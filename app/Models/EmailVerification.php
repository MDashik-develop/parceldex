<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'type',
        'verify_status'
    ];

    public function merchants()
    {
        return $this->belongsTo(Merchant::class, 'user_id', 'id');
    }
}
