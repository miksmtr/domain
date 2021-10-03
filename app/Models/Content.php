<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'first_link',
        "first_title", 
        "first_description", 
        "first_content", 
        "first_category", 
        
        "rewriter_title", 
        "rewriter_description", 
        "rewriter_content",

        "last_link", 
        "last_title", 
        "last_description", 
        "last_content", 
"bet_company_id",
"category_id",

        "website_id",
        "status",  // 0 ise daha rewriter edilmemiş. 1 ise rewriter edilmiş. 2 ise birisi üzerinden geçip düzeltmiş ve hazır.


    ];
}
