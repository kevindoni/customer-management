<?php

namespace App\Livewire\Admin\Roles\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Role;
use App\Services\Roles\RoleFormFields;
use Spatie\Permission\Models\Permission;
use App\Services\Roles\PermissionFormFields;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class EditPermission extends Component
{
    public $editPermissionModal = false;
    public $roles;
    public $permission;
    public $form = [];
    public $selectAll;


    #[On('show-edit-permission-modal')]
    public function showEditPermissionModal(Permission $permission)
    {
        $this->editPermissionModal = true;
        $this->permission = $permission;
        $this->roles =  Role::all();
        $service = new PermissionFormFields($permission->id);
        $data = $service->handle();
        $this->form = collect($data['permissionItemdData']['item']['roles'])->pluck('name')->toArray();
        $this->dispatch('selectGroup', $this->form);
    }



    public function save()
    {
        $this->permission->syncRoles($this->form);
        //Update user permission
        $roles = Role::whereIn('name', $this->form)->get();
        foreach ($roles as $role) {
            $users = User::role($role->name)->get();
            $permissions = $role->permissions;
            foreach ($users as $user) {
                $user->syncPermissions($permissions);
            }
        }
        $this->editPermissionModal = false;
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
            $this->form = collect($this->roles)->pluck('name')->toArray();
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
        return view('livewire.admin.roles.modal.edit-permission');
    }
}
