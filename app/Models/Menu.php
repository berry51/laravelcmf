<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'parent_path',
        'name',
        'sequence',
        'icon',
        'router',
        'hidden'
    ];
}
