<?php

namespace App\Models\Customers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\WhatsappGateway\WhatsappBootMessage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBootMessage extends Model
{
    use SoftDeletes;
    public $guarded = [];

    public function whatsapp_boot_message(): BelongsTo
    {
        return $this->belongsTo(WhatsappBootMessage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
