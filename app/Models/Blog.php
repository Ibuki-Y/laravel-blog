<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model {
    // table名
    protected $table = 'blogs';

    // $fillable: 可変項目
    protected $fillable = [
        'title', 'content'
    ];
}
