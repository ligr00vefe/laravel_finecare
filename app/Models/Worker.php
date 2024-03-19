<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class Worker extends Model
{
    use HasFactory;
    protected $table = "helpers";

    public static function getDependents($provider_key)
    {
        $user_id = User::get_user_id();

        return DB::table("workers")
            ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
            ->selectRaw("workers_detail.dependents")
            ->where("workers.user_id", "=", $user_id)
            ->where("workers.target_id", "=", $provider_key)
            ->first()->dependents ?? 0;
    }

    public static function get_user_id()
    {
        return User::where("remember_token", "=", session()->get("user_token"))->first("id")->id;
    }

    public static function getOne($target_id)
    {
        $user_id = self::get_user_id();

        return DB::table("helpers")
            ->selectRaw("helpers.*,
            helper_details.national_ins_check,
            helper_details.health_ins_check,
            helper_details.employ_ins_check,
            helper_details.industry_ins_check,
            helper_details.retire_added_check")
            ->join("helper_details", "helpers.target_key", "=", "helper_details.target_key", "left outer")
            ->where("helpers.user_id", "=", $user_id)
            ->where("helpers.target_key", "=", $target_id)
            ->first();
    }

    public static function getRetirementPay($target_id)
    {
        $user_id = self::get_user_id();

        $provider =  DB::table("helpers")
            ->join("helper_details", function ($join) {
                $join->on("helpers.target_key", "=", "helper_details.target_key")->andOn("helpers.user_id", "=", "helper_details.user_id");
            })
            ->where("helpers.user_id", "=", $user_id)
            ->where("helper_details.target_key", "=", $target_id)
            ->first();

        return $provider->retire_plus_price ?? 0;
    }

    // 활동지원사의 갑근세 금액 가져오기
    public static function getGabgeunse($target_id)
    {
        $user_id = self::get_user_id();

        $provider =  DB::table("helpers")->from("helpers as t1")
            ->leftJoin("helper_details_second", function ($join) {
                $join->on("t1.user_id", "=", "helper_details_second.user_id");
                $join->on("t1.target_key", "=", "helper_details_second.target_id");
            })
            ->where("t1.user_id", "=", $user_id)
            ->where("t1.target_key", "=", $target_id)
            ->first() ?? null;

        $gabgeunse_price = $provider->gabgeunse ?? 0;

        return $gabgeunse_price;
    }

    // $ymd기준 가입일이 1년차 이상이면 true리턴
    public static function first_year_check($key, $ymd)
    {
        $ymd = date("Y-m-d", strtotime($ymd));

        $join_date = DB::table("workers")
            ->where("target_id", "=", $key)
            ->first();

        if (!$join_date) return false;

        $join_date = $join_date->join_date;

        $join_date_one_year_add = strtotime($join_date);
        $ymd = strtotime($ymd . " -1 years");

        // 가입일 <= 검색일-1년 -------> 가입일이 검색일보다 -1년이상이므로 1연차 이상
        return $join_date_one_year_add <= $ymd;
    }

    // 활동지원사의 입사일을 가져온다
    public static function getJoinDate($target_id)
    {
        $join_date = Worker::where("target_id", "=", $target_id)->first();
        if(!$join_date) return false;
        $join_date = $join_date->join_date;
        if ($join_date == "1970-01-01") return false;
        return $join_date;
    }



    public static function pay($page, $request)
    {

        $user_id = User::get_user_id();

        $offset = ((int)$page - 1) * 15;
        $now = date("Y-m-d");

        $term = $request->input("term") ?? "";
        $from_date = $request->input("from_date") ?? "";
        $to_date = $request->input("to_date") ?? "";

        $limit = $request->input("limit") ?? 15;


        $lists = DB::table("workers")
            ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
            ->join("voucher_logs", "workers.target_id", "=", "voucher_logs.provider_key", "left outer")
            ->where(function($query) use($now, $term, $from_date, $to_date) {

                if ($term != "") {
                    $query->whereRaw("workers.name LIKE ?", [ "%{$term}%" ]);
                }

                if ($from_date != "") {
                    $query->whereRaw("voucher_logs.target_ym >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                }

                if ($to_date != "") {
                    $query->whereRaw("voucher_logs.target_ym <= ?", [ date("Y-m-d", strtotime($to_date)) ]);
                }

            })
            ->select(DB::raw("voucher_logs.target_ym, SUM(voucher_logs.confirm_pay) as sum_pay, voucher_logs.provider_key, workers.*"))
            ->where("workers.user_id", "=", $user_id)
            ->orderBy("voucher_logs.target_ym")
            ->groupByRaw("workers.id, voucher_logs.target_ym")
            ->offset($offset)
            ->limit($limit)
            ->get();

        $paging = DB::table("workers")
            ->join("voucher_logs", "workers.target_id", "=", "voucher_logs.provider_key", "left outer")
            ->where(function($query) use($now, $term, $from_date, $to_date) {

                if ($term != "") {
                    $query->whereRaw("workers.name LIKE ?", [ "%{$term}%" ]);
                }

                if ($from_date != "") {
                    $query->whereRaw("voucher_logs.target_ym >= ?", [ date("Y-m-d", strtotime($from_date)) ]);
                }

                if ($to_date != "") {
                    $query->whereRaw("voucher_logs.target_ym <= ?", [ date("Y-m-d", strtotime($to_date)) ]);
                }

            })
            ->select(DB::raw("count(workers.id) as cnt, workers.id"))
            ->where("workers.user_id", "=", $user_id)
            ->groupByRaw("workers.id, voucher_logs.target_ym")
            ->get();


        return [ "lists"=>$lists, "paging"=>count($paging) ];

    }

    public static function isRegNo($regNo)
    {
        $user_id = self::get_user_id();
        $is = DB::table("helpers")->where("regNo", "=", $regNo)->where("user_id", "=", $user_id);
        return $is;
    }

    /*
    helpers, helper_details, helper_details_second 테이블은 add 메서드에서만 수정
    - km 21.06.17
    */
    public static function add($request)
    {
        $user_id = self::get_user_id();

        $is = self::isRegNo($request->input("regNo"));
        if (is_array($is)) {
            return false;
        }

        $transaction = DB::transaction(function () use ($request, $user_id) {
            $target_key = $request->input("name").substr($request->input("rsNo"), 0, 6);

            $helpers_table = [
                "user_id" => $user_id,
                "target_key" => $target_key,
                "name" => $request->input("name"),
                "birth" => $request->input("rsNo"),
                "target_id" => $request->input("target_id"),
                "target_payment_id" => $request->input("target_payment_id"),
                "card_number" => $request->input("card_number"),
                "agency_name" => $request->input("agency_name"),
                "business_number" => $request->input("business_number"),
                "sido" => $request->input("sido"),
                "sigungu" => $request->input("sigungu"),
                "business_division" => $request->input("business_division"),
                "business_types" => $request->input("business_types"),
                "tel" => $request->input("tel"),
                "phone" => $request->input("phone"),
                "address" => $request->input("address"),
                "etc" => $request->input("etc"),
                "contract" => $request->input("contract"),
                "contract_start_date" => $request->input("contract_start_date"),
                "contract_end_date" => $request->input("contract_end_date"),
                "contract_date" => $request->input("contract_date")

            ];

            $helpers_insert = DB::table("helpers")
                ->updateOrInsert(
                    [ "user_id" => $user_id, "target_key" => $target_key  ],
                    $helpers_table // user_id 랑, target_key 제외한 $helpers_table
                );

            if (!$helpers_insert) return false;


            $helper_details_table = [
                "user_id" => $user_id,
                "target_key" => $target_key,
                "register_check" => $request->input("register_check"),
                "name" => $request->input("name"),
                "birth" => $request->input("rsNo"),
                "business_division" => $request->input("business_division01"),
                "business_type" => $request->input("business_type"),
                "payment_price" => $request->input("payment_price"),
                "moment_payment_price" => $request->input("moment_payment_price"),
                "work_time" => $request->input("work_time"),
                "add_basic_pay" => $request->input("add_basic_pay"),
                "add_week_pay" => $request->input("add_week_pay"),
                "add_year_pay" => $request->input("add_year_pay"),
                "etc_pay" => $request->input("etc_pay"),
                "ins_business_assign" => $request->input("ins_business_assign"),
                "retire_plus_price" => $request->input("retire_plus_price"),
                "monthly_payment" => $request->input("monthly_payment"),
                "work_time_day" => $request->input("work_time_day"),
                "time_per_price" => $request->input("time_per_price"),
                "ins_check" => $request->input("ins_check"),
                "national_ins_check" => $request->input("national_ins_check"),
                "health_ins_check" => $request->input("health_ins_check"),
                "employ_ins_check" => $request->input("employ_ins_check"),
                "industry_ins_check" => $request->input("industry_ins_check"),
                "baesang_ins_check" => $request->input("baesang_ins_check"),
                "retire_added_check" => $request->input("retire_added_check"),
                "qualification_status" => $request->input("qualification_status"),
                "target_id" => $request->input("target_id"),
                "business_division_code" => $request->input("business_division_code"),
                "business_type_code" => $request->input("business_type_code")
            ];

            $helper_details_insert = DB::table("helper_details")
                ->updateOrInsert(
                    [ "user_id" => $user_id, "target_key" => $target_key  ],
                    $helper_details_table // user_id 랑, target_key 제외한 $helpers_table
                );

            if (!$helper_details_insert) return false;

            $helper_details_second_table = [
                "user_id" => $user_id,
                "target_id" => $target_key,
                "name" => $request->input("name"),
                "rsNo" => $request->input("rsNo"),
                "regNo" => $request->input("regNo"),
                "regdate" => $request->input("regdate"),
                "join_date" => $request->input("join_date"),
                "resign_date" => $request->input("resign_date"),
                "phone" => $request->input("phone02"),
                "tel" => $request->input("tel02"),
                "address" => $request->input("address02"),
                "bank_name" => $request->input("bank_name"),
                "bank_account_number" => $request->input("bank_account_number"),
                "depositary_stock" => $request->input("depositary_stock"),
                "license_info" => $request->input("license_info"),
                "email" => $request->input("email"),
                "crime_check" => $request->input("crime_check"),
                "national_pension" => $request->input("national_pension"),
                "national_pension_monthly" => $request->input("national_pension_monthly"),
                "health_insurance" => $request->input("health_insurance"),
                "health_insurance_monthly" => $request->input("health_insurance_monthly"),
                "long_term_care_insurance_reduction" => $request->input("long_term_care_insurance_reduction"),
                "employment_insurance" => $request->input("employment_insurance"),
                "employment_insurance_monthly" => $request->input("employment_insurance_monthly"),
                "employment_insurance_after_65age" => $request->input("employment_insurance_after_65age"),
                "industrial_accident_insurance" => $request->input("industrial_accident_insurance"),
                "industrial_accident_insurance_monthly" => $request->input("industrial_accident_insurance_monthly")
            ];

            $helper_details_second_insert = DB::table("helper_details_second")
                ->updateOrInsert(
                    [ "user_id" => $user_id, "target_id" => $target_key  ],
                    $helper_details_second_table // user_id 랑, target_key 제외한 $helpers_table
                );

            if (!$helper_details_second_insert) return false;

            return true;
        });

        return $transaction;


    }


    public static function get($page, $type="all")
    {
        $offset = ((int)$page - 1) * 15;
        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";
        $from_date = $_GET['from_date'] ?? "";
        $to_date = $_GET['to_date'] ?? "";

        $limit = $_GET['limit'] ?? 15;

        $lists = [];
        $paging = [];

        $user_id = self::get_user_id();


        if ($type == "all")
        {

            $lists = DB::table("workers")
                ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
                ->select("workers.*", "workers_detail.gender")
                ->where("workers.user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("workers")
                ->select(DB::raw("count(id) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->orderByDesc("id")
                ->first("cnt");

        }

        else if ($type == "reg")
        {

            $lists = DB::table("workers")
                ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
                ->select("workers.*", "workers_detail.gender")
                ->where("workers.user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereNull("regEndDate")
                ->orWhereRaw("regdate >= regEndDate")
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)->get();


            $paging = DB::table("workers")
                ->select(DB::raw("count(id) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereNull("regEndDate")
                ->orWhereRaw("regdate >= regEndDate")
                ->orderByDesc("id")
                ->first("cnt");

        }

        else if ($type == "use")
        {

            $lists = DB::table("workers")
                ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
                ->select("workers.*", "workers_detail.gender")
                ->where("workers.user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("join_date >= ?", [$now])
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)->get();


            $paging = DB::table("workers")
                ->select(DB::raw("count(id) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("join_date >= ?", [$now])
                ->orderByDesc("id")
                ->first("cnt");

        }

        else if ($type == "termi")
        {

            $lists = DB::table("workers")
                ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
                ->select("workers.*", "workers_detail.gender")
                ->where("workers.user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("resign_date <= ?", [$now])
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)->get();


            $paging = DB::table("workers")
                ->select(DB::raw("count(id) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("resign_date <= ?", [$now])
                ->orderByDesc("id")
                ->first("cnt");

        }

        else if ($type == "cancel")
        {

            $lists = DB::table("workers")
                ->join("workers_detail", "workers.id", "=", "workers_detail.worker_id", "left outer")
                ->select("workers.*", "workers_detail.gender")
                ->where("workers.user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("regEndDate <= ?", [$now])
                ->orderByDesc("id")
                ->offset($offset)
                ->limit($limit)->get();


            $paging = DB::table("workers")
                ->select(DB::raw("count(id) as cnt"))
                ->where("user_id", "=", $user_id)
                ->where(function ($query) use ($term, $from_date, $to_date, $now) {

                    if ($term != "") {
                        $query->where("name", "like", "%{$term}%");
                    }

                    if ($from_date != "") {
                        $query->whereRaw("regdate >= ?", [date("Y-m-d", strtotime($from_date))]);
                    }

                    if ($to_date != "") {
                        $query->whereRaw("regdate <= LAST_DAY(?)", [date("Y-m-d", strtotime($to_date))]);
                    }

                })
                ->whereRaw("regEndDate <= ?", [$now])
                ->orderByDesc("id")
                ->first("cnt");

        }



        return [ "lists" => $lists, "paging" => $paging ];

    }

    /*
     * 활동지원사 일괄 업로드(기본양식)
     * */
    public static function batch($request)
    {
        $user_id = User::get_user_id();

        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];

        // 갱신하기
        if ($request->input("upload_type_basic") == "renew") {
            DB::table("workers_detail")->where("user_id", "=", $user_id)->delete();
            DB::table("workers")->where("user_id", "=", $user_id)->delete();
        }

        $upload = $request->file("basic_excel_upload")->store("upload/docs");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $worksheetData = $reader->listWorksheetInfo($upload);

        foreach ($worksheetData as $key => $worksheet)
        {
            if ($key == 1 ) continue; // 두번째시트는 양식이라서
            $sheetName = $worksheet['worksheetName'];

            $reader->setLoadSheetsOnly($sheetName);
            $spreadsheet = $reader->load($upload);

            $_worksheets = $spreadsheet->getActiveSheet()->toArray();

            unset($_worksheets[0], $_worksheets[1]);

            foreach ($_worksheets as $_key => $val)
            {
                // 이름없으면 넘기기
                if ($val[0] == "") {
                    $result['emptyCnt']++;
                    continue;
                }

                // 신규자료 업데이트 일 때 회원번호 같은사람 있으면 넘기기
                if ($request->input("upload_type_basic") == "new") {
                    $isMember = DB::table("workers")
                        ->where("regNo", "=", $val[2])
                        ->where("user_id", "=", $user_id)->first();
                    if ($isMember) {
                        $result['dupCnt']++;
                        continue;
                    }
                }

                $values = [
                    "user_id"                        => $user_id,
                    "target_id"                     => $val[0].substr($val[1], 0, 6),
                    "name"                           => $val[0],
                    "rsNo"                           => $val[1],
                    "regNo"                          => $val[2],
                    "regdate"                        => $val[3],
                    "join_date"            => $val[4],
                    "resign_date"              => $val[5],
                    "phone"                          => $val[6],
                    "tel"                            => $val[7],
                    "address"                        => $val[8],
                    "bank_name"                          => $val[9],
                    "bank_account_number"                         => $val[10],
                    "depositary_stock"              => $val[11],
                    "license_info" => $val[12],
                    "email"              => $val[13],
                    "crime_check"              => $val[14],
                    "national_pension"                   => $val[15],
                    "national_pension_monthly"     => $val[16],
                    "health_insurance"   => $val[17],
                    "health_insurance_monthly"    => $val[18],
                    "long_term_care_insurance_reduction"           => $val[19],
                    "employment_insurance"                => $val[20],
                    "employment_insurance_monthly"              => $val[21],
                    "employment_insurance_after_65age"            => $val[22],
                    "industrial_accident_insurance"             => $val[23],
                    "industrial_accident_insurance_monthly"               => $val[24],
                ];

                $success = DB::table("workers")->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 이용자 업로드에 실패했습니다";
                }
            }
        }

        return $result;
    }


    public static function getWorkerInfo($data)
    {
        return DB::table("workers")
            ->select("*")
            ->where("user_id", "=", $data['user_id'])
            ->where("target_id", "=", $data['provider_key'])
            ->first();
    }


    // 지원사의 비번일 가져오기
    public static function getOffDay($data)
    {
        return DB::table("helper_confirm_schedules")
            ->select("`date`")
            ->where("user_id", "=", $data['user_id'])
            ->where("worker_id", "=", $data['provider_key'])
            ->where("work_type", "=", "비번")
            ->get();
    }



}
