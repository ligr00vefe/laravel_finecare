<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBoard extends Model
{
    use HasFactory;

    public static function store($reuqest)
    {
        return true;
    }

    public static function edit($request, $id)
    {

    }
}
