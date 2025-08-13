<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\Roles\RolesUsageAuthTrait;
use App\Traits\Roles\RolesAndPermissionsHelpersTrait;

class RolesManagement extends Component
{
    use RolesAndPermissionsHelpersTrait, RolesUsageAuthTrait;

    #[On('refresh-role-and-permission-list')]
    public function render()
    {
        $data = $this->getDashboardData();
        //dd($data['view']);
        //  return view('livewire.admin.roles.role-management');
        return view($data['view'], $data['data']);
    }
}
