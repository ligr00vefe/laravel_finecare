<?php

namespace App\Models;

use App\Classes\Builder;
use App\Classes\Custom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class Member extends Model
{
    use HasFactory;

    protected $table = "members";

    public static $disables = [
        "지체장애" => 0, "뇌병변장애" => 0, "시각장애" => 0, "청각장애" => 0, "언어장애" => 0, "지적장애" => 0, "자폐성장애" => 0,
        "정신장애" => 0, "신장장애" => 0, "성장장애" => 0, "호흡기장애" => 0, "간장애" => 0, "안면장애" => 0, "장루장애 및 유루장애" => 0,
        "간질장애" => 0, "발달장애" => 0, "중복장애" => 0, "미등록" => 0
    ];

    public static $disables_key = [
        "지체장애", "뇌병변장애", "시각장애", "청각장애", "언어장애", "지적장애", "자폐성장애",
        "정신장애", "신장장애", "성장장애", "호흡기장애", "간장애", "안면장애", "장루장애 및 유루장애",
        "간질장애", "발달장애", "중복장애", "미등록"
    ];


    public static function get_user_id()
    {
        return User::where("remember_token", "=", session()->get("user_token"))->first("id")->id;
    }

    public static function getAll($request)
    {
        $user_id = self::get_user_id();

        $member = DB::table("clients")
            ->where("user_id", "=", $user_id)
            ->orderBy("name")
            ->get();

        return ["member" => $member];
    }

    public static function get_service_using($request)
    {

        $term = $request->input("term") ?? false;

        $user_id = User::get_user_id();
        $from_date = date("Y-m-d", strtotime($request->input("from_date")));
        $query = DB::table("voucher_logs")
            ->selectRaw("
                voucher_logs.target_key,
                ANY_VALUE(voucher_logs.id) as id,
                ANY_VALUE(voucher_logs.target_name) as target_name,
                ANY_VALUE(voucher_logs.target_birth) as target_birth,
                ANY_VALUE(voucher_logs.provider_name) as provider_name,
                ANY_VALUE(voucher_logs.provider_birth) as provider_birth,
                ANY_VALUE(voucher_logs.provider_key) as provider_key,
                ANY_VALUE(sum(voucher_logs.payment_time)) as payment_time,
                ANY_VALUE(voucher_logs.service_start_date_time) as service_start_date_time,
                group_concat(date_format(voucher_logs.service_start_date_time, '%Y-%m-%d')) as concat_start_date_time
            ")
            ->join("clients", "clients.target_key", "=", "voucher_logs.target_key")
            ->where("voucher_logs.user_id", "=", $user_id)
            ->where("voucher_logs.target_ym", "=", $from_date)
            ->when($term, function ($query, $term) {
                return $query->where("voucher_logs.target_name", "like", "%{$term}%");
            })
            ->groupBy("voucher_logs.target_key");

        $totalCount = $query->get();
        $paging = count($query->get());
        $logs = $query->orderByRaw("ANY_VALUE(id) DESC")->paginate(15);



        $workers = count(DB::table("voucher_logs")
                ->selectRaw("count(provider_key) as cnt")
                ->where("user_id", "=", $user_id)
                ->where("target_ym", "=", $from_date)
                ->groupBy("provider_key")
                ->get()) ?? 0;

//        $members = count(DB::table("voucher_logs")
//                ->selectRaw("count(target_key) as cnt")
//                ->where("user_id", "=", $user_id)
//                ->where("target_ym", "=", $from_date)
//                ->groupBy("target_key")
//                ->get()) ?? 0;

        $members = count(DB::table("voucher_logs")
            ->selectRaw("
                voucher_logs.target_key, count(*) as cnt
            ")
            ->join("clients", "clients.target_key", "=", "voucher_logs.target_key")
            ->where("voucher_logs.user_id", "=", $user_id)
            ->where("voucher_logs.target_ym", "=", $from_date)
            ->when($term, function ($query, $term) {
                return $query->where("voucher_logs.target_name", "like", "%{$term}%");
            })
            ->groupBy("voucher_logs.target_key")
            ->get());



        $day_total = count(DB::table("voucher_logs")
            ->join("clients", "voucher_logs.target_key", "=", "clients.target_key")
            ->selectRaw("DISTINCT(DATE_FORMAT(voucher_logs.service_start_date_time, '%Y-%m-%d'))")
            ->where("voucher_logs.user_id", "=", $user_id)
            ->where("voucher_logs.target_ym", "=", $from_date)
            ->get());

        $total = [
            "time_total" => 0,
            "day_total" => $day_total,
            "workers" => $workers,
            "members" => $members,
        ];


        $lists = [];


        foreach ($logs as $log) {

            if (!isset($lists[$log->target_key])) {
                $lists[$log->target_key] = [
                    "name" => $log->target_name,
                    "birth" => $log->target_birth,
                    "total_time" => 0,
                    "day_count" => array_unique(explode(",", $log->concat_start_date_time)),
                    "days" => 0,
                    "provider_info" => []
                ];
            }

            $service_start_date_time = date("Y-m-d", strtotime($log->service_start_date_time));

            $lists[$log->target_key]['total_time'] += $log->payment_time;
//            $lists[$log->target_key]['day_count'][$service_start_date_time] = 1;
//            $lists[$log->target_key]['day_count'] = array_unique($lists[$log->target_key]['day_count']);

            if (!isset($lists[$log->target_key]['provider_info'][$log->provider_key])) {
                $lists[$log->target_key]['provider_info'][$log->provider_key] = [
                    "name" => $log->provider_name,
                    "birth" => $log->provider_birth,
                    "total_time" => 0,
                ];
            }

            $lists[$log->target_key]['provider_info'][$log->provider_key]['total_time'] += $log->payment_time;
        }

        foreach ($totalCount as $tt) {
            $total['time_total'] += $tt->payment_time;
        }

        return ["lists" => $lists, "total" => $total, "paging" => $paging];

    }


    public static function get($page, $type = "all")
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

        if ($type == "all") {
            $lists = DB::table("clients")
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
                ->orderBy("name")
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("members")
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
        } else if ($type == "reg") {
            $lists = DB::table("members")
                ->whereNotNull("regdate")
                ->whereNull("regEndDate")
                ->orWhereRaw("regdate >= regEndDate")
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
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("members")
                ->select(DB::raw("count(id) as cnt"))
                ->whereNotNull("regdate")
                ->whereNull("regEndDate")
                ->orWhereRaw("regdate >= regEndDate")
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
        } else if ($type == "use") {

            $lists = DB::table("members")
                ->whereRaw("contract_end_date >= ?", [$now])
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
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("members")
                ->select(DB::raw("count(id) as cnt"))
                ->whereRaw("contract_end_date >= ?", [$now])
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
        } else if ($type == "termi") {
            $lists = DB::table("members")
                ->whereRaw("contract_end_date <= ?", [$now])
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
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("members")
                ->select(DB::raw("count(id) as cnt"))
                ->whereRaw("contract_end_date <= ?", [$now])
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
        } else if ($type == "cancel") {
            $lists = DB::table("members")
                ->whereRaw("regEndDate <= ?", [$now])
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
                ->offset($offset)
                ->limit($limit)->get();

            $paging = DB::table("members")
                ->select(DB::raw("count(id) as cnt"))
                ->whereRaw("regEndDate <= ?", [$now])
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


        return ["lists" => $lists, "paging" => $paging];
    }

    public static function isRegNo($regNo)
    {
        $user_id = self::get_user_id();
        $is = DB::table("members")->where("regNo", "=", $regNo)->where("user_id", "=", $user_id);
        return $is;
    }

    public static function add($request)
    {

        $transaction = DB::transaction(function () use ($request) {
            $user_id = self::get_user_id();

            $builder = new Builder();

            $client = $builder->table("clients")
                ->upsert(
                    [
                        "user_id" => $user_id,
                        "target_key" => $request->input("name") . substr($request->input("rsNo"), 0, 6),
                    ],
                    [
                        "name" => $request->input("name"),
                        "rsNo" => $request->input("rsNo"),
                        "target_id" => $request->input("target_id"),
                        "business_type" => $request->input("business_type"),
                        "grade" => $request->input("grade"),
                        "sigungu_name" => $request->input("sigungu_name"),
                        "service_sigungu_name" => $request->input("service_sigungu_name"),
                        "tel" => $request->input("tel"),
                        "phone" => $request->input("phone"),
                        "contract_status" => $request->input("contract_status"),
                        "contract_start_date" => $request->input("contract_start_date"),
                        "contract_end_date" => $request->input("contract_end_date"),
                        "contract_resign_reason" => $request->input("contract_resign_reason"),
                        "service_status" => $request->input("service_status"),
                        "service_start_date" => $request->input("service_start_date"),
                        "service_end_date" => $request->input("service_end_date"),
                        "service_resign_date" => $request->input("service_resign_date"),
                        "target_helper" => $request->input("target_helper"),
                        "help_price_total" => $request->input("help_price_total"),
                        "government_help_price" => $request->input("government_help_price"),
                        "deductible" => $request->input("deductible"),
                        "childbirth_date" => $request->input("childbirth_date"),
                        "leave_hospital_date" => $request->input("leave_hospital_date"),
                        "zip_code" => $request->input("zip_code"),
                        "service_address" => $request->input("service_address"),
                        "address" => $request->input("address"),
                        "address_detail" => $request->input("address_detail"),
                    ]
                );

            if (!$client) return false;

            $builder2 = new Builder;
            $client_detail = $builder2->table("client_details")
                ->upsert(
                    [
                        "user_id" => $user_id,
                        "client_id" => $client,
                    ],
                    [
                        "name" => $request->input("name"),
                        "rsNo" => $request->input("rsNo"),
                        "target_key" => $request->input("name") . substr($request->input("rsNo"), 0, 6),
                        "client_number" => $request->input("target_id"),
                        "regdate" => $request->input("regdate"),
                        "email" => $request->input("email"),
                        "company" => $request->input("company"),
                        "bogun_time" => $request->input("bogun_time"),
                        "jijache_time" => $request->input("jijache_time"),
                        "etc_time" => $request->input("etc_time"),
                        "other_experience" => $request->input("other_experience"),
                        "income_check" => $request->input("income_check"),
                        "activity_grade" => $request->input("activity_grade"),
                        "activity_grade_old" => $request->input("activity_grade_old"),
                        "activity_grade_type" => $request->input("activity_grade_type"),
                        "income_decision_date" => $request->input("income_decision_date"),
                        "self_charge_price" => $request->input("self_charge_price"),
                        "main_disable_name" => $request->input("main_disable_name"),
                        "main_disable_level" => $request->input("main_disable_level"),
                        "main_disable_grade" => $request->input("main_disable_grade"),
                        "sub_disable_name" => $request->input("sub_disable_name"),
                        "sub_disable_level" => $request->input("sub_disable_level"),
                        "sub_disable_grade" => $request->input("sub_disable_grade"),
                        "disease_name" => $request->input("disease_name"),
                        "drug_info" => $request->input("drug_info"),
                        "wasang_check" => $request->input("wasang_check"),
                        "marriage_check" => $request->input("marriage_check"),
                        "family_info" => $request->input("family_info"),
                        "protector_name" => $request->input("protector_name"),
                        "protector_relation" => $request->input("protector_relation"),
                        "protector_phone" => $request->input("protector_phone"),
                        "protector_tel" => $request->input("protector_tel"),
                        "protector_address" => $request->input("protector_address"),
                        "etc" => $request->input("etc"),
                        "comment" => $request->input("comment"),
                    ]
                );

            return $client_detail;

        });

        return $transaction;

    }


    public static function batch($request)
    {

        $user_id = self::get_user_id();
        $result = ["succCnt" => 0, "errCnt" => 0, "dupCnt" => 0, "errData" => []];

        // 갱신하기
        if ($request->input("upload_type_basic") == "renew") {
            DB::table("members_detail")->where("user_id", "=", $user_id)->delete();
            DB::table("members")->where("user_id", "=", $user_id)->delete();
        }

        $upload = $request->file("basic_excel_upload")->store("upload/docs");

        $reader = IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $worksheetData = $reader->listWorksheetInfo($upload);

        foreach ($worksheetData as $key => $worksheet) {
            if ($key == 1) continue; // 두번째시트는 양식이라서
            $sheetName = $worksheet['worksheetName'];

            $reader->setLoadSheetsOnly($sheetName);
            $spreadsheet = $reader->load($upload);

            $_worksheets = $spreadsheet->getActiveSheet()->toArray();

            unset($_worksheets[0], $_worksheets[1]);

            foreach ($_worksheets as $_key => $val) {
                // 이름없으면 넘기기
                if ($val[0] == "") {
                    continue;
                }

                // 신규자료 업데이트 일 때 주민등록번호 같은사람 있으면 넘기기
                if ($request->input("upload_type_basic") == "new") {
                    $isMember = DB::table("members")
                        ->where("regNo", "=", $val[2])
                        ->where("user_id", "=", $user_id)->first();
                    if ($isMember) {
                        $result['dupCnt']++;
                        continue;
                    }
                }

                $values = [
                    "user_id" => $user_id,
                    "member_id" => $val[0] . substr($val[1], 0, 6),
                    "name" => $val[0],
                    "rsNo" => $val[1],
                    "regNo" => $val[2],
                    "regdate" => $val[3],
                    "contract_start_date" => $val[4],
                    "contract_end_date" => $val[5],
                    "phone" => $val[6],
                    "tel" => $val[7],
                    "address" => $val[8],
                    "email" => $val[9],
                    "office" => $val[10],
                    "MHW_decision_time" => $val[11],
                    "local_government_decision_time" => $val[12],
                    "etc_decision_time" => $val[13],
                    "other_organiz_exp" => $val[14],
                    "income_check" => $val[15],
                    "activity_support_grade_new" => $val[16],
                    "activity_support_grade_exist" => $val[17],
                    "activity_support_grade_type" => $val[18],
                    "income_decision_date" => $val[19],
                    "personal_charge" => $val[20],
                    "main_disable_name" => $val[21],
                    "main_disable_degree" => $val[22],
                    "main_disable_grade" => $val[23],
                    "sub_disable_name" => $val[24],
                    "sub_disable_degree" => $val[25],
                    "sub_disable_grade" => $val[26],
                    "disease_name" => $val[27],
                    "dosing_info" => $val[28],
                    "bed_disable_check" => $val[29],
                    "marriage_check" => $val[30],
                    "family_details" => $val[31],
                    "guardian_name" => $val[32],
                    "guardian_relationship" => $val[33],
                    "guardian_phone" => $val[34],
                    "guardian_tel" => $val[35],
                    "guardian_address" => $val[36],
                    "note" => $val[37],
                    "overall" => $val[38]
                ];

                $success = DB::table("members")->insert($values);
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

    public static function averageMng($type)
    {

        $user_id = self::get_user_id();
        $get2 = DB::select(sprintf('SELECT t1.main_disable_name,t1.main_disable_grade,t2.payment_time FROM client_details AS t1 JOIN voucher_logs AS t2 ON t1.target_key = t2.target_key WHERE t1.user_id = %s', $user_id));

        $lists2 = [];
        $columns2 = [
            "지체장애", "뇌병변장애", "시각장애", "청각장애", "언어장애", "지적장애", "자폐성장애",
            "정신장애", "신장장애", "성장장애", "호흡기장애", "간장애", "안면장애", "장루장애 및 유루장애",
            "간질장애", "발달장애", "중복장애", "미등록"
        ];
        foreach ($columns2 as $i => $column) {
            $lists2[$i] = [0, 0, 0, 0, 0, 0, 0, 0];
        }


        foreach ($get2 as $key => $val) {
            if ($val->payment_time == "")
                continue;

            switch ($val->main_disable_name) {
                case "지체":
                case "지체장애":
                    $i = 0;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "뇌병변":
                case "뇌병변장애":
                    $i = 1;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "시각":
                case "시각장애":
                    $i = 2;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "청각":
                case "청각장애":
                    $i = 3;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "언어":
                case "언어장애":
                    $i = 4;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "지적":
                case "지적장애":
                    $i = 5;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "자폐성":
                case "자폐성장애":
                    $i = 6;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "정신":
                case "정신장애":
                    $i = 7;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "신장":
                case "신장장애":
                    $i = 8;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "성장":
                case "성장장애":
                    $i = 9;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "호흡기":
                case "호흡기장애":
                    $i = 10;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "간":
                case "간장애":
                    $i = 11;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "안면":
                case "안면장애":
                    $i = 12;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "장루":
                case "장루장애 및 요루장애":
                    $i = 13;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "간질":
                case "간질장애":
                    $i = 14;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "발달":
                case "발달장애":
                    $i = 15;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "중복":
                case "중복장애":
                    $i = 16;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                case "미등록":
                    $i = 17;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
                default:
                    $i = 17;
                    if ($val->main_disable_grade == "1등급") $lists2[$i][0] += $val->payment_time;
                    if ($val->main_disable_grade == "2등급") $lists2[$i][1] += $val->payment_time;
                    if ($val->main_disable_grade == "3등급") $lists2[$i][2] += $val->payment_time;
                    if ($val->main_disable_grade == "4등급") $lists2[$i][3] += $val->payment_time;
                    if ($val->main_disable_grade == "5등급") $lists2[$i][4] += $val->payment_time;
                    if ($val->main_disable_grade == "6등급") $lists2[$i][5] += $val->payment_time;
                    if ($val->main_disable_grade == "미등록") $lists2[$i][6] += $val->payment_time;
                    if ($val->main_disable_grade == "기타") $lists2[$i][7] += $val->payment_time;
                    break;
            }

        }
        return $lists2;
    }

    public static function sortMng($type)
    {
        $user_id = self::get_user_id();

        $get = DB::table("client_details")
            ->select(DB::raw("main_disable_name, main_disable_grade, count(id) as cnt"))
            ->where("user_id", "=", $user_id)
            ->whereNotNull("main_disable_name")
            ->groupByRaw("main_disable_name, main_disable_grade")
            ->get();

        $lists = [];

        $columns = [
            "지체장애", "뇌병변장애", "시각장애", "청각장애", "언어장애", "지적장애", "자폐성장애",
            "정신장애", "신장장애", "성장장애", "호흡기장애", "간장애", "안면장애", "장루장애 및 유루장애",
            "간질장애", "발달장애", "중복장애", "미등록"
        ];

        foreach ($columns as $i => $column) {
            $lists[$i] = [0, 0, 0, 0, 0, 0, 0, 0];
        }

        foreach ($get as $key => $val) {
            switch ($val->main_disable_name) {
                case "지체":
                case "지체장애":
                    $i = 0;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "뇌병변":
                case "뇌병변장애":
                    $i = 1;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "시각":
                case "시각장애":
                    $i = 2;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "청각":
                case "청각장애":
                    $i = 3;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "언어":
                case "언어장애":
                    $i = 4;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "지적":
                case "지적장애":
                    $i = 5;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "자폐성":
                case "자폐성장애":
                    $i = 6;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "정신":
                case "정신장애":
                    $i = 7;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "신장":
                case "신장장애":
                    $i = 8;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "성장":
                case "성장장애":
                    $i = 9;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "호흡기":
                case "호흡기장애":
                    $i = 10;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "간":
                case "간장애":
                    $i = 11;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "안면":
                case "안면장애":
                    $i = 12;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "장루":
                case "장루장애 및 요루장애":
                    $i = 13;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "간질":
                case "간질장애":
                    $i = 14;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "발달":
                case "발달장애":
                    $i = 15;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "중복":
                case "중복장애":
                    $i = 16;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                case "미등록":
                    $i = 17;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
                default:
                    $i = 17;
                    if ($val->main_disable_grade == "1등급") $lists[$i][0] = $val->cnt;
                    if ($val->main_disable_grade == "2등급") $lists[$i][1] = $val->cnt;
                    if ($val->main_disable_grade == "3등급") $lists[$i][2] = $val->cnt;
                    if ($val->main_disable_grade == "4등급") $lists[$i][3] = $val->cnt;
                    if ($val->main_disable_grade == "5등급") $lists[$i][4] = $val->cnt;
                    if ($val->main_disable_grade == "6등급") $lists[$i][5] = $val->cnt;
                    if ($val->main_disable_grade == "미등록") $lists[$i][6] = $val->cnt;
                    if ($val->main_disable_grade == "기타") $lists[$i][7] = $val->cnt;
                    break;
            }
        }


        return $lists;
    }


    public static function sortMna($type)
    {
        $user_id = User::get_user_id();
        $disables = self::$disables;

        $averageAges = $ages = [
            "0-3" => $disables,
            "4-6" => $disables,
            "7-12" => $disables,
            "13-18" => $disables,
            "19-30" => $disables,
            "31-40" => $disables,
            "41-50" => $disables,
            "51-64" => $disables,
            "65-300" => $disables,
        ];

        $members = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->where("clients.user_id", "=", $user_id)
            ->get();

        foreach ($members as $key => $member) {

            // 주민번호로 나이 구하기
            $rsNoCheck = substr($member->rsNo, 7, 1) ?: 1;
            $century = 19;

            // 평균 구하기
            $averageTimes = DB::table("voucher_logs")
                ->select("payment_time")
                ->where("target_key", "=", $member->target_key)
                ->get();

            $averageTime = 0;
            foreach ($averageTimes as $average_key => $time) {
                if (is_numeric($time->payment_time)) $averageTime += $time->payment_time;
            }


            if ($rsNoCheck == 3 || $rsNoCheck == 4) $century = 20;
            $birth = date("Y-m-d", strtotime($century . substr($member->rsNo, 0, 6)));
            $age = Custom::getAge($birth, 2);

            foreach ($ages as $i => $count) {
                if (!$member->main_disable_name) continue;
                $range = explode("-", $i);
                if ($range[1] < $age) continue;
                if ($range[0] <= $age && $range[1] >= $age) {
                    $ages[$i][$member->main_disable_name]++;
                    $averageAges[$i][$member->main_disable_name] = $averageTime;
                }
            }
        }
        $averageTotalDisables = $totalDisables = $disables;

        $averageTotalAges = $totalAges = [
            "0-3" => 0,
            "4-6" => 0,
            "7-12" => 0,
            "13-18" => 0,
            "19-30" => 0,
            "31-40" => 0,
            "41-50" => 0,
            "51-64" => 0,
            "65-300" => 0,
        ];

        foreach ($ages as $key => $disables) {
            foreach ($disables as $disableName => $disable) {
                $totalDisables[$disableName] += $disable;
                $totalAges[$key] += $disable;
            }
        }

        foreach ($averageAges as $key => $disables) {
            foreach ($disables as $disableName => $disable) {
                $averageTotalDisables[$disableName] += $disable;
                $averageTotalAges[$key] += $disable;
            }
        }

        return [
            "ages" => $ages,
            "totalType1" => $totalDisables,
            "totalType2" => $totalAges,
            "averageAges" => $averageAges,
            "averageTotalType1" => $averageTotalDisables,
            "averageTotalType2" => $averageTotalAges
        ];

    }

    public static function sortGna()
    {
        $user_id = User::get_user_id();

        $genders = [
            "여성" => 0,
            "남성" => 0,
            "-" => 0,
        ];

        $averageAges = $ages = [
            "0-3" => $genders,
            "4-6" => $genders,
            "7-12" => $genders,
            "13-18" => $genders,
            "19-30" => $genders,
            "31-40" => $genders,
            "41-50" => $genders,
            "51-64" => $genders,
            "65-300" => $genders,
        ];

        $members = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->where("clients.user_id", "=", $user_id)
            ->get();


        foreach ($members as $member) {
            // 주민번호로 나이 구하기
            $rsNoCheck = substr($member->rsNo, 7, 1) ?: 1;
            $century = 19;
            if ($rsNoCheck == 3 || $rsNoCheck == 4) $century = 20;
            $birth = date("Y-m-d", strtotime($century . substr($member->rsNo, 0, 6)));
            $age = Custom::getAge($birth, 2);

            // 평균 구하기
            $averageTimes = DB::table("voucher_logs")
                ->select("payment_time")
                ->where("target_key", "=", $member->target_key)
                ->get();

            $averageTime = 0;
            foreach ($averageTimes as $average_key => $time) {
                if (is_numeric($time->payment_time)) $averageTime += $time->payment_time;
            }

            foreach ($ages as $i => $count) {
                $range = explode("-", $i);
                if ($range[1] < $age) continue;
                if ($range[0] <= $age && $range[1] >= $age) {
                    if ($rsNoCheck == 1 || $rsNoCheck == 3) {
                        $ages[$i]["남성"]++;
                        $averageAges[$i]["남성"] = $averageTime;
                    } else if ($rsNoCheck == 2 || $rsNoCheck == 4) {
                        $ages[$i]["여성"]++;
                        $averageAges[$i]["여성"] = $averageTime;
                    } else {
                        $ages[$i]["-"]++;
                        $averageAges[$i]["-"] = 0;
                    }
                }
            }
        }


        $averageTotalAges = $totalAges = [
            "0-3" => 0,
            "4-6" => 0,
            "7-12" => 0,
            "13-18" => 0,
            "19-30" => 0,
            "31-40" => 0,
            "41-50" => 0,
            "51-64" => 0,
            "65-300" => 0,
        ];

        foreach ($ages as $key => $genders) {
            foreach ($genders as $gendersName => $type) {
                $totalAges[$key] += $type;
            }
        }

        foreach ($averageAges as $key => $genders) {
            foreach ($genders as $gendersName => $type) {
                $averageTotalAges[$key] += $type;
            }
        }

        return [
            "ages" => $ages,
            "totalType1" => $totalAges,
            "averageAges" => $averageAges,
            "averageTotalType1" => $averageTotalAges
        ];
    }

    public static function sortLna()
    {
        $user_id = User::get_user_id();

        $averageLifetypes = $lifetypes = [
            "기초생활수급자" => 0,
            "차상위계층" => 0,
            "일반" => 0,
            "-" => 0,
        ];

        $averageAges = $ages = [
            "0-3" => $lifetypes,
            "4-6" => $lifetypes,
            "7-12" => $lifetypes,
            "13-18" => $lifetypes,
            "19-30" => $lifetypes,
            "31-40" => $lifetypes,
            "41-50" => $lifetypes,
            "51-64" => $lifetypes,
            "65-300" => $lifetypes,
        ];

        $members = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->where("clients.user_id", "=", $user_id)
            ->get();

        foreach ($members as $member) {
            // 주민번호로 나이 구하기
            $rsNoCheck = substr($member->rsNo, 7, 1) ?: 1;
            $century = 19;
            if ($rsNoCheck == 3 || $rsNoCheck == 4) $century = 20;
            $birth = date("Y-m-d", strtotime($century . substr($member->rsNo, 0, 6)));
            $age = Custom::getAge($birth, 2);

            // 평균 구하기
            $averageTimes = DB::table("voucher_logs")
                ->select("payment_time")
                ->where("target_key", "=", $member->target_key)
                ->get();

            $averageTime = 0;
            foreach ($averageTimes as $average_key => $time) {
                if (is_numeric($time->payment_time)) $averageTime += $time->payment_time;
            }

            foreach ($ages as $i => $count) {
                $range = explode("-", $i);
                if ($range[1] < $age) continue;
                if ($range[0] <= $age && $range[1] >= $age) {

                    switch ($member->income_check) {
                        case "기초생활수급자":
                            $ages[$i][$member->income_check]++;
                            $averageAges[$i][$member->income_check] += $averageTime;
                            break;
                        case "차상위계층":
                            $ages[$i][$member->income_check]++;
                            $averageAges[$i][$member->income_check] += $averageTime;
                            break;
                        case "일반":
                            $ages[$i][$member->income_check]++;
                            $averageAges[$i][$member->income_check] += $averageTime;
                            break;
                        default:
                            $ages[$i]["-"]++;
                            $averageAges[$i]["-"] += $averageTime;
                    }

                }
            }
        }

        $averageTotalAges = $totalAges = [
            "0-3" => 0,
            "4-6" => 0,
            "7-12" => 0,
            "13-18" => 0,
            "19-30" => 0,
            "31-40" => 0,
            "41-50" => 0,
            "51-64" => 0,
            "65-300" => 0,
        ];

        foreach ($ages as $key => $types) {
            foreach ($types as $typeName => $type) {
                $totalAges[$key] += $type;
            }
        }

        foreach ($averageAges as $key => $types) {
            foreach ($types as $typeName => $type) {
                $averageTotalAges[$key] += $type;
            }
        }

        return [
            "ages" => $ages,
            "totalType1" => $totalAges,
            "averageAges" => $averageAges,
            "averageTotalType1" => $averageTotalAges
        ];
    }

    public static function averageSortSnt()
    {
        $user_id = User::get_user_id();

        $grades = [
            "1등급" => ["people" => 0, "time" => 0,],
            "2등급" => ["people" => 0, "time" => 0,],
            "3등급" => ["people" => 0, "time" => 0,],
            "4등급" => ["people" => 0, "time" => 0,],
            "5등급" => ["people" => 0, "time" => 0,],
            "6등급" => ["people" => 0, "time" => 0,],
            "7등급" => ["people" => 0, "time" => 0,],
            "8등급" => ["people" => 0, "time" => 0,],
            "9등급" => ["people" => 0, "time" => 0,],
            "10등급" => ["people" => 0, "time" => 0,],
            "11등급" => ["people" => 0, "time" => 0,],
            "12등급" => ["people" => 0, "time" => 0,],
            "13등급" => ["people" => 0, "time" => 0,],
            "14등급" => ["people" => 0, "time" => 0,],
            "15등급" => ["people" => 0, "time" => 0,],
            "-" => ["people" => 0, "time" => 0,]
        ];

        $total = [
            "1등급" => ["people" => 0, "time" => 0],
            "2등급" => ["people" => 0, "time" => 0],
            "3등급" => ["people" => 0, "time" => 0],
            "4등급" => ["people" => 0, "time" => 0],
            "5등급" => ["people" => 0, "time" => 0],
            "6등급" => ["people" => 0, "time" => 0],
            "7등급" => ["people" => 0, "time" => 0],
            "8등급" => ["people" => 0, "time" => 0],
            "9등급" => ["people" => 0, "time" => 0],
            "10등급" => ["people" => 0, "time" => 0],
            "11등급" => ["people" => 0, "time" => 0],
            "12등급" => ["people" => 0, "time" => 0],
            "13등급" => ["people" => 0, "time" => 0],
            "14등급" => ["people" => 0, "time" => 0],
            "15등급" => ["people" => 0, "time" => 0],
            "-" => ["people" => 0, "time" => 0],
            "가" => ["people" => 0, "time" => 0],
            "나" => ["people" => 0, "time" => 0],
            "다" => ["people" => 0, "time" => 0],
            "라" => ["people" => 0, "time" => 0],
            "마" => ["people" => 0, "time" => 0],
            "바" => ["people" => 0, "time" => 0],
            "미등록" => ["people" => 0, "time" => 0],
            "소계토탈" => ["people" => 0, "time" => 0]
        ];

        function show($msg)
        {
            echo "<pre>";
            print_r($msg);
            echo "</pre>";
        }

        $types = [
            "가" => $grades,
            "나" => $grades,
            "다" => $grades,
            "라 " => $grades,
            "마" => $grades,
            "바" => $grades,
            "미등록" => $grades,
            "소계" => $total,
        ];

        $members = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->where("clients.user_id", "=", $user_id)
            ->get();


        foreach ($members as $member) {
            if (!$member->activity_grade) continue;
            if (!$member->activity_grade_type) $member->activity_grade_type = "-";

            // 평균 구하기
            $averageTimes = DB::table("voucher_logs")
                ->select("payment_time")
                ->where("target_key", "=", $member->target_key)
                ->get();

            $averageTime = 0;
            foreach ($averageTimes as $average_key => $time) {
                if (is_numeric($time->payment_time)) $averageTime += $time->payment_time;
            }

            switch ($member->activity_grade_type) {
                case "가":
                case "나":
                case "다":
                case "라":
                case "마":
                case "바":
                    $types[$member->activity_grade_type][$member->activity_grade]['people'] += $averageTime;
                    $types['소계'][$member->activity_grade_type]["people"] += $averageTime;
                    $types['소계'][$member->activity_grade]["people"] += $averageTime;

                    break;
                default:
                    $types['소계']["미등록"]["people"] += $averageTime;

                    if (!isset($types["미등록"][$member->activity_grade])) break;
                    if (!isset($types["소계"][$member->activity_grade])) break;

                    $types["미등록"][$member->activity_grade]['people'] += $averageTime;
                    $types['소계'][$member->activity_grade]["people"] += $averageTime;
                    break;
            }
        }
        return $types;
    }


    public static function sortSnt()
    {
        $user_id = User::get_user_id();

        $grades = [
            "1등급" => ["people" => 0, "time" => 0,],
            "2등급" => ["people" => 0, "time" => 0,],
            "3등급" => ["people" => 0, "time" => 0,],
            "4등급" => ["people" => 0, "time" => 0,],
            "5등급" => ["people" => 0, "time" => 0,],
            "6등급" => ["people" => 0, "time" => 0,],
            "7등급" => ["people" => 0, "time" => 0,],
            "8등급" => ["people" => 0, "time" => 0,],
            "9등급" => ["people" => 0, "time" => 0,],
            "10등급" => ["people" => 0, "time" => 0,],
            "11등급" => ["people" => 0, "time" => 0,],
            "12등급" => ["people" => 0, "time" => 0,],
            "13등급" => ["people" => 0, "time" => 0,],
            "14등급" => ["people" => 0, "time" => 0,],
            "15등급" => ["people" => 0, "time" => 0,],
            "-" => ["people" => 0, "time" => 0,]
        ];

        $total = [
            "1등급" => ["people" => 0, "time" => 0],
            "2등급" => ["people" => 0, "time" => 0],
            "3등급" => ["people" => 0, "time" => 0],
            "4등급" => ["people" => 0, "time" => 0],
            "5등급" => ["people" => 0, "time" => 0],
            "6등급" => ["people" => 0, "time" => 0],
            "7등급" => ["people" => 0, "time" => 0],
            "8등급" => ["people" => 0, "time" => 0],
            "9등급" => ["people" => 0, "time" => 0],
            "10등급" => ["people" => 0, "time" => 0],
            "11등급" => ["people" => 0, "time" => 0],
            "12등급" => ["people" => 0, "time" => 0],
            "13등급" => ["people" => 0, "time" => 0],
            "14등급" => ["people" => 0, "time" => 0],
            "15등급" => ["people" => 0, "time" => 0],
            "-" => ["people" => 0, "time" => 0],
            "가" => ["people" => 0, "time" => 0],
            "나" => ["people" => 0, "time" => 0],
            "다" => ["people" => 0, "time" => 0],
            "라" => ["people" => 0, "time" => 0],
            "마" => ["people" => 0, "time" => 0],
            "바" => ["people" => 0, "time" => 0],
            "미등록" => ["people" => 0, "time" => 0],
            "소계토탈" => ["people" => 0, "time" => 0]
        ];

        $types = [
            "가" => $grades,
            "나" => $grades,
            "다" => $grades,
            "라 " => $grades,
            "마" => $grades,
            "바" => $grades,
            "미등록" => $grades,
            "소계" => $total,
        ];

        $members = DB::table("clients")
            ->join("client_details", "clients.target_key", "=", "client_details.target_key", "left outer")
            ->where("clients.user_id", "=", $user_id)
            ->get();

        foreach ($members as $member) {
            if (!$member->activity_grade) continue;
            if (!$member->activity_grade_type) $member->activity_grade_type = "-";

            switch ($member->activity_grade_type) {
                case "가":
                case "나":
                case "다":
                case "라":
                case "마":
                case "바":
                    $types[$member->activity_grade_type][$member->activity_grade]['people']++;
                    $types['소계'][$member->activity_grade_type]["people"]++;
                    $types['소계'][$member->activity_grade]["people"]++;
                    break;
                default:
                    $types['소계']["미등록"]["people"]++;

                    if (!isset($types["미등록"][$member->activity_grade])) break;
                    if (!isset($types["소계"][$member->activity_grade])) break;

                    $types["미등록"][$member->activity_grade]['people']++;
                    $types['소계'][$member->activity_grade]["people"]++;
                    break;
            }
        }

        return $types;
    }


    public static function sort($type)
    {

        switch ($type) {
            case "mng":
                return self::sortMng($type);
                break;
            case "mna":
                return self::sortMna($type);
                break;
            case "gna" :
                return self::sortGna();
                break;
            case "lna" :
                return self::sortLna();
                break;
            case "snt":
                return self::sortSnt();
                break;

        }

    }

    public static function averageSort($type)
    {

        switch ($type) {
            case "mng":
                return self::averageMng($type);
                break;
            case "snt":
                return self::averageSortSnt();
                break;
        }

    }


    public static function get_all_users()
    {
        return DB::table("members")->get();

    }

    public static function list_excel_download()
    {

        $cells = array(
            'A' => array(25, "name", '이름 (필수)'),
            'B' => array(25, "rsNo", '주민등록번호 (필수)'),
            'C' => array(25, "regNo", '이용자 관리번호'),
            'D' => array(25, "regdate", '접수일 (필수)'),
            'E' => array(25, "contract_start_date", '계약시작일자'),
            'F' => array(25, "contract_end_date", '계약종료일자'),
            'G' => array(25, "phone", '휴대전화번호'),
            'H' => array(25, "tel", '자택전화번호'),
            'I' => array(50, "address", '주소'),
            'J' => array(25, "email", '이메일'),
            'K' => array(25, "office", '직장'),
            'L' => array(25, "MHW_decision_time", '보건복지부 판정시간'),
            'M' => array(25, "local_government_decision_time", '지자체추가 판정시간'),
            'N' => array(25, "etc_decision_time", '기타 판정시간'),
            'O' => array(50, "other_organiz_exp", '타기관 이용경험'),
            'P' => array(25, "income_check", '수급여부'),
            'Q' => array(25, "activity_support_grade_new", '활동지원등급 (신규)'),
            'R' => array(25, "activity_support_grade_exist", '활동지원등급 (기존)'),
            'S' => array(25, "activity_support_grade_type", '활동지원등급유형'),
            'T' => array(25, "income_decision_date", '수급결정시기'),
            'U' => array(25, "personal_charge", '본인부담금'),
            'V' => array(25, "main_disable_name", '주장애명'),
            'W' => array(25, "main_disable_degree", '주장애정도'),
            'X' => array(25, "main_disable_grade", '주장애등급'),
            'Y' => array(25, "sub_disable_name", '부장애명'),
            'Z' => array(25, "sub_disable_degree", '부장애정도'),
            'AA' => array(25, "sub_disable_grade", '부장애등급'),
            'AB' => array(25, "disease_name", '보유질환명'),
            'AC' => array(25, "dosing_info", '투약정보'),
            'AD' => array(25, "bed_disable_check", '와상장애여부'),
            'AE' => array(25, "marriage_check", '결혼여부'),
            'AF' => array(25, "family_details", '가족사항'),
            'AG' => array(25, "guardian_name", '보호자명'),
            'AH' => array(25, "guardian_relationship", '보호자관계'),
            'AI' => array(25, "guardian_phone", '보호자휴대전화번호'),
            'AJ' => array(25, "guardian_tel", '보호자자택전화번호'),
            'AK' => array(50, "guardian_address", '보호자주소'),
            'AL' => array(50, "note", '특이사항'),
            'AM' => array(50, "overall", '종합소견'),
        );
        $cells2 = array(
            'A' => array(0, "name", '이용자의 이름을 입력해주세요. 최대 20자 입력가능합니다.
예> 홍길동2', 'FDE9D9'),
            'B' => array(0, "rsNo", '이용자의 주민등록번호를 입력해주세요.
예> 550815-1234567', 'FDE9D9'),
            'C' => array(0, "regNo", '기관에서 관리하는 번호를 입력해주세요. 최대 10자 입력가능합니다.
            예> 2016-00001'),
            'D' => array(0, "regdate", '접수한 날짜를 입력해주세요. 접수일만 입력한 경우 접수상태로 등록됩니다.
예> 20160101', 'FDE9D9'),
            'E' => array(0, "contract_start_date", '계약한 날짜를 입력해주세요. 입력시 이용중 상태로 등록됩니다.
예> 20160125'),
            'F' => array(0, "contract_end_date", '계약을 해지한 날짜를 입력해주세요. 입력 시 해지상태로 등록됩니다. 단, 계약시작일자도 등록하셔야 합니다.
예> 20161231'),
            'G' => array(0, "phone", '이용자의 휴대전화번호를 입력해주세요. 최대 20자 입력가능합니다.
예> 010-1234-5678'),
            'H' => array(0, "tel", '이용자의 자택전화번호를 입력해주세요. 최대 20자 입력가능합니다.
예> 02-567-1234'),
            'I' => array(0, "address", '이용자의 주소를 입력해주세요. 최대 200자 입력 가능합니다.'),
            'J' => array(0, "email", '이용자의 이메일주소를 입력해주세요. 최대 100자 입력 가능합니다.'),
            'K' => array(0, "office", '이용자의 직장을 입력해주세요. 최대 200자 입력 가능합니다.'),
            'L' => array(0, "MHW_decision_time", '이용자의 보건복지부 판정시간을 입력해주세요. 숫자만 최대 999까지 입력이 가능합니다.
예> 391'),
            'M' => array(0, "local_government_decision_time", '이용자의 지자체추가 판정시간을 입력해주세요. 숫자만 최대 999까지 입력이 가능합니다.
예> 107'),
            'N' => array(0, "etc_decision_time", '이용자의 기타추가 판정시간을 입력해주세요. 숫자만 최대 999까지  입력이 가능합니다.
예> 10'),
            'O' => array(0, "other_organiz_exp", '이용자의 타기관 활동보조서비스 이용경험,  타 복지서비스(바우처 등) 이용경험을 입력해주세요.'),
            'P' => array(0, "income_check", '이용자의 수급여부를 입력해주세요. 반드시 [항목값 참조 시트의 수급여부]를 참조해서 입력하여야 합니다.'),
            'Q' => array(0, "activity_support_grade_new", '이용자의 활동지원등급 (신규 / 1~15등급)을 입력해주세요. 반드시 [항목값 참조 시트의 활동지원등급]을 참조해서 입력하여야 합니다.
예> 1등급'),
            'R' => array(0, "activity_support_grade_exist", '이용자의 활동지원등급 (기존 / 1~4등급)을 입력해주세요. 반드시 [항목값 참조 시트의 활동지원등급]을 참조해서 입력하여야 합니다.
예> 1등급'),
            'S' => array(0, "activity_support_grade_type", '활동지원등급 유형을 입력해주세요. 반드시 [항목값 참조 시트의 활동지원등급]을 참조해서 입력하여야 합니다.
예> 가 '),
            'T' => array(0, "income_decision_date", '이용자의 수급결정시기를 입력해주세요. 최대 20자 입력 가능합니다.
예> 2016년'),
            'U' => array(0, "personal_charge", '이용자의 본인부담금을 입력해주세요. 숫자만 최대 10자리 입력이 가능합니다.
예> 108900'),
            'V' => array(0, "main_disable_name", '이용자의 주장애명을 입력해주세요. 반드시 [항목값 참조 시트의 장애명 종류]를 참조해서 입력하여야 합니다.'),
            'W' => array(0, "main_disable_degree", '이용자의 주장애 장애정도를 입력해주세요. 반드시 [항목값 참조 시트의 장애등급 종류]를 참조해서 입력하여야 합니다.
예> 중증'),
            'X' => array(0, "main_disable_grade", '이용자의 주장애등급을 입력해주세요. 반드시 [항목값 참조 시트의 장애등급 종류]를 참조해서 입력하여야 합니다.'),
            'Y' => array(0, "sub_disable_name", '이용자의 부장애명을 입력해주세요. 반드시 [항목값 참조 시트의 장애명 종류]를 참조해서 입력하여야 합니다.'),
            'Z' => array(0, "sub_disable_degree", '이용자의 부장애 장애정도를 입력해주세요. 반드시 [항목값 참조 시트의 장애등급 종류]를 참조해서 입력하여야 합니다.
예> 중증'),
            'AA' => array(0, "sub_disable_grade", '이용자의 주장애등급을 입력해주세요. 반드시 [항목값 참조 시트의 장애등급 종류]를 참조해서 입력하여야 합니다.'),
            'AB' => array(0, "disease_name", '이용자의 보유질환을 자유롭게 입력해주세요.
예> 간질'),
            'AC' => array(0, "dosing_info", '이용자의 투약정보를 자유롭게 입력해주세요.
예> 고혈압약 복용중'),
            'AD' => array(0, "bed_disable_check", '이용자가 와상장애인지 입력해주세요. 와상장애인 경우 Y 또는 y를 입력하시면 됩니다.
예> Y'),
            'AE' => array(0, "marriage_check", '이용자의 결혼여부를 입력해주세요. 반드시 [항목값 참조 시트의 결혼여부]를 참조해서 입력하여야 합니다.'),
            'AF' => array(0, "family_details", '이용자의 가족사항을 입력해주세요. 반드시 [항목값 참조 시트의 가족사항]을 참조해서 입력하여야 합니다.'),
            'AG' => array(0, "guardian_name", '보호자 이름을 입력해주세요.  최대 15자 입력가능합니다.'),
            'AH' => array(0, "guardian_relationship", '이용자와 보호자의 관계를 입력해주세요. 최대 50자 입력가능합니다.'),
            'AI' => array(0, "guardian_phone", '보호자의 휴대전화번호를 입력해주세요. 최대 50자 입력가능합니다.'),
            'AJ' => array(0, "guardian_tel", '보호자의 자택전화번호를 입력해주세요. 최대 50자 입력가능합니다.'),
            'AK' => array(0, "guardian_address", '보호자의 주소를 입력해주세요.최대 200자 입력가능합니다.'),
            'AL' => array(0, "note", '특이사항(장애특성 및 일상생활)을 자유롭게 입력해주세요.'),
            'AM' => array(0, "overall", '이용자에 대한 종합 소견을 자유롭게 입력해주세요.'),
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($cells as $key => $val) {
            $cellName = $key . '1';
            $sheet->getColumnDimension($key)->setWidth($val[0]);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->setCellValue($cellName, $val[2]);
            $sheet->getStyle($cellName)->getAlignment()->setWrapText(true);
            $sheet->getStyle($cellName)->getFont()->setBold(true)->getColor()->setRGB("000000");
            $sheet->getStyle($cellName)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellName)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cellName)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFF00");
        }


        foreach ($cells2 as $key => $val) {
            $cellName = $key . '2';
            $sheet->getRowDimension('2')->setRowHeight(110);
            $sheet->setCellValue($cellName, $val[2]);
            $sheet->getStyle($cellName)->getAlignment()->setWrapText(true);
            $sheet->getStyle($cellName)->getFont()->setBold(true)->getColor()->setRGB("000000");
            $sheet->getStyle($cellName)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($cellName)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            if (!empty($val[3])) {
                $sheet->getStyle($cellName)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($val[3]);
            } else {
                $sheet->getStyle($cellName)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("DCE6F1");
            }

        }


        $all_members = self::get_all_users();
        $lists = [];
        foreach ($all_members as $key => $val) {
            $lists[] = objectToArray($val);
        }


        for ($i = 3; $row = array_shift($lists); $i++) {
            foreach ($cells as $key => $val) {
                $sheet->setCellValue($key . $i, $row[$val[1]]);
                $sheet->getRowDimension($i)->setRowHeight(25);
                $sheet->getStyle($key . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle($key . $i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle($key . $i)->getAlignment()->setWrapText(true);
            }
        }

        $filename = 'member_list';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    } /*작업중*/


}
