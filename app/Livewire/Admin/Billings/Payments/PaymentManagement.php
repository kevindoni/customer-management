<?php

namespace App\Livewire\Admin\Billings\Payments;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Billings\Payment;
use App\Models\Billings\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentManagement extends Component
{
    use WithPagination;

    public $perPage = 25;
    public $sortField = 'full_name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];


    public $search_with_month = "";
    public $search_with_year = "";
    public $search_with_teller;

    public $startDateDeadline;
    public $endDateDeadline;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function clearSearch()
    {
        $this->startDateDeadline = null;
        $this->endDateDeadline = null;
        $this->search_with_year = "";
        $this->search_with_month = "";
        $this->search_with_teller = null;
    }

    public function clearSearchWithYear()
    {
        $this->search_with_year = "";
    }
    public function clearSearchWithMonth()
    {
        $this->search_with_month = "";
    }

    public function clearSearchWithTeller()
    {
        $this->search_with_teller = null;
    }

    public function clearSearchDateDeadline()
    {
        $this->startDateDeadline = null;
        $this->endDateDeadline = null;
    }
    public function mount()
    {
        $this->search_with_month = Carbon::now()->format('m');
        $this->search_with_year = Carbon::now()->format('Y');
    }

    public function getInvoicePayments()
    {
        return Invoice::whereHas('payments', function ($q) {
            $q->where('reconciliation_status', '!=', 'discrepancy');
            $q->when($this->search_with_teller, function ($builder) {
                $this->resetPage();
                $builder->where('teller', $this->search_with_teller);
            });

            $q->when($this->search_with_month, function ($builder) {
                $builder->whereMonth('payment_date', $this->search_with_month);
            }, function ($builder) {
                return $builder->whereMonth('payment_date', Carbon::now()->format('m'));
            });

            $q->when($this->search_with_year, function ($builder) {
                $builder->whereYear('payment_date', $this->search_with_year);
            }, function ($builder) {
                return $builder->whereYear('payment_date', Carbon::now()->format('Y'));
            });

            $q->when($this->startDateDeadline, function ($builder) {
                $this->search_with_month = "";
                $this->search_with_year = "";
                if ($this->startDateDeadline && $this->endDateDeadline) {
                    $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                } else {
                   $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                }
            });
        })
            ->with('payments', function ($q) {
                $q->where('reconciliation_status', '!=', 'discrepancy');
                $q->when($this->search_with_teller, function ($builder) {
                    $this->resetPage();
                    $builder->where('teller', $this->search_with_teller);
                });

                $q->when($this->search_with_month, function ($builder) {
                    $builder->whereMonth('payment_date', $this->search_with_month);
                }, function ($builder) {
                    return $builder->whereMonth('payment_date', Carbon::now()->format('m'));
                });

                $q->when($this->search_with_year, function ($builder) {
                    $builder->whereYear('payment_date', $this->search_with_year);
                }, function ($builder) {
                    return $builder->whereYear('payment_date', Carbon::now()->format('Y'));
                });

                $q->when($this->startDateDeadline, function ($builder) {
                    $this->search_with_month = "";
                    $this->search_with_year = "";
                    if ($this->startDateDeadline && $this->endDateDeadline) {
                        $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                    } else {
                        $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                    }
                });
            })

            ->paginate($this->perPage);
    }

    public function getPaymentsAmount()
    {
        $payments = Payment::where('reconciliation_status', '!=', 'discrepancy')
            ->when($this->search_with_teller, function ($builder) {
                $this->resetPage();
                $builder->where('teller', $this->search_with_teller);
            })
            ->when($this->search_with_month, function ($builder) {
                $builder->whereMonth('payment_date', $this->search_with_month);
            })

            ->when($this->search_with_year, function ($builder) {
                $builder->whereYear('payment_date', $this->search_with_year);
            })
            ->when($this->startDateDeadline, function ($builder) {
                $this->search_with_month = "";
                $this->search_with_year = "";
                if ($this->startDateDeadline && $this->endDateDeadline) {
                    $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                } else {
                    $builder->whereBetween('payment_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                }
            })
            ->get();

        return $payments->sum('amount');
    }


    public function render()
    {
        $paymentTellers = Payment::selectRaw('teller')
            ->distinct()
            ->orderBy('teller', 'asc')
            ->get();
        $years = Payment::selectRaw('extract(year FROM payment_date) AS year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $invoices = $this->getInvoicePayments();
        $paymentsAmount = $this->getPaymentsAmount();
        return view('livewire.admin.billings.payments.payment-management', compact('invoices', 'paymentTellers', 'years', 'paymentsAmount'));
    }
}
