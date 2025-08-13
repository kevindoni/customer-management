<?php

namespace App\View\Components\CustomerManagement;

use Closure;
use Illuminate\View\Component;
use App\Models\WhatsappGateway\WhatsappBootMessage;
use Illuminate\Contracts\View\View;

class AdminTreeMessage extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $treeMessages = WhatsappBootMessage::whereNull('whatsapp_boot_message_id')
            ->with('childrenMessages')
            ->get();
        return view('components.customer-management.admin-tree-message', compact('treeMessages'));
    }
}
