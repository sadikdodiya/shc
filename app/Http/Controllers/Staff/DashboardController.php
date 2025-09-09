<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Complaint;
use App\Models\StaffPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the staff dashboard.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        
        // Get today's attendance
        $attendance = Attendance::where('staff_id', $user->id)
            ->whereDate('clock_in', $today)
            ->first();
        
        // Get complaints statistics
        $complaintStats = [
            'total' => $user->assignedComplaints()->count(),
            'open' => $user->assignedComplaints()->where('status', 'open')->count(),
            'in_progress' => $user->assignedComplaints()->where('status', 'in_progress')->count(),
            'resolved' => $user->assignedComplaints()->where('status', 'resolved')->count(),
            'cancelled' => $user->assignedComplaints()->where('status', 'cancelled')->count(),
        ];
        
        // Get recent complaints
        $recentComplaints = $user->assignedComplaints()
            ->with(['customer', 'product'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent payments
        $recentPayments = StaffPayment::where('staff_id', $user->id)
            ->with(['complaint'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('staff.dashboard', [
            'attendance' => $attendance,
            'complaintStats' => $complaintStats,
            'recentComplaints' => $recentComplaints,
            'recentPayments' => $recentPayments,
        ]);
    }
    
    /**
     * Show the attendance page.
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->format('Y-m'));
        
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $attendance = Attendance::where('staff_id', $user->id)
            ->whereBetween('clock_in', [$startDate, $endDate])
            ->orderBy('clock_in', 'desc')
            ->get();
        
        return view('staff.attendance', [
            'attendance' => $attendance,
            'currentMonth' => $month,
            'totalDays' => $startDate->daysInMonth,
            'presentDays' => $attendance->where('status', 'present')->count(),
            'halfDays' => $attendance->where('status', 'half_day')->count(),
            'absentDays' => $startDate->daysInMonth - $attendance->count(),
        ]);
    }
    
    /**
     * Show the complaints page.
     */
    public function complaints(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');
        
        $complaints = $user->assignedComplaints()
            ->with(['customer', 'product'])
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);
        
        return view('staff.complaints.index', [
            'complaints' => $complaints,
            'currentStatus' => $status,
        ]);
    }
    
    /**
     * Show a single complaint.
     */
    public function showComplaint($id)
    {
        $complaint = Complaint::with([
            'customer', 
            'product', 
            'remarks' => function ($query) {
                $query->with('staff')->latest();
            },
            'payments'
        ])->findOrFail($id);
        
        $this->authorize('view', $complaint);
        
        return view('staff.complaints.show', [
            'complaint' => $complaint,
            'attachments' => $complaint->getMedia('attachments'),
        ]);
    }
    
    /**
     * Show the payments page.
     */
    public function payments(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('year', now()->year);
        
        $payments = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $year)
            ->with(['complaint'])
            ->latest('payment_date')
            ->paginate(15);
        
        // Calculate summary
        $summary = [
            'total_credit' => $payments->sum(function ($payment) {
                return $payment->type === 'credit' ? $payment->amount : 0;
            }),
            'total_debit' => $payments->sum(function ($payment) {
                return $payment->type === 'debit' ? $payment->amount : 0;
            }),
            'balance' => $payments->sum(function ($payment) {
                return $payment->type === 'credit' ? $payment->amount : -$payment->amount;
            }),
        ];
        
        // Get years for filter
        $years = StaffPayment::selectRaw('YEAR(payment_date) as year')
            ->where('staff_id', $user->id)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('staff.payments', [
            'payments' => $payments,
            'summary' => $summary,
            'years' => $years,
            'currentYear' => $year,
        ]);
    }
}
