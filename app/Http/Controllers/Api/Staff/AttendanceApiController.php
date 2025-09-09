<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Api\Staff\BaseApiController;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceApiController extends BaseApiController
{
    /**
     * Get the current user's attendance status
     *
     * @return JsonResponse
     */
    public function getStatus(): JsonResponse
    {
        try {
            $user = auth()->user();
            $today = now()->startOfDay();
            
            $attendance = Attendance::where('staff_id', $user->id)
                ->whereDate('clock_in', '>=', $today)
                ->whereNull('clock_out')
                ->first();
            
            $data = [
                'is_clocked_in' => !is_null($attendance),
                'clock_in_time' => $attendance ? $attendance->clock_in : null,
                'current_duration' => $attendance ? now()->diffInMinutes($attendance->clock_in) : 0,
                'last_clock_in' => $user->last_clock_in,
                'last_clock_out' => $user->last_clock_out,
            ];
            
            return $this->success($data, 'Attendance status retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve attendance status');
        }
    }
    
    /**
     * Clock in the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clockIn(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $now = now();
            $today = $now->copy()->startOfDay();
            
            // Check if already clocked in today
            $existingAttendance = Attendance::where('staff_id', $user->id)
                ->whereDate('clock_in', '>=', $today)
                ->whereNull('clock_out')
                ->exists();
                
            if ($existingAttendance) {
                return $this->error('You are already clocked in for today', 400);
            }
            
            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = 'attendance/' . $user->id . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('public', $fileName);
                $photoPath = str_replace('public/', '', $photoPath);
            }
            
            // Create attendance record
            $attendance = new Attendance([
                'staff_id' => $user->id,
                'clock_in' => $now,
                'clock_in_photo' => $photoPath,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'notes' => $request->input('notes'),
                'status' => 'present',
            ]);
            
            $attendance->save();
            
            // Update user's last clock in time
            $user->last_clock_in = $now;
            $user->save();
            
            return $this->success([
                'attendance_id' => $attendance->id,
                'clock_in_time' => $attendance->clock_in,
                'photo_url' => $attendance->clock_in_photo_url,
            ], 'Successfully clocked in');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to clock in');
        }
    }
    
    /**
     * Clock out the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clockOut(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'attendance_id' => ['required', 'exists:attendances,id'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'notes' => ['nullable', 'string', 'max:1000'],
                'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // Max 5MB
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $now = now();
            
            // Find the attendance record
            $attendance = Attendance::where('id', $request->input('attendance_id'))
                ->where('staff_id', $user->id)
                ->whereNull('clock_out')
                ->first();
                
            if (!$attendance) {
                return $this->error('No active attendance record found or already clocked out', 400);
            }
            
            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = 'attendance/' . $user->id . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $photoPath = $file->storeAs('public', $fileName);
                $photoPath = str_replace('public/', '', $photoPath);
            }
            
            // Update attendance record
            $attendance->update([
                'clock_out' => $now,
                'clock_out_photo' => $photoPath,
                'latitude_out' => $request->input('latitude'),
                'longitude_out' => $request->input('longitude'),
                'notes' => $attendance->notes ? $attendance->notes . "\n\n" . $request->input('notes', '') : $request->input('notes'),
                'duration_minutes' => $now->diffInMinutes($attendance->clock_in),
            ]);
            
            // Update user's last clock out time
            $user->last_clock_out = $now;
            $user->save();
            
            return $this->success([
                'attendance_id' => $attendance->id,
                'clock_in_time' => $attendance->clock_in,
                'clock_out_time' => $attendance->clock_out,
                'duration_minutes' => $attendance->duration_minutes,
                'photo_url' => $attendance->clock_out_photo_url,
            ], 'Successfully clocked out');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to clock out');
        }
    }
    
    /**
     * Get attendance history for the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => ['nullable', 'date'],
                'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $perPage = $request->input('per_page', 15);
            $query = Attendance::where('staff_id', $user->id);
            
            // Apply date filters
            if ($request->has('start_date')) {
                $query->whereDate('clock_in', '>=', $request->input('start_date'));
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('clock_in', '<=', $request->input('end_date'));
            }
            
            // Order and paginate
            $attendances = $query->orderBy('clock_in', 'desc')
                ->paginate($perPage);
            
            // Format the response
            $formattedAttendances = $attendances->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->clock_in->toDateString(),
                    'clock_in' => $attendance->clock_in,
                    'clock_out' => $attendance->clock_out,
                    'duration_minutes' => $attendance->duration_minutes,
                    'status' => $attendance->status,
                    'notes' => $attendance->notes,
                    'clock_in_photo_url' => $attendance->clock_in_photo_url,
                    'clock_out_photo_url' => $attendance->clock_out_photo_url,
                    'location' => $attendance->latitude && $attendance->longitude 
                        ? ['latitude' => $attendance->latitude, 'longitude' => $attendance->longitude]
                        : null,
                ];
            });
            
            $response = [
                'data' => $formattedAttendances,
                'pagination' => [
                    'total' => $attendances->total(),
                    'per_page' => $attendances->perPage(),
                    'current_page' => $attendances->currentPage(),
                    'last_page' => $attendances->lastPage(),
                ]
            ];
            
            return $this->success($response, 'Attendance history retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve attendance history');
        }
    }
    
    /**
     * Get attendance summary for the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function summary(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'month' => ['nullable', 'integer', 'between:1,12'],
                'year' => ['nullable', 'integer', 'digits:4'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            
            // Get all attendances for the month
            $attendances = Attendance::where('staff_id', $user->id)
                ->whereBetween('clock_in', [$startDate, $endDate])
                ->get();
            
            // Calculate summary
            $totalDays = $startDate->daysInMonth;
            $presentDays = $attendances->count();
            $totalHours = $attendances->sum('duration_minutes') / 60;
            $averageHoursPerDay = $presentDays > 0 ? $totalHours / $presentDays : 0;
            
            $summary = [
                'month' => $startDate->format('F Y'),
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $totalDays - $presentDays,
                'total_hours' => round($totalHours, 2),
                'average_hours_per_day' => round($averageHoursPerDay, 2),
                'status' => $this->calculateAttendanceStatus($presentDays, $totalDays),
            ];
            
            return $this->success($summary, 'Attendance summary retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve attendance summary');
        }
    }
    
    /**
     * Calculate attendance status based on present days
     *
     * @param int $presentDays
     * @param int $totalDays
     * @return string
     */
    private function calculateAttendanceStatus(int $presentDays, int $totalDays): string
    {
        $attendancePercentage = ($presentDays / $totalDays) * 100;
        
        if ($attendancePercentage >= 90) {
            return 'excellent';
        } elseif ($attendancePercentage >= 75) {
            return 'good';
        } elseif ($attendancePercentage >= 60) {
            return 'average';
        } else {
            return 'needs_improvement';
        }
    }
}
