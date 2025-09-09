<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the complaints assigned to the staff.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');
        $search = $request->input('search');
        
        $complaints = $user->assignedComplaints()
            ->with(['customer', 'product'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('ticket_id', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->orderByRaw("FIELD(status, 'open', 'in_progress', 'resolved', 'cancelled')")
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        $statuses = [
            'all' => 'All Complaints',
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'resolved' => 'Resolved',
            'cancelled' => 'Cancelled',
        ];
        
        return view('staff.complaints.index', [
            'complaints' => $complaints,
            'statuses' => $statuses,
            'currentStatus' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Display the specified complaint.
     */
    public function show($id)
    {
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
        
        $this->authorize('view', $complaint);
        
        return view('staff.complaints.show', [
            'complaint' => $complaint,
            'attachments' => $complaint->getMedia('attachments'),
        ]);
    }
    
    /**
     * Update the status of the specified complaint.
     */
    public function updateStatus(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $this->authorize('update', $complaint);
        
        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,cancelled'],
            'message' => ['required_if:status,resolved,cancelled', 'string', 'max:1000'],
        ]);
        
        $previousStatus = $complaint->status;
        $newStatus = $validated['status'];
        
        // Update complaint status
        $complaint->update(['status' => $newStatus]);
        
        // Add remark for status change
        if ($previousStatus !== $newStatus) {
            $statusLabels = [
                'open' => 'Open',
                'in_progress' => 'In Progress',
                'resolved' => 'Resolved',
                'cancelled' => 'Cancelled',
            ];
            
            $message = "Status changed from {$statusLabels[$previousStatus]} to {$statusLabels[$newStatus]}.";
            
            if (!empty($validated['message'])) {
                $message .= "\n\n" . $validated['message'];
            }
            
            $this->addRemark($complaint->id, $message);
            
            // Trigger any status change events/notifications here if needed
        }
        
        return redirect()->back()
            ->with('success', 'Complaint status updated successfully.');
    }
    
    /**
     * Add a remark to the complaint.
     */
    public function addRemark($id, $message, $photo = null, $status = null)
    {
        $complaint = Complaint::findOrFail($id);
        $this->authorize('update', $complaint);
        
        $photoPath = null;
        if ($photo && $photo->isValid()) {
            $fileName = 'complaints/' . $id . '/remarks/' . Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public', $fileName);
            $photoPath = str_replace('public/', '', $photoPath);
        }
        
        $remark = new ComplaintRemark([
            'complaint_id' => $id,
            'staff_id' => Auth::id(),
            'message' => $message,
            'photo_path' => $photoPath,
            'status' => $status,
        ]);
        
        $remark->save();
        
        return $remark;
    }
    
    /**
     * Store a newly created remark in storage.
     */
    public function storeRemark(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $this->authorize('update', $complaint);
        
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:5120'], // Max 5MB
            'status' => ['nullable', 'in:open,in_progress,resolved,cancelled'],
        ]);
        
        $remark = $this->addRemark(
            $id, 
            $validated['message'], 
            $request->file('photo'),
            $validated['status'] ?? null
        );
        
        // Update complaint status if provided
        if (!empty($validated['status'])) {
            $complaint->update(['status' => $validated['status']]);
        }
        
        return redirect()->back()
            ->with('success', 'Remark added successfully.');
    }
    
    /**
     * Get the complaint status statistics.
     */
    public function getStats()
    {
        $user = Auth::user();
        
        $stats = [
            'total' => $user->assignedComplaints()->count(),
            'open' => $user->assignedComplaints()->where('status', 'open')->count(),
            'in_progress' => $user->assignedComplaints()->where('status', 'in_progress')->count(),
            'resolved' => $user->assignedComplaints()->where('status', 'resolved')->count(),
            'cancelled' => $user->assignedComplaints()->where('status', 'cancelled')->count(),
        ];
        
        return response()->json($stats);
    }
    
    /**
     * Get the recent complaints for the dashboard.
     */
    public function getRecentComplaints()
    {
        $user = Auth::user();
        
        $complaints = $user->assignedComplaints()
            ->with(['customer', 'product'])
            ->latest()
            ->take(5)
            ->get();
            
        return response()->json($complaints);
    }
}
