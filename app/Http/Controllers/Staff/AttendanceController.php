<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->format('Y-m'));
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $attendance = Attendance::where('staff_id', $user->id)
            ->whereBetween('clock_in', [$startDate, $endDate])
            ->orderBy('clock_in', 'desc')
            ->get();
        
        // Get months with attendance for the filter
        $months = Attendance::where('staff_id', $user->id)
            ->selectRaw('DATE_FORMAT(clock_in, "%Y-%m") as month')
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->map(function ($date) {
                return [
                    'value' => $date,
                    'label' => Carbon::createFromFormat('Y-m', $date)->format('F Y')
                ];
            });
        
        return view('staff.attendance.index', [
            'attendance' => $attendance,
            'currentMonth' => $month,
            'months' => $months,
            'totalDays' => $startDate->daysInMonth,
            'presentDays' => $attendance->where('status', 'present')->count(),
            'halfDays' => $attendance->where('status', 'half_day')->count(),
            'absentDays' => $startDate->daysInMonth - $attendance->count(),
        ]);
    }

    /**
     * Handle staff clock in.
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        // Check if already clocked in today
        $existingAttendance = Attendance::where('staff_id', $user->id)
            ->whereDate('clock_in', $today)
            ->first();
            
        if ($existingAttendance) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'You have already clocked in today.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // Max 5MB
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Handle file upload
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
            'clock_in' => now(),
            'clock_in_photo' => $photoPath,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'present',
        ]);
        
        $attendance->save();
        
        return redirect()->route('staff.dashboard')
            ->with('success', 'Successfully clocked in at ' . now()->format('h:i A'));
    }
    
    /**
     * Handle staff clock out.
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        // Get today's attendance record
        $attendance = Attendance::where('staff_id', $user->id)
            ->whereDate('clock_in', $today)
            ->whereNull('clock_out')
            ->first();
            
        if (!$attendance) {
            return redirect()->route('staff.dashboard')
                ->with('error', 'No active attendance record found to clock out.');
        }
        
        // Validate the request
        $validated = $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // Max 5MB
            'status' => ['required', 'in:present,half_day'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'attendance/' . $user->id . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('public', $fileName);
            $photoPath = str_replace('public/', '', $photoPath);
        }
        
        // Update attendance record
        $attendance->update([
            'clock_out' => now(),
            'clock_out_photo' => $photoPath,
            'status' => $validated['status'],
            'notes' => $attendance->notes ? 
                $attendance->notes . "\n--- Clock Out ---\n" . ($validated['notes'] ?? '') : 
                ($validated['notes'] ?? null),
        ]);
        
        return redirect()->route('staff.dashboard')
            ->with('success', 'Successfully clocked out at ' . now()->format('h:i A'));
    }
    
    /**
     * Display the specified attendance record.
     */
    public function show($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('staff_id', Auth::id())
            ->firstOrFail();
            
        return view('staff.attendance.show', [
            'attendance' => $attendance,
        ]);
    }
    
    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit($id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('staff_id', Auth::id())
            ->whereNull('clock_out')
            ->firstOrFail();
            
        return view('staff.attendance.edit', [
            'attendance' => $attendance,
        ]);
    }
    
    /**
     * Update the specified attendance record.
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('staff_id', Auth::id())
            ->whereNull('clock_out')
            ->firstOrFail();
            
        $validated = $request->validate([
            'status' => ['required', 'in:present,half_day'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        
        $attendance->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('staff.attendance.index')
            ->with('success', 'Attendance record updated successfully.');
    }
    
    /**
     * Get the current attendance status for the authenticated user.
     */
    public function status()
    {
        $attendance = Attendance::where('staff_id', Auth::id())
            ->whereDate('clock_in', now()->format('Y-m-d'))
            ->first();
            
        return response()->json([
            'clocked_in' => $attendance && !$attendance->clock_out,
            'clock_in_time' => $attendance ? $attendance->clock_in->format('h:i A') : null,
            'clock_out_time' => $attendance && $attendance->clock_out ? $attendance->clock_out->format('h:i A') : null,
        ]);
    }
}
