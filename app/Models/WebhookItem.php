<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id',
        'access_token',
    ];
}
