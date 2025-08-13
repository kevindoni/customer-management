<?php

namespace App\Livewire\Admin\Roles\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use App\Services\Roles\RoleFormFields;
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditRole extends Component
{
    public $editRoleModal = false;
    public $permissions;
    public $role;
    public $form = [];
    public $selectAll;


    #[On('show-edit-role-modal')]
    public function showEditRoleModal(Role $role)
    {
        $this->editRoleModal = true;
        $this->permissions = Permission::all();
        $this->role = $role;

        $service = new RoleFormFields($role->id);
        $data = $service->handle();
        //  dd($data);
        $this->form = collect($this->permissions->whereIn('id', $data['rolePermissionsIds']))->pluck('name')->toArray();
        $this->dispatch('selectGroup', $this->form);
    }



    public function save()
    {
        $this->role->syncPermissions($this->form);
        //Update user permission
        $users = User::role($this->role->name)->get();
        $permissions = $this->role->permissions;
        foreach ($users as $user) {
            $user->syncPermissions($permissions);
        }
        $this->editRoleModal = false;
        LivewireAlert::title(trans('Success'))
            ->text(trans('Success'))
            ->position('center')
            ->success()
            ->show();
        $this->dispatch('refresh-role-and-permission-list');
    }

    public function updatedSelectAll($value)
    {
        // dd('select all');
        if ($value) {
            // Select all options
            $this->form = collect($this->permissions)->pluck('name')->toArray();
            //  dump($this->form);
        } else {
            // Clear selection
            $this->form = [];
        }

        // Dispatch an event to update Choices.js
        $this->dispatch('selectGroup', $this->form);
    }
    public function render()
    {
        return view('livewire.admin.roles.modal.edit-role');
    }
}
