<?php

namespace App\Models;

use Laravel\Cashier\Cashier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['courses'];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function scopeSession()
    {
        return $this->where('session_id', session()->getId());
    }

    public function total()
    {
        return Cashier::formatAmount($this->courses->sum('price'), env('CASHIER_CURRENCY'));
    
    }
}
