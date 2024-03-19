<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helpers extends Model
{
    use HasFactory;
    protected $table = "helpers";
    // 무조건 쿼리에 넣어야 되는 것
//    protected $fillable = [ "user_id", "name", "birth", "target_key", "target_id", "target_payment_id", "card_number", "agency_name", "business_number",
//        "sido", "sigungu", "business_division", "business_types", "tel", "phone", "address", "etc", "contract", "contract_start_date", "contract_end_date", "contract_date"
//    ];

    // 쿼리에 안넣어도 자동으로 되는 것.  fillable이나 guarded 둘 중에 하나만 정의
    protected $guarded = [ "id", "created_at", "updated_at" ];
}
