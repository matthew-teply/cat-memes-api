<?php

namespace App\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    protected $table = 'images';

    protected $primaryKey = 'id';

    protected $fillable = [
        'image_name',
        'image_category',
        'hits',
    ];
}
