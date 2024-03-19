<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class EtcIncome extends Model
{
    use HasFactory;

    public static function get_user_id()
    {
        return User::where("remember_token", "=", session()->get("user_token"))->first("id")->id;
    }


    public static function get($page, $type)
    {
        $user_id = self::get_user_id();

        $offset = ((int)$page - 1) * 15;
        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";
        $from_date = $_GET['from_date'] ?? "";

        $limit = $_GET['limit'] ?? 15;

        $table = "";

        switch ($type)
        {
            case "fare":
                $table = "long_distance_charges_log";
                break;
            case "severe":
                $table = "severe_allowance_logs";
                break;
            default:
                break;
        }


        $lists = DB::table($table)
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $from_date, $now) {

                $date_column = $type == "fare" ? "target_ym" : "amount_date";

                if ($term != "") {
                    $query->where("provider_name", "like", "%{$term}%");
                }

                if ($from_date != "") {
                    $query->whereRaw("{$date_column} >= ?", [ date("Y-m-d", strtotime($from_date."-01")) ]);
                    $query->whereRaw("{$date_column} <= LAST_DAY(?)", [ date("Y-m-d", strtotime($from_date."-01")) ]);
                }

            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        $paging = DB::table($table)
            ->select(DB::raw("count(id) as cnt"))
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $from_date, $now) {

                $date_column = $type == "fare" ? "target_ym" : "amount_date";


                if ($term != "") {
                    $query->where("provider_name", "like", "%{$term}%");
                }

                if ($from_date != "") {
                    $query->whereRaw("{$date_column} >= ?", [ date("Y-m-d", strtotime($from_date."-01")) ]);
                    $query->whereRaw("{$date_column} <= LAST_DAY(?)", [ date("Y-m-d", strtotime($from_date."-01")) ]);
                }

            })
            ->orderByDesc("id")
            ->first("cnt")->cnt;

        return [ "lists" => $lists, "paging" => $paging ];
    }

    public static function fare_upload($request)
    {
        $user_id = User::get_user_id();

        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];

        $upload = $request->file("upload_file")->store("upload/docs");

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

            unset($_worksheets[0]);

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
                    "user_id" => $user_id,
                    "target_ym" => date("Y-m-d", strtotime($val[1]."-01")),
                    "business_division" => $val[2],
                    "business_class" => $val[3],
                    "provide_agency" => $val[4],
                    "business_license" => $val[5],
                    "provider_name" => $val[6],
                    "provider_birth" => $val[7],
                    "service_count" => $val[8],
                    "return_count" => $val[9],
                    "total_payment" => $val[10],
                    "provide_date" => $val[11],
                ];

                $success = DB::table("long_distance_charges_log")->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 원거리교통비 내역 등록에 실패했습니다";
                }
            }
        }

        return $result;
    }



    public static function severe_upload($request)
    {
        $user_id = User::get_user_id();

        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];

        $upload = $request->file("upload_file")->store("upload/docs");

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

            unset($_worksheets[0]);

            foreach ($_worksheets as $_key => $val)
            {

                // 이름없으면 넘기기
                if ($val[2] == "") {
                    $result['emptyCnt']++;
                    continue;
                }

                $val = arrayTrim($val);

                $target_id = $val[2] . substr($val[3], 0, 7);
                $provider_id = $val[4] . substr($val[5], 0, 7);


                $values = [
                    "user_id" => $user_id,
                    "target_id" => $target_id,
                    "return_check" => $val[1],
                    "target_name" => $val[2],
                    "target_rsNo" => $val[3],
                    "provider_name" => $val[4],
                    "provider_rsNo" => $val[5],
                    "provider_id" => $provider_id,
                    "provider_agency" => $val[6],
                    "business_license" => $val[7],
                    "sido" => $val[8],
                    "sigungu" => $val[9],
                    "business_division" =>              $val[10],
                    "business_class" =>                 $val[11],
                    "confirm_number" =>                 $val[12],
                    "confirm_date" =>               $val[13],
                    "service_start_date_time" =>                $val[14],
                    "service_end_date_time" =>              $val[15],
                    "general_payment_time" =>               $val[16],
                    "add_payment_time" =>               $val[17],
                    "payment_amount" =>                 $val[18],
                    "amount_date" =>                $val[19],
                    "return_date" =>                $val[20] ?: "1970-01-01"
                ];

                $success = DB::table("severe_allowance_logs")->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 중증가산수당 내역 등록에 실패했습니다";
                }
            }
        }

        return $result;
    }
}
