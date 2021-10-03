<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedList extends Model
{
    public $timestamps = true;


    protected $fillable = [
        'domain_id',
        'how_many_times',
        'banned_time'
    ];
}
