<?php

namespace App\Http\Controllers;

use App\Classes\Custom;
use App\Classes\Night;
use App\Models\HelperSchedules;
use App\Models\Holiday;
use App\Models\ServiceExtra;
use App\Models\Standard;
use App\Models\Tax;
use App\Models\Voucher;
use App\Models\VoucherPayment;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\Salary;


class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function non_inclusive($page=1)
    {
        return view("salary.non", [ "page"=>$page ]);
    }

    public function inclusive($page=1)
    {
        return view("salary.inclusive", [ "page"=>$page ]);
    }


    public function calc()
    {
        $_POST['pay_per_hour'] = VoucherPayment::get(date("Y")) ?? "";
        $_POST['pay_hour'] = 8750;
        return view("salary.calc", [ "lists" => [], "total" => [] ]);
    }

    public function calc_action(Request $request)
    {
        $voucherPayment = VoucherPayment::get(date("Y")) ?? "";

        $voucher_hour_pay = $request->input("pay_per_hour"); // 시간당인건비단가

        // 해당월의 바우처 내역 가져오기
        $work_lists = Voucher::getWorkData($request);
        /* ----------------------------------------- */

        // 바우처 승인내역 집계 구하기.
        $vouchers = Voucher::calc($request, $work_lists);

        // 반납 구하기
        $return = Voucher::getReturn($request, $work_lists);

        // 제공자 법정 지급항목 시뮬레이션
        $standard = Standard::get($request, $work_lists, $vouchers);

        /* 합계 */
        $total = Custom::setTotal();

        // 기본지급 (바우처상 지급합계 구하기)
        foreach ($vouchers as $key => $voucher)
        {

            $vouchers[$key]['User'] = Worker::getOne($key);

            // 근로기준법합계구하기
            $vouchers[$key]['Standard'] = $standard[$key];
            $vouchers[$key]['Return'] = $return[$key] ?? [];


            // 바우처 구한 시간으로 급여구하기
            $vouchers[$key] = Voucher::total($vouchers[$key]);

            // 바우처지급합계구하기 = 바우처지급합계
            $vouchers[$key]['Payment'] = Voucher::payment($request, $voucher['Voucher']);
            $vouchers[$key]['Return'] = $return[$key] ?? [];
            $vouchers[$key]['Standard'] = $standard[$key] ?? [];

            // 근로기준법 구한 시간으로 급여구하기
            $vouchers[$key] = Standard::total($vouchers[$key], $key, $request);

            // 공제구하기
            $vouchers[$key] = Tax::calc($request, $key, $vouchers[$key]);

            // 합계구하기
            $total = Salary::calcTotal($total, $vouchers[$key]);


            // 추가 서비스 내역 더 해주기
            $vouchers[$key]['Payment'] += ServiceExtra::add($request, $key);
            $service_extra_logs = ServiceExtra::get($request, $key);

            // 국비일때, 시도비일때 날짜 추가해주기.
            foreach ($service_extra_logs as $i => $service)
            {
                // 시도에 포함된다면 시도비로, 안된다면 모두 국비로
                $sido = [ "광역자치단체" ];
                $government_name = $service->local_government_name;
                $service_ymd = date("Y-m-d", strtotime($service->service_start_date_time));

                if (in_array($government_name, $sido)) {
                    $vouchers[$key]['Voucher']['CITY']['DAY'][date("Y-m-d", strtotime($service_ymd))] = 2;
                    $vouchers[$key]['Voucher']['CITY']['DAY_COUNT'] = count($vouchers[$key]['Voucher']['CITY']['DAY']);
                } else {
                    $vouchers[$key]['Voucher']['COUNTRY']['DAY'][date("Y-m-d", strtotime($service_ymd))] = 2;
                    $vouchers[$key]['Voucher']['COUNTRY']['DAY_COUNT'] = count($vouchers[$key]['Voucher']['COUNTRY']['DAY']);
                }
            }

            $vouchers[$key]['DATE_MERGE'] = array_merge($vouchers[$key]['Voucher']['CITY']['DAY'], $vouchers[$key]['Voucher']['COUNTRY']['DAY']);
            $vouchers[$key]['basic_time'] = $vouchers[$key]['Voucher']['COUNTRY']['TIME_NORMAL'] + $vouchers[$key]['Voucher']['CITY']['TIME_NORMAL'];


            // 공휴일 유급휴일임금계산
            $holidays = Holiday::calc([
                "date"  => $request->input("from_date"), // 급여기준연월
                "type1" => $request->input("timetable_1"), // 근무->근무
                "type2" => $request->input("timetable_2"), // 미근무->미근무
                "type3" => $request->input("timetable_3"), // 근무->미근무
                "type4" => $request->input("timetable_4"), // 미근무-> 근무
                "dates" => array_keys($vouchers[$key]['DATE_MERGE']), // 바우처국비, 시도비 일한날짜 합친거
                "provider_key" => $key, // 활동지원사키
                "basic_time" => $vouchers[$key]['basic_time'], // 바우처국비+시도비 시간 합친거
                "week_selector" => $request->input("public_allowance_day_selector"), // 공휴일 유급휴일임금계산 주5일, 주6일 선택값
                "pay_hour" => $request->input("pay_hour") // 기본시급
            ]);

            // 공휴일 유급임금 급여, 시간 더해주기
            $vouchers[$key]['Standard']['PUBLIC_HOLIDAY_PAY'] = $holidays['pay'] ?? 0;
            $vouchers[$key]['Standard']['PUBLIC_HOLIDAY_TIME'] = $holidays['time'] ?? 0;

            $vouchers[$key]['CompanyBusinessTotal'] -= $vouchers[$key]['Standard']['PUBLIC_HOLIDAY_PAY'];

            // 스탠다드 가격 추가해주기
            $vouchers[$key]['Standard']['PAY_TOTAL'] += $holidays['pay'];

        }


//        pp($vouchers);


        return view("salary.calc", [
            "lists"=>$vouchers,
            "total" => $total,
            "voucherPayment" => $voucherPayment
        ]);
    }


    public static function save(Request $request)
    {
        $decode = array();
        parse_str($request->input("data"), $decode);

        if (Salary::decide($request, $decode)) {
            session()->flash("msg", "성공적으로 저장했습니다.");
            return redirect("/salary/calc");
        } else {
            session()->flash("error", "저장에 실패했습니다.");
            return back();
        }
    }

}
