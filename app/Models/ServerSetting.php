<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerSetting extends Model
{
    use HasFactory;
    protected $table = 'server_settings';
    public $timestamps = true;


    protected $fillable = [
        'is_server_busy', // controllerlar bir şey oluşturma aşamasındaysa 1 değilse 0 eğer 1 ise diğer controllerlar bişi oluşturmaya başlamıyor.
        'banned_domain_get_controller', // banlanan domainin yerine yeni domain getiriyor.
        'new_domain_get_controller', // yeni domain eklendiğinde yeni domaini sunucuya kuruyor.
        'check_domain_controller', // domainlerin türkiye tarafından banlanıp banlanmadığını kontrol ediyor.
        'website_picker_busy',
        'website_picker_second_busy',
    ];
}
