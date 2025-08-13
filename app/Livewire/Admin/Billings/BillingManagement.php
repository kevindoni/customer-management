<?php

namespace App\Livewire\Admin\Billings;

use DateTime;
use App\Models\User;
use Livewire\Component;
use App\Models\Websystem;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use App\Models\Billings\Payment;
use Illuminate\Support\Facades\DB;
use App\Services\Billings\ExportInvoiceService;
use App\Services\WhatsappGateway\WhatsappNotificationService;

class BillingManagement extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'full_name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];

    // Pagination
    public $perPage = 25;

    //Search
    public $search_name = '';
    public $search_address = '';
    public $search_with_status = 'pending';
    //public $search_with_status = '';
    public $search_with_month = "all-month";
    public $search_with_year = "all-year";
    public $search_with_teller;
    public $startDateDeadline = '';
    public $endDateDeadline;
    public $periodeMonths;
    public $selectedInvoice = [];
    public $checkAll;

    /**
     * Sort by function
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function updatedSearchName()
    {
        $this->resetPage();
    }

    public function updatedSearchAddress()
    {
        $this->resetPage();
    }

    public function updatedSearchWithTeller()
    {
        $this->resetPage();
    }

    public function updatedEndDateDeadline()
    {
        $this->resetPage();
    }

    public function updatedStartDateDeadline()
    {
        $this->resetPage();
    }



    public function bulkSelectedPayment()
    {
        $this->dispatch('bulk-payment-modal', invoiceSelected: $this->selectedInvoice);
    }

    public function bulkDeleteSelected()
    {
        $this->dispatch('bulk-delete-invoice-modal', invoiceSelected: $this->selectedInvoice);
    }
    public function clearSearch()
    {
        $this->startDateDeadline = null;
        $this->endDateDeadline = null;
        $this->search_with_year = "all-year";
        $this->search_with_month = "all-month";
        $this->search_with_status = 'pending';
        // $this->search_with_status = null;
        $this->search_name = null;
        $this->search_address = null;
        $this->search_with_teller = null;
    }

    public function updatedSearchWithStatus($status)
    {
        // $this->resetPage();
        if ($status == "paid") {
            $getMonthPeriode =  $this->getMonthsPeriode(Carbon::now()->format('Y'));
            $this->periodeMonths = $getMonthPeriode->get();
            $lastMonth =  DateTime::createFromFormat('m', $getMonthPeriode->latest('periode')->first()->month);
            $this->search_with_month = Carbon::parse($lastMonth)->format('m');
            $this->search_with_year = Carbon::now()->format('Y');
        } else {
            $this->search_with_month = 'all-month';
            $this->search_with_year = 'all-year';
        }
    }

    public function updatedSearchWithYear($year)
    {
        //$this->resetPage();
        if ($year == 'all-year') {
            $this->search_with_month = 'all-month';
        } else {
            if ($this->search_with_status == "paid") {
                $getMonthPeriode =  $this->getMonthsPeriode($year);
                $lastMonth =  DateTime::createFromFormat('!m', $getMonthPeriode->latest('periode')->first()->month);
                $this->search_with_month = Carbon::parse($lastMonth)->format('m');
            }
        }
    }

    public function updatedCheckAll($checkAll)
    {
        // $this->resetPage();
        if (!$checkAll) {
            $this->selectedInvoice = [];
        }
    }

    public function getMonthsPeriode($year)
    {
        return Invoice::whereYear('periode', $year)->selectRaw('extract(month FROM periode) AS month')
            ->distinct()
            ->orderBy('month', 'desc');
    }

    public function get_users_billing()
    {
        $usersWithBilling = User::select("*", DB::raw("CONCAT(users.first_name,' ',COALESCE(users.last_name, '')) as full_name"))
            ->with('invoices', function ($builder) {
                $builder->when($this->search_with_teller, function ($builder) {
                    $this->resetPage();
                    $builder->with(['payments' => function ($builder) {
                        $builder->where('teller', $this->search_with_teller);
                    }]);
                });
                // $builder->where('invoices.status', '!=', 'paid');
                $builder->when($this->search_with_status, function ($builder) {
                    // $this->resetPage();
                    if ($this->search_with_status == "pending") {
                        $builder->where('invoices.status', '!=', 'paid');
                    } elseif ($this->search_with_status == "paylater") {
                        $builder->whereNotNull('customer_pakets.paylater_date');
                    } else {

                        $builder->where('invoices.status', $this->search_with_status);
                    }
                });

                $builder->when($this->search_with_month, function ($builder) {
                    // $this->resetPage();
                    $builder->where(function ($builder) {
                        if ($this->search_with_month != "all-month") {
                            $builder->whereMonth('periode', $this->search_with_month);
                        }
                    });
                }, function ($builder) {
                    if ($this->search_with_status == "paid") {
                        $this->search_with_month = Carbon::now()->format('m');
                        return $builder->whereMonth('periode', Carbon::now()->format('m'));
                    }
                });

                $builder->when($this->search_with_year, function ($builder) {
                    //$this->resetPage();
                    $builder->where(function ($builder) {
                        if ($this->search_with_year != "all-year") {
                            $builder->whereYear('periode', $this->search_with_year);
                        }
                    });
                }, function ($builder) {
                    if ($this->search_with_status == "paid") {
                        $this->search_with_year = Carbon::now()->format('Y');
                        return $builder->whereYear('periode', $this->search_with_year);
                    }
                });

                $builder->when($this->startDateDeadline, function ($builder) {
                    $this->search_with_month = "all-month";
                    $this->search_with_year = "all-year";
                    if ($this->startDateDeadline && $this->endDateDeadline) {
                        if ($this->search_with_status == "paid") {
                            $builder->whereBetween('paid_at', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                        } else {
                            $builder->whereBetween('due_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                        }
                    } else {
                        if ($this->search_with_status == "paid") {
                            $builder->whereBetween('paid_at', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                        } else {
                            $builder->whereBetween('due_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                        }
                    }
                });
            })

            ->whereHas('invoices', function ($builder) {
                $builder->when($this->search_with_teller, function ($builder) {
                    // $this->resetPage();
                    $builder->with(['payments' => function ($builder) {
                        $builder->where('teller', $this->search_with_teller);
                    }]);
                });

                $builder->when($this->search_with_status, function ($builder) {
                    // $this->resetPage();
                    if ($this->search_with_status == "pending") {
                        $builder->where('invoices.status', '!=', 'paid');
                    } elseif ($this->search_with_status == "paylater") {
                        $builder->whereNotNull('customer_pakets.paylater_date');
                    } else {

                        $builder->where('invoices.status', $this->search_with_status);
                    }
                });

                $builder->when($this->search_with_month, function ($builder) {
                    // $this->resetPage();
                    $builder->where(function ($builder) {
                        if ($this->search_with_month != "all-month") {
                            $builder->whereMonth('periode', $this->search_with_month);
                        }
                    });
                }, function ($builder) {
                    if ($this->search_with_status == "paid") {
                        $this->search_with_month = Carbon::now()->format('m');
                        return $builder->whereMonth('periode', Carbon::now()->format('m'));
                    }
                });

                $builder->when($this->search_with_year, function ($builder) {
                    //$this->resetPage();
                    $builder->where(function ($builder) {
                        if ($this->search_with_year != "all-year") {
                            $builder->whereYear('periode', $this->search_with_year);
                        }
                    });
                }, function ($builder) {
                    if ($this->search_with_status == "paid") {
                        $this->search_with_year = Carbon::now()->format('Y');
                        return $builder->whereYear('periode', $this->search_with_year);
                    }
                });

                $builder->when($this->startDateDeadline, function ($builder) {
                    $this->search_with_month = "all-month";
                    $this->search_with_year = "all-year";
                    if ($this->startDateDeadline && $this->endDateDeadline) {
                        if ($this->search_with_status == "paid") {
                            $builder->whereBetween('paid_at', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                        } else {
                            $builder->whereBetween('due_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::parse($this->endDateDeadline)->endOfDay()]);
                        }
                    } else {
                        if ($this->search_with_status == "paid") {
                            $builder->whereBetween('paid_at', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                        } else {
                            $builder->whereBetween('due_date', [Carbon::parse($this->startDateDeadline)->startOfDay(), Carbon::now()->endOfDay()]);
                        }
                    }
                    // });
                });
            })
            ->whereHas('user_customer')
            ->whereHas('customer_pakets')
            ->when($this->search_name, function ($builder) {
                //  $this->resetPage();
                $builder->where(function ($builder) {
                    $sql = "CONCAT(first_name,' ',COALESCE(last_name,''))  like ?";
                    $builder->whereRaw($sql,  "%" . $this->search_name . "%")
                        ->orWhere('email', 'like', '%' . $this->search_name . '%');
                });
            })
            ->with('user_address')
            ->whereHas('user_address', function ($builder) {
                $builder->when($this->search_address, function ($builder) {
                    //  $this->resetPage();
                    $builder->where('address', 'like', '%' . $this->search_address . '%')
                        ->orWhere('phone', 'like', '%' . $this->search_address . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);
        return $usersWithBilling;
    }


    public function download_customer_invoice(Invoice $invoice, ExportInvoiceService $exportInvoiceService)
    {
        $address = $invoice->customer_paket->user->user_address->address;
        $address = $address === null ? '_' : '_' . $address;
        // $fileName = Str::replace('.', '_', 'invoice_' . $invoice->customer_paket->user->full_name . '_' . Str::replace(' ', $address === null ? '_' : '_', $address));
        $fileName =  Str::slug('invoice_' . $invoice->customer_paket->user->full_name . $address . '_' . $invoice->periode, '_');
        $invoicesFile = $exportInvoiceService->create_invoice_file(
            $invoice,
            $fileName
        );
        $response = $exportInvoiceService->download($invoicesFile);
        if ($response) {
            return $response;
        }
    }

    public function download_customer_invoices(User $user, ExportInvoiceService $exportInvoiceService)
    {
        $address = $user->user_address->address;
        $address = $address === null ? '_' : '_' . $address;
        $fileName =  Str::slug('invoice_' . $user->full_name . $address, '_');

        $invoicesFile = $exportInvoiceService->create_invoices_file(
            collect([$user]),
            $fileName
        );

        $response = $exportInvoiceService->download($invoicesFile);
        if ($response) {
            return $response;
        }
    }

    public function sendNotification(User $user, WhatsappNotificationService $whatsappNotificationService)
    {
        $customerPakets = $user->customer_pakets;
        foreach ($customerPakets as $customerPaket) {
            $lastInvoice = $customerPaket->invoices()->latest()->first();
            $nowDate = new DateTime(Carbon::now()->startOfDay());
            $deadline = new DateTime($lastInvoice->due_date);
            $interval_day = $nowDate->diff($deadline)->format('%a');
            if (Carbon::now()->gt($lastInvoice->due_date) && $interval_day != 0) {
                $interval_day = '+' . $interval_day;
            }
            //  dd($interval_day);
            $whatsappNotificationService->sendUpcomingDueReminders($customerPaket, $interval_day);
        }
    }

    #[On('refresh-billing-paket')]
    public function refreshSelectedCustomerPaket()
    {
        $this->selectedInvoice = [];
        $this->checkAll = false;
    }

    #[On('refresh-billing-paket')]
    public function render()
    {
        $payments = Payment::selectRaw('teller')
            ->distinct()
            ->orderBy('teller', 'asc')
            ->get();
        $years = Invoice::selectRaw('extract(year FROM periode) AS year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();

        $users = $this->get_users_billing();
        $billings = Invoice::get();
        $webSystem = Websystem::first();
        return view('livewire.admin.billings.billing-management', [
            'users' => $users->paginate($this->perPage),
            'billings' => $billings,
            'years' => $years,
            'payments' => $payments,
            'websystem' => $webSystem
        ]);
    }
}
