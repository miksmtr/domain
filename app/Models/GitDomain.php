<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GitDomain extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'git_id',
        "domain_id",
        "setup",

    ];
}
