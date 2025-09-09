<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Api\Staff\BaseApiController;
use App\Models\StaffPayment;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends BaseApiController
{
    /**
     * Get payment history for the authenticated staff member
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_date' => ['nullable', 'date'],
                'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
                'type' => ['nullable', 'in:credit,debit'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
                'sort_by' => ['nullable', 'in:payment_date,amount,created_at'],
                'sort_order' => ['nullable', 'in:asc,desc'],
            ]);
            
            if ($validator->fails()) {
                return $this->validationError($validator);
            }
            
            $user = auth()->user();
            $perPage = $request->input('per_page', 15);
            $sortBy = $request->input('sort_by', 'payment_date');
            $sortOrder = $request->input('sort_order', 'desc');
            
            $query = StaffPayment::where('staff_id', $user->id)
                ->with(['complaint', 'recordedBy']);
            
            // Apply date filters
            if ($request->has('start_date')) {
                $query->whereDate('payment_date', '>=', $request->input('start_date'));
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('payment_date', '<=', $request->input('end_date'));
            }
            
            // Apply type filter
            if ($request->has('type')) {
                $query->where('type', $request->input('type'));
            }
            
            // Apply sorting
            $query->orderBy($sortBy, $sortOrder);
            
            // Get paginated results
            $payments = $query->paginate($perPage);
            
            // Format the response
            $formattedPayments = $payments->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'amount' => $payment->amount,
                    'type' => $payment->type,
                    'payment_date' => $payment->payment_date,
                    'payment_method' => $payment->payment_method,
                    'transaction_id' => $payment->transaction_id,
                    'status' => $payment->status,
                    'notes' => $payment->notes,
                    'created_at' => $payment->created_at,
                    'complaint' => $payment->complaint ? [
                        'id' => $payment->complaint->id,
                        'ticket_id' => $payment->complaint->ticket_id,
                        'subject' => $payment->complaint->subject,
                    ] : null,
                    'recorded_by' => $payment->recordedBy ? [
                        'id' => $payment->recordedBy->id,
                        'name' => $payment->recordedBy->name,
                    ] : null,
                ];
            });
            
            // Calculate summary
            $summary = [
                'total_credit' => $this->getPaymentSummary($user->id, $request->input('start_date'), $request->input('end_date'), 'credit'),
                'total_debit' => $this->getPaymentSummary($user->id, $request->input('start_date'), $request->input('end_date'), 'debit'),
                'balance' => $this->getPaymentSummary($user->id, $request->input('start_date'), $request->input('end_date'), 'balance'),
            ];
            
            $response = [
                'data' => $formattedPayments,
                'summary' => $summary,
                'pagination' => [
                    'total' => $payments->total(),
                    'per_page' => $payments->perPage(),
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                ],
                'filters' => [
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'type' => $request->input('type'),
                    'sort_by' => $sortBy,
                    'sort_order' => $sortOrder,
                ],
            ];
            
            return $this->success($response, 'Payment history retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve payment history');
        }
    }
    
    /**
     * Get payment summary for the given filters
     *
     * @param int $staffId
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string $type
     * @return float
     */
    private function getPaymentSummary(int $staffId, ?string $startDate, ?string $endDate, string $type): float
    {
        $query = StaffPayment::where('staff_id', $staffId);
        
        if ($startDate) {
            $query->whereDate('payment_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('payment_date', '<=', $endDate);
        }
        
        if ($type === 'credit') {
            return (float) $query->clone()->where('type', 'credit')->sum('amount');
        } elseif ($type === 'debit') {
            return (float) $query->clone()->where('type', 'debit')->sum('amount');
        } else {
            // Balance
            $credits = (float) $query->clone()->where('type', 'credit')->sum('amount');
            $debits = (float) $query->clone()->where('type', 'debit')->sum('amount');
            return $credits - $debits;
        }
    }
    
    /**
     * Get payment statistics for the dashboard
     *
     * @return JsonResponse
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $user = auth()->user();
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
            
            // Payment methods distribution
            $paymentMethods = StaffPayment::where('staff_id', $user->id)
                ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total_amount')
                ->groupBy('payment_method')
                ->get()
                ->map(function ($item) {
                    return [
                        'method' => $item->payment_method,
                        'count' => $item->count,
                        'total_amount' => (float) $item->total_amount,
                    ];
                });
            
            // Monthly trend for the current year
            $monthlyTrend = [];
            
            for ($month = 1; $month <= 12; $month++) {
                $startDate = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                
                $credits = StaffPayment::where('staff_id', $user->id)
                    ->where('type', 'credit')
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->sum('amount');
                    
                $debits = StaffPayment::where('staff_id', $user->id)
                    ->where('type', 'debit')
                    ->whereBetween('payment_date', [$startDate, $endDate])
                    ->sum('amount');
                
                $monthlyTrend[] = [
                    'month' => $startDate->format('M'),
                    'credits' => (float) $credits,
                    'debits' => (float) $debits,
                    'balance' => (float) ($credits - $debits),
                ];
            }
            
            $response = [
                'current_month' => [
                    'credits' => (float) $monthCredits,
                    'debits' => (float) $monthDebits,
                    'balance' => (float) ($monthCredits - $monthDebits),
                ],
                'year_to_date' => [
                    'credits' => (float) $ytdCredits,
                    'debits' => (float) $ytdDebits,
                    'balance' => (float) ($ytdCredits - $ytdDebits),
                ],
                'all_time' => [
                    'credits' => (float) $totalCredits,
                    'debits' => (float) $totalDebits,
                    'balance' => (float) ($totalCredits - $totalDebits),
                ],
                'payment_methods' => $paymentMethods,
                'monthly_trend' => $monthlyTrend,
            ];
            
            return $this->success($response, 'Payment statistics retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve payment statistics');
        }
    }
    
    /**
     * Get recent payments for the dashboard
     *
     * @return JsonResponse
     */
    public function getRecentPayments(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $recentPayments = StaffPayment::where('staff_id', $user->id)
                ->with(['complaint', 'recordedBy'])
                ->orderBy('payment_date', 'desc')
                ->take(10)
                ->get()
                ->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'type' => $payment->type,
                        'payment_date' => $payment->payment_date,
                        'payment_method' => $payment->payment_method,
                        'status' => $payment->status,
                        'notes' => $payment->notes,
                        'complaint' => $payment->complaint ? [
                            'id' => $payment->complaint->id,
                            'ticket_id' => $payment->complaint->ticket_id,
                            'subject' => $payment->complaint->subject,
                        ] : null,
                        'recorded_by' => $payment->recordedBy ? [
                            'name' => $payment->recordedBy->name,
                        ] : null,
                    ];
                });
            
            return $this->success($recentPayments, 'Recent payments retrieved successfully');
            
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve recent payments');
        }
    }
    
    /**
     * Get payment details by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $payment = StaffPayment::with(['complaint', 'recordedBy', 'attachments'])
                ->where('staff_id', $user->id)
                ->findOrFail($id);
            
            $formattedPayment = [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'type' => $payment->type,
                'payment_date' => $payment->payment_date,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $payment->transaction_id,
                'status' => $payment->status,
                'notes' => $payment->notes,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
                'complaint' => $payment->complaint ? [
                    'id' => $payment->complaint->id,
                    'ticket_id' => $payment->complaint->ticket_id,
                    'subject' => $payment->complaint->subject,
                    'status' => $payment->complaint->status,
                ] : null,
                'recorded_by' => $payment->recordedBy ? [
                    'id' => $payment->recordedBy->id,
                    'name' => $payment->recordedBy->name,
                    'email' => $payment->recordedBy->email,
                ] : null,
                'attachments' => $payment->attachments->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'file_name' => $attachment->file_name,
                        'file_type' => $attachment->file_type,
                        'file_size' => $attachment->file_size,
                        'url' => $attachment->getUrl(),
                        'created_at' => $attachment->created_at,
                    ];
                }),
            ];
            
            return $this->success($formattedPayment, 'Payment details retrieved successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->notFound('Payment not found');
        } catch (\Exception $e) {
            return $this->serverError($e, 'Failed to retrieve payment details');
        }
    }
}
