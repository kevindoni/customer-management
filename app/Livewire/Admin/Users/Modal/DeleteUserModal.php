<?php

namespace App\Livewire\Admin\Users\Modal;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\AdminAction;
use App\Livewire\Actions\Users\UserAction;
use App\Http\Requests\CurrentPasswordRequest;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class DeleteUserModal extends Component
{

    public $deleteUserModal = false;
    public $userSelect;
    public $input = [];
    /**
     * Delete User function
     * Show delete confirmation modal
     */
    #[On('delete-user-modal')]
    public function showDeleteModal(User $user)
    {
        $this->userSelect = $user;
        $this->deleteUserModal = true;
    }

    /**
     * Deleted user after verifivcation user
     */
    public function delete(CurrentPasswordRequest $request)
    {
        $this->resetErrorBag();
        $userName = $this->userSelect->full_name;
        $request->validate($this->input);
        //if ($request->validate($this->input)) {
            $countAdminSafe = 1;
            $adminCount = User::with('roles')->get()->filter(
                fn ($user) => $user->roles->where('name', 'admin')->toArray()
            )->count();
            if ($adminCount <=  $countAdminSafe && $this->userSelect->hasRole('admin')){
                $title =trans('user.alert.failed');
                $text = trans('user.alert.text-failed-delete-admin-1', ['count' =>  $countAdminSafe]);
                $status = 'error';
            } else {
                if ($this->userSelect->id != Auth::user()->id){
                    (new UserAction())->delete($this->userSelect);
                    $title =trans('user.alert.success');
                    $text = trans('user.alert.success-deleted', ['user' => $userName]);
                    $status = 'success';
                } else {
                    //dd('failed');
                    $title =trans('user.alert.failed');
                    $text = trans('user.alert.text-failed-deleted-self');
                    $status = 'error';
                }
            }

        //}
        $this->notification($title, $text, $status);
        $this->dispatch('refresh-user-list');
        $this->closeDeleteModal();
    }

    public function notification($title, $text, $status)
    {
        LivewireAlert::title($title)
            ->text($text)
            ->position('top-end')
            ->toast()
            ->status($status)
            ->show();
    }

    /**
     * Clode confirmation modal
     */
    #[On('close-modal')]
    public function closeDeleteModal()
    {
        $this->deleteUserModal = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.users.modal.delete-user-modal');
    }
}
