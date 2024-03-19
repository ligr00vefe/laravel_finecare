<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGoodsLists extends Model
{
    use HasFactory;
    protected $table = "user_goods_lists";

    public function goods()
    {
        return $this->belongsTo("App\Models\Goods", "goods_id");
    }

    public function user()
    {
        return $this->belongsTo("App\Models\User", "user_id");
    }

}
