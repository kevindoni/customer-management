<?php

namespace App\Models\WhatsappGateway;

use Illuminate\Database\Eloquent\Model;

class WhatsappBootMessage extends Model
{
    protected $guarded = [
        'id',
    ];

    // Each category may have one parent
    public function messages()
    {
        return $this->hasMany(WhatsappBootMessage::class);
    }

    // Each category may have multiple children
    public function childrenMessages()
    {
        return $this->hasMany(WhatsappBootMessage::class)->with('messages');
    }
}
