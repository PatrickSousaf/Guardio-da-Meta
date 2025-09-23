<?php
// app/Models/InviteCode.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'role',
        'max_uses',
        'uses',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function isValid()
    {
        return $this->is_active &&
               $this->uses < $this->max_uses &&
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function incrementUses()
    {
        $this->increment('uses');
    }
}
