<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tier',
        'starts_at',
        'ends_at',
        'switch',
        'payment_statuses_id',

    ];
}
