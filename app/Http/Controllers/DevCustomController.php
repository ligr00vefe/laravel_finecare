<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Illuminate\Support\Facades\DB;

class DevCustomController extends Controller
{
    // 간이세액표 올리기
    public function simplified_tax_table(Request $request)
    {
//        dd($request->file());
        $result = [ "succCnt"=>0, "errCnt"=>0, "dupCnt"=>0, "emptyCnt"=>0, "errData"=>[] ];


        $upload = $request->file("file")->store("upload/docs");
        $ext = $request->file("file")->extension();

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

            $len = count(array_filter($_worksheets[0]));

            unset($_worksheets[0]);
            unset($_worksheets[1]);
            unset($_worksheets[2]);
            unset($_worksheets[3]);
            unset($_worksheets[4]);

//            pp($_worksheets);
            $table = "simplified_tax_table";

            foreach ($_worksheets as $_key => $val)
            {
//                $val = array_slice($val, 0, $len);

                $values = [
                    "moreThan" => $val[0],
                    "under" => $val[1],
                    "dependents1" => $val[2],
                    "dependents2" => $val[3],
                    "dependents3" => $val[4],
                    "dependents4" => $val[5],
                    "dependents5" => $val[6],
                    "dependents6" => $val[7],
                    "dependents7" => $val[8],
                    "dependents8" => $val[9],
                    "dependents9" => $val[10],
                    "dependents10" => $val[11],
                    "dependents11" => $val[12],
                ];



                $success = DB::table($table)->insert($values);
                if ($success) {
                    $result['succCnt']++;
                } else {
                    $result['errCnt']++;
                    $result['errData'][] = "{$val[0]} 내역 등록에 실패했습니다";
                }
            }
        }
    }
}
