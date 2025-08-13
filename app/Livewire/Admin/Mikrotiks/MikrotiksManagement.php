<?php

namespace App\Livewire\Admin\Mikrotiks;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Servers\Mikrotik;
use App\Services\Mikrotiks\MikrotikPppService;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class MikrotiksManagement extends Component
{
    use WithPagination;
    //Short by
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $queryString = ['sortField', 'sortDirection'];
    //Search
    public $search_name_or_ip = '';
    public $perPage = 10;
    public $alert_title, $alert_message;

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

    /**
     * Alert when user successfully disable or enable
     */
    #[On('mikrotik-disable')]
    public function alert($model)
    {
        $model = Mikrotik::find($model['id']);
        $pakets = $model->pakets;
        if ($model->disabled) {
            foreach ($pakets as $paket) {
                $paket->update([
                    'disabled' => true
                ]);
            }
            // $this->dispatch('notify', status: 'warning', message: trans('mikrotik.alert.mikrotik-disable', ['mikrotik' => $model->name, 'paket_count' => count($pakets)]));
            $status = 'warning';
            $alert_title = trans('mikrotik.alert.disable-successfully');
            $alert_message = trans('mikrotik.alert.mikrotik-disable', ['mikrotik' => $model->name, 'paket_count' => count($pakets)]);
        } else {
            foreach ($pakets as $paket) {
                $paket->update([
                    'disabled' => false
                ]);
            }
            //  $this->dispatch('notify', status: 'success', message: trans('mikrotik.alert.mikrotik-enable', ['mikrotik' => $model->name, 'paket_count' => count($pakets)]));
            $status = 'success';
            $alert_title = trans('mikrotik.alert.enable-successfully');
            $alert_message = trans('mikrotik.alert.mikrotik-enable', ['mikrotik' => $model->name, 'paket_count' => count($pakets)]);
        }

        $this->notification($alert_title, $alert_message, $status);
    }

    public function notification($title, $message, $status)
    {
        LivewireAlert::title($title)
            ->text($message)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    #[On('refresh-mikrotik-list')]
    public function render()
    {
        $mikrotiks = Mikrotik::when($this->search_name_or_ip, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like',  "%" . $this->search_name_or_ip . "%")
                    ->orWhere('host', 'like', '%' . $this->search_name_or_ip . '%');
            });
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // $mikrotik = $mikrotiks->first();
        // dd($mikrotik->pakets);
        return view('livewire.admin.mikrotiks.mikrotiks-management', [
            'mikrotiks' => $mikrotiks
        ]);
    }
}
