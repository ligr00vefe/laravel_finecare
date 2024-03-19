<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelperDetailsSecond extends Model
{
    use HasFactory;
    protected $table = "helper_details_second";
    protected $guarded = [ "id", "created_at", "updated_at" ];
}
