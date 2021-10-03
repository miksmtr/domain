<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetCompany extends Model
{
    use HasFactory;

    public $timestamps = true;


    protected $fillable = [
        'name',
        'status',
        'sort',
        'free_bonus',
        'first_deposit',
        'second_deposit',
        'thirth_deposit',
        'casino_bonus',
        'link',
        'rating',
        'btc',
        'credit_card',
        'live_tv',
        'cash_out',
        'papara',
        'havale',
        "type"
    ];
}
