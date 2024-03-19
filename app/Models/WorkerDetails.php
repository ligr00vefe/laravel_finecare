<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerDetails extends Model
{
    use HasFactory;
    protected $table = "helper_details";
    protected $guarded = [ "id", "created_at", "updated_at" ];
}

