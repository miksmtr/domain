<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;
    protected $table = 'codes';
    public $timestamps = true;


    protected $fillable = [
        'name',
        'type',
        'limit',
        'description',
        'git_address',
    ];
}
