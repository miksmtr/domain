<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'link',
        "status", // 0 ise içerikler çekilmemiş. 1 ise çekilmiş // -1 ise çekerken hata var. // 
    ];
}
