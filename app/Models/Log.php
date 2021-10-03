<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'logs';
    public $timestamps = true;
    protected $fillable = [
        'type', // 0 ise başarılı, -1 ise başarısız. 1 uyarı
        'title',
        'description',
        'which_worker' // 
    ];




}
