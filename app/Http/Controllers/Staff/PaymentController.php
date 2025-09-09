<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the staff's payments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        $type = $request->input('type');
        
        $query = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $year);
            
        if ($month) {
            $query->whereMonth('payment_date', $month);
        }
        
        if (in_array($type, ['credit', 'debit'])) {
            $query->where('type', $type);
        }
        
        $payments = $query->with(['complaint', 'recordedBy'])
            ->orderBy('payment_date', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        // Calculate summary
        $summary = [
            'total_credit' => $this->getPaymentSummary($user->id, $year, $month, 'credit'),
            'total_debit' => $this->getPaymentSummary($user->id, $year, $month, 'debit'),
            'balance' => $this->getPaymentSummary($user->id, $year, $month, 'balance'),
        ];
        
        // Get years for filter
        $years = StaffPayment::selectRaw('YEAR(payment_date) as year')
            ->where('staff_id', $user->id)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        // Get months for filter
        $months = [
            ['value' => '01', 'label' => 'January'],
            ['value' => '02', 'label' => 'February'],
            ['value' => '03', 'label' => 'March'],
            ['value' => '04', 'label' => 'April'],
            ['value' => '05', 'label' => 'May'],
            ['value' => '06', 'label' => 'June'],
            ['value' => '07', 'label' => 'July'],
            ['value' => '08', 'label' => 'August'],
            ['value' => '09', 'label' => 'September'],
            ['value' => '10', 'label' => 'October'],
            ['value' => '11', 'label' => 'November'],
            ['value' => '12', 'label' => 'December'],
        ];
        
        return view('staff.payments.index', [
            'payments' => $payments,
            'summary' => $summary,
            'years' => $years,
            'months' => $months,
            'currentYear' => $year,
            'currentMonth' => $month,
            'currentType' => $type,
        ]);
    }
    
    /**
     * Get payment summary for the given filters.
     */
    private function getPaymentSummary($staffId, $year, $month = null, $type = 'balance')
    {
        $query = StaffPayment::where('staff_id', $staffId)
            ->whereYear('payment_date', $year);
            
        if ($month) {
            $query->whereMonth('payment_date', $month);
        }
        
        if ($type === 'credit') {
            return $query->where('type', 'credit')->sum('amount');
        } elseif ($type === 'debit') {
            return $query->where('type', 'debit')->sum('amount');
        } else {
            // Balance
            $credits = (clone $query)->where('type', 'credit')->sum('amount');
            $debits = (clone $query)->where('type', 'debit')->sum('amount');
            return $credits - $debits;
        }
    }
    
    /**
     * Get payment statistics for the dashboard.
     */
    public function getPaymentStats()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        // Current month stats
        $monthCredits = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $currentYear)
            ->whereMonth('payment_date', $currentMonth)
            ->where('type', 'credit')
            ->sum('amount');
            
        $monthDebits = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $currentYear)
            ->whereMonth('payment_date', $currentMonth)
            ->where('type', 'debit')
            ->sum('amount');
            
        // Year to date stats
        $ytdCredits = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $currentYear)
            ->where('type', 'credit')
            ->sum('amount');
            
        $ytdDebits = StaffPayment::where('staff_id', $user->id)
            ->whereYear('payment_date', $currentYear)
            ->where('type', 'debit')
            ->sum('amount');
            
        // Total balance
        $totalCredits = StaffPayment::where('staff_id', $user->id)
            ->where('type', 'credit')
            ->sum('amount');
            
        $totalDebits = StaffPayment::where('staff_id', $user->id)
            ->where('type', 'debit')
            ->sum('amount');
        
        return response()->json([
            'current_month' => [
                'credits' => $monthCredits,
                'debits' => $monthDebits,
                'balance' => $monthCredits - $monthDebits,
            ],
            'year_to_date' => [
                'credits' => $ytdCredits,
                'debits' => $ytdDebits,
                'balance' => $ytdCredits - $ytdDebits,
            ],
            'all_time' => [
                'credits' => $totalCredits,
                'debits' => $totalDebits,
                'balance' => $totalCredits - $totalDebits,
            ],
        ]);
    }
    
    /**
     * Get payment history for the specified period.
     */
    public function getPaymentHistory(Request $request)
    {
        $user = Auth::user();
        $period = $request->input('period', 'month'); // month, year, all
        
        $query = StaffPayment::where('staff_id', $user->id)
            ->with(['complaint', 'recordedBy']);
            
        if ($period === 'month') {
            $query->whereYear('payment_date', now()->year)
                ->whereMonth('payment_date', now()->month);
        } elseif ($period === 'year') {
            $query->whereYear('payment_date', now()->year);
        }
        
        $payments = $query->orderBy('payment_date', 'desc')
            ->take(10)
            ->get();
            
        return response()->json($payments);
    }
    
    /**
     * Show the specified payment details.
     */
    public function show($id)
    {
        $payment = StaffPayment::with(['complaint', 'recordedBy'])
            ->where('id', $id)
            ->where('staff_id', Auth::id())
            ->firstOrFail();
            
        return view('staff.payments.show', [
            'payment' => $payment,
        ]);
    }
}
