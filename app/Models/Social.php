<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Illuminate\Support\Facades\DB;

class Social extends Model
{
    use HasFactory;


    public static function edi_get($type, $page, $request)
    {
        $user_id = User::get_user_id();

        $offset = ((int)$page - 1) * 15;
        $limit = $_GET['limit'] ?? 15;

        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";

        $table = "";

        switch ($type)
        {
            case "np":
                $table = "edi_national_pension_logs";
                break;
            case "health":
                $table = "edi_health_ins_logs";
                break;
            case "ind":
                $table = "edi_industry_ins_logs";
                break;
            case "emp":
                $table = "edi_employment_ins_logs";
                break;
            default:
                break;
        }


        $lists = DB::table($table)
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $now) {

                if ($term != "") {
                    $query->where("name", "like", "%{$term}%");
                }

            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        $paging = DB::table($table)
            ->select(DB::raw("count(id) as cnt"))
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $now) {

                if ($term != "") {
                    $query->where("name", "like", "%{$term}%");
                }

            })
            ->orderByDesc("id")
            ->first("cnt")->cnt;

        return [ "lists" => $lists, "paging" => $paging ];
    }

    public static function get($type, $page)
    {

        $user_id = User::get_user_id();

        $offset = ((int)$page - 1) * 15;
        $limit = $_GET['limit'] ?? 15;

        $now = date("Y-m-d");

        $term = $_GET['term'] ?? "";

        $table = "";

        switch ($type)
        {
            case "np":
                $table = "national_pension_logs";
                break;
            case "health":
                $table = "health_ins_logs";
                break;
            case "ind":
                $table = "industry_ins_logs";
                break;
            case "emp":
                $table = "employment_ins_logs";
                break;
            default:
                break;
        }


        $lists = DB::table($table)
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $now) {

                if ($term != "") {
                    $query->where("name", "like", "%{$term}%");
                }

            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        $paging = DB::table($table)
            ->select(DB::raw("count(id) as cnt"))
            ->where("user_id", "=", $user_id)
            ->where(function($query) use($term, $type, $now) {

                if ($term != "") {
                    $query->where("name", "like", "%{$term}%");
                }

            })
            ->orderByDesc("id")
            ->first("cnt")->cnt;

        return [ "lists" => $lists, "paging" => $paging ];

    }

    public static function excel_validation($table, $excel_head)
    {
        $validation = [];

        switch ($table)
        {
            case "national_pension_logs":
                $validation = [ "순번", "국민연금번호", "주민번호", "가입자명", "정산사유", "정산적용기간", "결정보험료" ];
                break;
            case "health_ins_logs":
                $validation = [
                    "순번", "증번호", "주민번호", "성명", "보수월액", "구분", "산출보험료",
                    "정산보험료", "정산사유", "정산적용기간", "감면사유", "연말정산", "환급금이자", "고지보험료", "회계", "영업소기호", "직종", "취득일/상실일",  "구분",
                    "산출보험료", "정산보험료", "정산사유", "정산적용기간", "감면사유", "연말정산" , "환급금이자", "고지보험료", "회계", "영업소기호", "직종", "취득일/상실일"
                ];
                break;
            case "industry_ins_logs":
            case "employment_ins_logs":
                $validation = [
                    "순번", "원부번호", "주민번호", "가입자명", "결정보험료", "월평균보수월액", "비고"
                ];
                break;
            default:
                return false;
                break;
        }

        $result = true;

        foreach ($excel_head as $key => $val) {

//            echo $val . "::" . $validation[$key] . "<br>";

            if (trim($val) != trim($validation[$key])) {
                $result = false;
                break;
            }
        }


        return $result;
    }


    public static function upload($table, $request)
    {
        $user_id = User::get_user_id();

        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];

        // 갱신하기
        if ($request->input("upload_type") == "renew") {
            DB::table($table)->where("user_id", "=", $user_id)->delete();
        }

        $upload = $request->file("upload_file")->store("upload/docs");
        $ext = $request->file("upload_file")->extension();

        // 업로드 확장자에 따라서 리더로드 다르게
        if (strtolower($ext) == "xlsx") {
            $reader = IOFactory::createReader("Xlsx");
        } else if (strtolower($ext) == "csv" || strtolower($ext) == "txt") {
            $reader = new Csv();
            $reader->setInputEncoding('EUC-KR');
        } else {
            session()->flash("uploadMsg", "업로드 파일의 확장자가 잘못되었습니다. csv파일과 xlsx파일을 지원합니다");
            return false;
        }
        $reader->setReadDataOnly(true);
        $worksheetData = $reader->listWorksheetInfo($upload);

        foreach ($worksheetData as $key => $worksheet)
        {
            if ($key >= 1 ) continue; // 두번째시트는 양식이라서
            $sheetName = $worksheet['worksheetName'];

            $reader->setLoadSheetsOnly($sheetName);
            $spreadsheet = $reader->load($upload);

            $_worksheets = $spreadsheet->getActiveSheet()->toArray();

            // 엑셀 1열 값이 양식이랑 같은지 확인
            if (!self::excel_validation($table, $_worksheets[0])) {
                session()->flash("uploadMsg", "업로드 파일 양식이 틀립니다. 업로드 파일을 확인해 주세요.");
                return false;
            }

            $len = count(array_filter($_worksheets[0]));

            unset($_worksheets[0]);

            foreach ($_worksheets as $_key => $val)
            {
                /* 신규자료 업데이트 구현해야한다 */

                // 주민번호없으면 넘기기
                if ($val[2] == "") {
                    $result['emptyCnt']++;
                    continue;
                }

                $val = array_slice($val, 0, $len);

                $values = [];

                switch ($table)
                {
                    case "national_pension_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[3].substr($val[2], 0, 6),
                            "np_no" => $val[1],
                            "rsNo" => $val[2],
                            "name" => $val[3],
                            "reason" => $val[4],
                            "period" => date("Y-m-d", strtotime($val[5])),
                            "insurance_fee" => $val[6]
                        ];
                        break;
                    case "health_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[3].substr($val[2], 0, 6),
                            "proof_number" => $val[1],
                            "rsNo" => $val[2],
                            "name" => $val[3],
                            "monthly_price" => $val[4],
                            "division" => $val[5],
                            "prod_insurance_price" => $val[6],
                            "cal_insurance_price" => $val[7],
                            "cal_reason" => $val[8],
                            "cal_period" => $val[9],
                            "reduction_reason" => $val[10],
                            "year_end_tax" => $val[11],
                            "refund_interest" => $val[12],
                            "notice_insurance_price" => $val[13],
                            "accounting" => $val[14],
                            "business_symbol" => $val[15],
                            "job_type" => $val[16],
                            "gain_date" => $val[17],
                            "division2" => $val[18],
                            "prod_insurance_price2" => $val[19],
                            "cal_insurance_price2" => $val[20],
                            "cal_reason2" => $val[21],
                            "cal_period2" => $val[22],
                            "reduction_reason2" => $val[23],
                            "year_end_tax2" => $val[24],
                            "refund_interest2" => $val[25],
                            "notice_insurance_price2" => $val[26],
                            "accounting2" => $val[27],
                            "business_symbol2" => $val[28],
                            "job_type2" => $val[29],
                            "loss_date" => $val[30]
                        ];
                        break;

                    case "industry_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[3].substr($val[2], 0, 6),
                            "original_number" => $val[1],
                            "rsNo" => $val[2],
                            "name" => $val[3],
                            "insurance_fee" => $val[4],
                            "monthly_bosu_price" => $val[5],
                            "remarks" => $val[6]
                        ];
                        break;

                    case "employment_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[3].substr($val[2], 0, 6),
                            "original_number" => $val[1],
                            "rsNo" => $val[2],
                            "name" => $val[3],
                            "insurance_fee" => $val[4],
                            "monthly_bosu_price" => $val[5],
                            "remarks" => $val[6]
                        ];
                        break;

                    default:
                        break;
                }


                $success = DB::table($table)->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 내역 등록에 실패했습니다";
                }
            }
        }

        return $result;
    }


    public static function edi_upload($table, $request)
    {
        $user_id = User::get_user_id();

        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];

        // 갱신하기
        if ($request->input("upload_type") == "renew") {
            DB::table($table)->where("user_id", "=", $user_id)->delete();
        }

        $upload = $request->file("upload_file")->store("upload/docs");
        $ext = $request->file("upload_file")->extension();

        // 업로드 확장자에 따라서 리더로드 다르게
        if (strtolower($ext) == "xlsx") {
            $reader = IOFactory::createReader("Xlsx");
        } else if (strtolower($ext) == "csv" || strtolower($ext) == "txt") {
            $reader = new Csv();
            $reader->setInputEncoding('EUC-KR');
        } else if (strtolower($ext) == "xls") {
            $reader = new Xls();
        } else {
            session()->flash("uploadMsg", "업로드 파일의 확장자가 잘못되었습니다. csv, xlsx, xls파일을 지원합니다");
            return false;
        }
        $reader->setReadDataOnly(true);
        $worksheetData = $reader->listWorksheetInfo($upload);

        foreach ($worksheetData as $key => $worksheet)
        {

            if ($key >= 1 ) continue; // 두번째시트는 양식이라서
            $sheetName = $worksheet['worksheetName'];

            $reader->setLoadSheetsOnly($sheetName);
            $spreadsheet = $reader->load($upload);

            $_worksheets = $spreadsheet->getActiveSheet()->toArray();


            $len = 0;
            $column2rowTables = [ "edi_national_pension_logs", "edi_employment_ins_logs", "edi_industry_ins_logs" ];

            // 양식에 기본행이 2줄일경우 2줄 삭제해주기
            if (in_array($table, $column2rowTables)) {

                // len = 컬럼행 공백란이 너무 길게 늘어져있을 경우가 있어서 부하줄이려고 줄여줌
                $last_key = array_keys(array_filter($_worksheets[1]));
                $len = end($last_key) + 1;
                unset($_worksheets[0], $_worksheets[1]);
            } else {
                $last_key = array_keys(array_filter($_worksheets[0]));
                $len = end($last_key) + 1;
                unset($_worksheets[0]);
            }

            foreach ($_worksheets as $_key => $val)
            {
                /* 신규자료 업데이트 구현해야한다 */

                // 주민번호없으면 넘기기
                if ($val[1] == "") {
                    $result['emptyCnt']++;
                    continue;
                }

                $val = array_slice($val, 0, $len);

                $values = [];

                switch ($table)
                {
                    case "edi_national_pension_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[0] . substr($val[1], 0, 6),
                            "name" => $val[0],
                            "rsNo" => $val[1],
                            "monthly_base_income" => $val[2],
                            "monthly_ins_price" => $val[3],
                            "personal_charge" => $val[4],
                            "personal_contribute_price" => $val[5]
                        ];
                        break;

                    case "edi_health_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_ym" => date("Y-m-d", strtotime($val[0]."-01")),
                            "business_license" => $val[1],
                            "unit_office" => $val[2],
                            "high_order_number" => $val[3],
                            "accounting" => $val[4],
                            "proof_number" => $val[5],
                            "name" => $val[6],
                            "rsNo" => $val[7],
                            "target_id" => $val[6].substr($val[7], 0, 6),
                            "reduction_reason" => $val[8],
                            "job_type" => $val[9],
                            "grade" => $val[10],
                            "monthly_bosu_price" => $val[11],
                            "cal_ins_price" => $val[12],
                            "account_reason" => $val[13],
                            "start_date" => date("Y-m-d", strtotime($val[14]."-01")),
                            "end_date" => date("Y-m-d", strtotime($val[15]."-01")),
                            "account_price" => $val[16],
                            "notice_price" => $val[17],
                            "year_end_tax" => $val[18],
                            "gave_date" => $val[19],
                            "lose_date" => $val[20],
                            "recup_cal_ins_price" => $val[21],
                            "recup_acc_reason_code" => $val[22],
                            "recup_start_date" => $val[23],
                            "recup_end_date" => $val[24],
                            "recup_acc_ins_price" => $val[25],
                            "recup_notice_ins_price" => $val[26],
                            "recup_year_end_tax_ins_price" => $val[27],
                            "total_cal_ins_price" => $val[28],
                            "total_acc_ins_price" => $val[29],
                            "total_notice_ins_price" => $val[30],
                            "total_year_end_ins_price" => $val[31],
                            "health_return_price_interest" => $val[32],
                            "recup_return_price_interest" => $val[33],
                            "user_total_ins_price" => $val[34],
                        ];
                        break;

                    case "edi_industry_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[1].substr(str_replace("-", "", $val[2]), 0, 6),
                            "worker_type" => $val[0],
                            "name" => $val[1],
                            "birth" => str_replace("-", "", $val[2]),
                            "worker_original_number" => $val[3],
                            "employ_start_date" => str_replace("-", "", $val[4]) != "" ? date("Y-m-d", strtotime($val[4])) : "1970-01-01",
                            "employ_end_date" => str_replace("-", "", $val[5]) != "" ? date("Y-m-d", strtotime($val[5])) : "1970-01-01",
                            "leave_worker_average" => $val[6],
                            "monthly_bosu_average_price" => $val[7],
                            "cal_ins_price" => $val[8],
                            "re_cal_ins_price" => $val[9],
                            "acc_bosu_total_price" => $val[10],
                            "acc_ins_price" => $val[11],
                            "total_ins_price" => $val[12],
                        ];
                        break;

                    case "edi_employment_ins_logs":
                        $values = [
                            "user_id" => $user_id,
                            "target_id" => $val[1].substr(str_replace("-", "", $val[2]), 0, 6),
                            "worker_type" => $val[0],
                            "name" => $val[1],
                            "birth" => str_replace("-", "", $val[2]),
                            "worker_original_number" => $val[3],
                            "employ_start_date" => str_replace("-", "", $val[4]) ? date("Y-m-d", strtotime($val[4])) : "1970-01-01",
                            "employ_end_date" => str_replace("-", "", $val[5]) ? date("Y-m-d", strtotime($val[5])) : "1970-01-01",
                            "leave_worker_average" => $val[6],
                            "monthly_bosu_average_price" => $val[7],
                            "cal_worker_unemploy_benefit" => $val[8],
                            "cal_owner_unemploy_benefit" => $val[9],
                            "cal_owner_goan_ins_price" => $val[10],
                            "re_cal_worker_unemploy_benefit" => $val[11],
                            "re_cal_owner_unemploy_benefit" => $val[12],
                            "re_cal_owner_goan_ins_price" => $val[13],
                            "acc_bosu_total_price" => $val[14],
                            "acc_worker_unemploy_benefit" => $val[15],
                            "acc_owner_unemploy_benefit" => $val[16],
                            "acc_owner_goan_ins_price" => $val[17],
                            "total_worker_unemploy_benefit" => $val[18],
                            "total_owner_unemploy_benefit" => $val[19],
                            "total_owner_goan_ins_price" => $val[20],
                        ];
                        break;

                    default:
                        break;
                }


                $success = DB::table($table)->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 내역 등록에 실패했습니다";
                }
            }
        }

        return $result;
    }
}
