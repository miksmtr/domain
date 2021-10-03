<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $table = 'domains';
    public $timestamps = true;


    protected $fillable = [
        'name',
        'hosting',
        'status', // 0, sa çalışıyor, 1 se banlandı, 2 se taşındı , 3 se banlandı mail gitti
        'start_time', // ne zaman hayata geçti.
        'finish_time', // ne zaman banlandı. öldü
        'domain_status', // 0 ise sorun yok 1 se taşınması gerekiyor 2 ise taşındı.
        'movable', // 0 ise taşınamaz, 1 ise taşınabilir
        "used" // 0 ise kullanılmadı. 1 ise kullanıldı.
    ];
}
