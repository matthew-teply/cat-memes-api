<?php

namespace App\V1\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';

    protected $fillable = [
        'image_id',
        'ip',
    ];
}
