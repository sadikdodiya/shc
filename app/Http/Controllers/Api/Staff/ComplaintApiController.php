<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Api\Staff\BaseApiController;
use App\Models\Complaint;
use App\Models\ComplaintRemark;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintApiController extends BaseApiController
{
    /**
     * Get a list of complaints assigned to the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => ['nullable', 'in:open,in_progress,resolved,cancelled'],
                'search' => ['nullable', 'string', 'max:255'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
                'sort_by' => ['nullable', 'in:created_at,updated_at,due_date,priority'],
                'sort_order' => ['nullable', 'in:asc,desc'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $perPage = $request->input('per_page', 15);
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            $query = $user->assignedComplaints()
                ->with(['customer', 'product', 'assignedStaff']);
            
            // Apply status filter
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }
            
            // Apply search filter
            if ($search = $request->input('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('ticket_id', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            // Apply sorting
            $query->orderBy($sortBy, $sortOrder);
            
            // Get paginated results
            $complaints = $query->paginate($perPage);
            
            // Format the response
            $formattedComplaints = $complaints->map(function ($complaint) {
                return [
                    'id' => $complaint->id,
                    'ticket_id' => $complaint->ticket_id,
                    'subject' => $complaint->subject,
                    'status' => $complaint->status,
                    'priority' => $complaint->priority,
                    'created_at' => $complaint->created_at,
                    'due_date' => $complaint->due_date,
                    'customer' => $complaint->customer ? [
                        'id' => $complaint->customer->id,
                        'name' => $complaint->customer->name,
                        'phone' => $complaint->customer->phone,
                        'email' => $complaint->customer->email,
                    ] : null,
                    'product' => $complaint->product ? [
                        'id' => $complaint->product->id,
                        'name' => $complaint->product->name,
                        'model_number' => $complaint->product->model_number,
                    ] : null,
                    'assigned_to' => $complaint->assignedStaff ? [
                        'id' => $complaint->assignedStaff->id,
                        'name' => $complaint->assignedStaff->name,
                    ] : null,
                ];
            });
            
            $response = [
                'data' => $formattedComplaints,
                'pagination' => [
                    'total' => $complaints->total(),
                    'per_page' => $complaints->perPage(),
                    'current_page' => $complaints->currentPage(),
                    'last_page' => $complaints->lastPage(),
                ],
                'filters' => [
                    'status' => $request->input('status'),
                    'search' => $request->input('search'),
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ],
                'status_counts' => [
                    'total' => $user->assignedComplaints()->count(),
                    'open' => $user->assignedComplaints()->where('status', 'open')->count(),
                    'in_progress' => $user->assignedComplaints()->where('status', 'in_progress')->count(),
                    'resolved' => $user->assignedComplaints()->where('status', 'resolved')->count(),
                    'cancelled' => $user->assignedComplaints()->where('status', 'cancelled')->count(),
                ],
            ];
            
            return $this->success($response, 'Complaints retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve complaints');
        }
    }
    
    /**
     * Get details of a specific complaint
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $complaint = Complaint::with([
                'customer', 
                'product', 
                'assignedStaff',
                'remarks' => function ($query) {
                    $query->with('staff')->latest();
                },
                'payments',
                'attachments'
            ])->findOrFail($id);
            
            // Check if the complaint is assigned to the authenticated staff member
            if ($complaint->assigned_to !== $user->id) {
                return $this->unauthorized('You are not authorized to view this complaint');
            }
            
            // Format the response
            $formattedComplaint = [
                'id' => $complaint->id,
                'ticket_id' => $complaint->ticket_id,
                'subject' => $complaint->subject,
                'description' => $complaint->description,
                'status' => $complaint->status,
                'priority' => $complaint->priority,
                'created_at' => $complaint->created_at,
                'updated_at' => $complaint->updated_at,
                'due_date' => $complaint->due_date,
                'resolved_at' => $complaint->resolved_at,
                'customer' => $complaint->customer ? [
                    'id' => $complaint->customer->id,
                    'name' => $complaint->customer->name,
                    'phone' => $complaint->customer->phone,
                    'email' => $complaint->customer->email,
                    'address' => $complaint->customer->address,
                ] : null,
                'product' => $complaint->product ? [
                    'id' => $complaint->product->id,
                    'name' => $complaint->product->name,
                    'model_number' => $complaint->product->model_number,
                    'serial_number' => $complaint->product->serial_number,
                    'purchase_date' => $complaint->product->purchase_date,
                    'warranty_status' => $complaint->product->warranty_status,
                ] : null,
                'assigned_to' => $complaint->assignedStaff ? [
                    'id' => $complaint->assignedStaff->id,
                    'name' => $complaint->assignedStaff->name,
                    'email' => $complaint->assignedStaff->email,
                    'phone' => $complaint->assignedStaff->phone,
                ] : null,
                'remarks' => $complaint->remarks->map(function ($remark) {
                    return [
                        'id' => $remark->id,
                        'message' => $remark->message,
                        'status' => $remark->status,
                        'created_at' => $remark->created_at,
                        'staff' => $remark->staff ? [
                            'id' => $remark->staff->id,
                            'name' => $remark->staff->name,
                        ] : null,
                        'photo_url' => $remark->photo_url,
                    ];
                }),
                'attachments' => $complaint->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'file_name' => $attachment->file_name,
                        'file_type' => $attachment->file_type,
                        'file_size' => $attachment->file_size,
                        'url' => $attachment->getUrl(),
                        'created_at' => $attachment->created_at,
                    ];
                }),
                'payments' => $complaint->payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'transaction_id' => $payment->transaction_id,
                        'status' => $payment->status,
                        'notes' => $payment->notes,
                        'created_at' => $payment->created_at,
                    ];
                }),
            ];
            
            return $this->success($formattedComplaint, 'Complaint details retrieved successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Complaint not found');
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve complaint details');
        }
    }
    
    /**
     * Update the status of a complaint
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => ['required', 'in:open,in_progress,resolved,cancelled'],
                'message' => ['required_if:status,resolved,cancelled', 'string', 'max:1000'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $complaint = Complaint::findOrFail($id);
            
            // Check if the complaint is assigned to the authenticated staff member
            if ($complaint->assigned_to !== $user->id) {
                return $this->unauthorized('You are not authorized to update this complaint');
            }
            
            $previousStatus = $complaint->status;
            $newStatus = $request->input('status');
            
            // Update complaint status
            $updateData = ['status' => $newStatus];
            
            // If status is resolved, set resolved_at timestamp
            if ($newStatus === 'resolved' && $previousStatus !== 'resolved') {
                $updateData['resolved_at'] = now();
            }
            
            $complaint->update($updateData);
            
            // Add remark for status change
            if ($previousStatus !== $newStatus) {
                $statusLabels = [
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'resolved' => 'Resolved',
                    'cancelled' => 'Cancelled',
                ];
                
                $message = "Status changed from {$statusLabels[$previousStatus]} to {$statusLabels[$newStatus]}.";
                
                if (!empty($request->input('message'))) {
                    $message .= "\n\n" . $request->input('message');
                }
                
                $this->addRemark($complaint->id, $message, null, $newStatus);
            }
            
            return $this->success(
                ['status' => $newStatus],
                'Complaint status updated successfully'
            );
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Complaint not found');
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to update complaint status');
        }
    }
    
    /**
     * Add a remark to a complaint
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function addRemarkToComplaint(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => ['required', 'string', 'max:1000'],
                'status' => ['nullable', 'in:open,in_progress,resolved,cancelled'],
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $complaint = Complaint::findOrFail($id);
            
            // Check if the complaint is assigned to the authenticated staff member
            if ($complaint->assigned_to !== $user->id) {
                return $this->unauthorized('You are not authorized to add remarks to this complaint');
            }
            
            // Add the remark
            $remark = $this->addRemark(
                $complaint->id,
                $request->input('message'),
                $request->file('photo'),
                $request->input('status')
            );
            
            // Update complaint status if provided
            if ($request->has('status')) {
                $complaint->update(['status' => $request->input('status')]);
                
                // If status is resolved, set resolved_at timestamp
                if ($request->input('status') === 'resolved' && $complaint->resolved_at === null) {
                    $complaint->update(['resolved_at' => now()]);
                }
            }
            
            // Format the response
            $formattedRemark = [
                'id' => $remark->id,
                'message' => $remark->message,
                'status' => $remark->status,
                'created_at' => $remark->created_at,
                'photo_url' => $remark->photo_url,
                'staff' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
            ];
            
            return $this->success($formattedRemark, 'Remark added successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Complaint not found');
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to add remark');
        }
    }
    
    /**
     * Helper method to add a remark to a complaint
     *
     * @param int $complaintId
     * @param string $message
     * @param \Illuminate\Http\UploadedFile|null $photo
     * @param string|null $status
     * @return ComplaintRemark
     */
    private function addRemark(int $complaintId, string $message, $photo = null, ?string $status = null): ComplaintRemark
    {
        $photoPath = null;
        
        // Handle photo upload if provided
        if ($photo && $photo->isValid()) {
            $fileName = 'complaints/' . $complaintId . '/remarks/' . Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public', $fileName);
            $photoPath = str_replace('public/', '', $photoPath);
        }
        
        // Create the remark
        $remark = new ComplaintRemark([
            'complaint_id' => $complaintId,
            'staff_id' => auth()->id(),
            'message' => $message,
            'photo_path' => $photoPath,
            'status' => $status,
        ]);
        
        $remark->save();
        
        return $remark;
    }
    
    /**
     * Get statistics for complaints
     *
     * @return JsonResponse
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Get counts by status
            $counts = [
                'total' => $user->assignedComplaints()->count(),
                'open' => $user->assignedComplaints()->where('status', 'open')->count(),
                'in_progress' => $user->assignedComplaints()->where('status', 'in_progress')->count(),
                'resolved' => $user->assignedComplaints()->where('status', 'resolved')->count(),
                'cancelled' => $user->assignedComplaints()->where('status', 'cancelled')->count(),
            ];
            
            // Get complaints by month for the current year
            $currentYear = now()->year;
            $monthlyData = [];
            
            for ($month = 1; $month <= 12; $month++) {
                $startDate = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                
                $monthlyData[] = [
                    'month' => $startDate->format('M'),
                    'open' => $user->assignedComplaints()
                        ->where('status', 'open')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                    'in_progress' => $user->assignedComplaints()
                        ->where('status', 'in_progress')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                    'resolved' => $user->assignedComplaints()
                        ->where('status', 'resolved')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                    'cancelled' => $user->assignedComplaints()
                        ->where('status', 'cancelled')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                ];
            }
            
            // Get recent activities
            $recentActivities = $user->assignedComplaints()
                ->with(['customer', 'product'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($complaint) {
                    return [
                        'id' => $complaint->id,
                        'ticket_id' => $complaint->ticket_id,
                        'subject' => $complaint->subject,
                        'status' => $complaint->status,
                        'updated_at' => $complaint->updated_at,
                        'customer' => $complaint->customer ? [
                            'name' => $complaint->customer->name,
                        ] : null,
                        'product' => $complaint->product ? [
                            'name' => $complaint->product->name,
                        ] : null,
                    ];
                });
            
            $response = [
                'counts' => $counts,
                'monthly_data' => $monthlyData,
                'recent_activities' => $recentActivities,
            ];
            
            return $this->success($response, 'Complaint statistics retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve complaint statistics');
        }
    }
}
