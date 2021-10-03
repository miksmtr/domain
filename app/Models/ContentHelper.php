<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentHelper extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'h',
        "p", 
        "content_id",
        "status"

    ];
}
