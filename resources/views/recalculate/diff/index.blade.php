@extends("layouts/layout")

@section("title")
    재정산 차액산정
@endsection

@section("content")

    @include("salary.side_nav")

    <link rel="stylesheet" href="/css/member/index.css">
    <style>

        #list_contents input {
            border: none;
            background-color: transparent;
            text-align: center;
        }

        .standard_tax {
            display: none;
        }

        .standard_total {
            display: none;
        }

        .body-wrapper {
            top:0;
            position: absolute;
            width: 100%;
            height: 300vh;
            background-color: rgba(0,0,0, 0.5);
        }

        .saveAction {
            width: 120px;
            height: 40px;
            background-color: #252525;
            border: none;
            color: white;
        }

        .table-style-default1 tr td,
        .table-style-default1 tr th
        {
            border-top: none !important;
            text-align: left !important;
            padding: 3px 0 !important;
        }

        .order-column thead.thead-origin th {
            height: 30px;
        }

        .DTFC_LeftBodyLiner {
            overflow-x: hidden !important;
        }

        #list_contents {
            height: 900px;
        }

        .table-top {
            height: 65px;
        }

        .loading-wrapper {
            display: none;
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            background-color: rgba(0,0,0,0.5)
        }

        .loading {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            border: .25rem solid rgba(255,255,255,0.2);
            border-top-color: white;
            animation: spin 0.7s infinite linear;
            position: absolute;
            top: 40%;
            left: 50%;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

    </style>
    <div class="loading-wrapper">
    <div class="loading"></div>
    </div>

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>재정산 차액산정</h1>
                <p style="display: inline-block; font-size: 14px;">계산시간이 다소 오래 걸릴 수 있습니다</p>
            </div>

        </article>

        <div class="search-form">
            <form action="" onsubmit="return submitAfterVisibleLoader(this)">

                <span>년 검색</span>

                <input type="text" class="from_date" id="from_date" autocomplete="off" name="from_date" readonly value="{{ $from_date ?? "" }}">
                <label for="from_date">
                    <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                </label>

                <span>근로자명</span>
                <input type="text" name="provider_name" value="{{ $provider_name ?? "" }}">

                <span>생년월일</span>
                <input type="text" name="provider_birth" placeholder="ex) 200101" value="{{ $provider_birth ?? "" }}">

                <button>검색</button>

            </form>
        </div>

        <script>
            function submitAfterVisibleLoader(f)
            {
                if (f.from_date.value == "") {
                    alert("날짜를 입력해주세요");
                    return false;
                }
                $(".loading-wrapper").css("display", "block");
                return true;
            }
        </script>

        @if ($lists)
            <article id="list_contents" class="m-top-20" style="overflow-x: auto">
            <form action="" id="payment_calc" name="payment_calc" method="post">

                <div class="">
                    <table id="calc_result" class=" stripe row-border order-column">
                        <thead class="thead-origin">
                        <tr class="table-top">
                            <th rowspan="2" colspan="2" class="b-right-b7">
                                개인기본
                            </th>

                            <th colspan="18">
                                바우처 승인내역 집계 ( 반납건, 지급보류건 제외 )
                            </th>

                            <th colspan="9">
                                반납승인내역
                            </th>
                            <th colspan="36">
                                제공자 법정 지급항목 시뮬레이션
                            </th>
                            <th colspan="15">
                                제공자 급여공제내역
                            </th>
                            <th colspan="6">
                                급여지급 및 이체현황
                            </th>
                            <th colspan="24">
                                사업수입 및 사업주 부담내역
                            </th>

                            <th colspan="3">

                            </th>
                        </tr>
                        <tr class="table-top">
                            <th colspan="6">
                                국비(A)
                            </th>
                            <th colspan="6">
                                도비, 시/군/구비(B)
                            </th>
                            <th colspan="6">
                                승인합계(C=A+B)
                            </th>

                            <th colspan="3">
                                국비반납
                            </th>
                            <th colspan="3">
                                도비반납
                            </th>
                            <th colspan="3">
                                합계
                            </th>


                            <th colspan="3">
                                바우처상 지급합계
                            </th>
                            <th colspan="3">
                                기본급
                            </th>
                            <th colspan="3">
                                연장수당
                            </th>
                            <th colspan="3">
                                휴일수당
                            </th>
                            <th colspan="3">
                                야근수당
                            </th>
                            <th colspan="3">
                                주휴수당
                            </th>
                            <th colspan="3">
                                연차수당
                            </th>
                            <th colspan="3">
                                근로자의날 수당
                            </th>
                            <th colspan="3">
                                공휴일 유급휴일 수당
                            </th>
                            <th colspan="3">
                                적용합계
                            </th>

                            <th colspan="3">
                                법정제수당(또는 차액)
                            </th>


                            <th colspan="3">
                                지급총액
                            </th>

                            <th colspan="3">
                                국민연금
                            </th>
                            <th colspan="3">
                                건강보험
                            </th>
                            <th colspan="3">
                                고용보험
                            </th>
                            <th colspan="3">
                                갑근세
                            </th>
                            <th colspan="3">
                                주민세
                            </th>

                            <th colspan="3">
                                공제합계
                            </th>
                            <th colspan="3">
                                차인지급액
                            </th>

                            <th colspan="3">
                                사업수입
                            </th>
                            <th colspan="3">
                                국민연금
                            </th>
                            <th colspan="3">
                                건강보험
                            </th>
                            <th colspan="3">
                                고용보험
                            </th>
                            <th colspan="3">
                                산재보험
                            </th>
                            <th colspan="3">
                                퇴직연금
                            </th>
                            <th colspan="3">
                                반납승인(사업주)
                            </th>
                            <th colspan="3">
                                사업주 부담합계
                            </th>
                            <th colspan="3">
                                차감 사업주 수익
                            </th>
                        </tr>
                        <tr class="table-top">
                            <th  >
                                No
                            </th>

                            <th  >
                                제공자KEY
                            </th>

                            <!-- 국비 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>
                            <th  >
                                가산금
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 도비 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>
                            <th  >
                                가산금
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 승인합계 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>
                            <th  >
                                가산금
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>


                            <!-- 국비반납 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 도비반납 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 합계 -->
                            <th  >
                                승인금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 바우처상지급합계 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 기본급 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 연장수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 휴일수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 야근수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 주휴수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 연차수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 근로기준법 근로자의날 수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>


                            <!-- 근로기준법 공휴일유급휴일수당 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>


                            <!-- 적용합계 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 법정제수당 또는 차액 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 지급총액 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 국민연금 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 건강보험 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 고용보험 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 갑근세 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 주민세 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 공제합계 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 차인지급액 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 사업수입 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 국민연금 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 건강보험 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>


                            <!-- 고용보험 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 산재보험 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 퇴직연금 -->
                            <th  >
                                금액
                            </th>
                            <th  >
                                재정산 금액
                            </th>
                            <th   class="accent-red">
                                차액
                            </th>

                            <!-- 반납승인(사업주) -->
                            <th>
                                금액
                            </th>
                            <th>
                                재정산 금액
                            </th>
                            <th class="accent-red">
                                차액
                            </th>

                            <!-- 사업주부담합계 -->
                            <th>
                                금액
                            </th>
                            <th>
                                재정산 금액
                            </th>
                            <th class="accent-red">
                                차액
                            </th>

                            <!-- 차감사업주수익 -->
                            <th>
                                금액
                            </th>
                            <th>
                                재정산 금액
                            </th>
                            <th class="accent-red">
                                차액
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($lists as $key => $list)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $key }}
                            </td>

                            <!-- 국비승인금액 -->
                            <td>
                                {{ num($list['payment_list']->nation_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->nation_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->nation_confirm_payment) }}
                            </td>

                            <!-- 국비가산금 -->
                            <td>
                                {{ num($list['payment_list']->nation_add_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->nation_add_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->nation_add_payment) }}
                            </td>

                            <!-- 시도비가산금 -->
                            <td>
                                {{ num($list['payment_list']->city_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->city_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->city_confirm_payment) }}
                            </td>

                            <!-- 시도비가산금 -->
                            <td>
                                {{ num($list['payment_list']->city_add_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->city_add_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->city_add_payment) }}
                            </td>

                            <!-- 승인합계 승인금액 -->
                            <td>
                                {{ num($list['payment_list']->voucher_total_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_total_confirm_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_total_confirm_payment) }}
                            </td>

                            <!-- 승인합계 가산금 -->
                            <td>
                                {{ num($list['payment_list']->voucher_total_confirm_payment_add) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_total_confirm_payment_add) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_total_confirm_payment_add) }}
                            </td>

                            <!-- 반납승인내역 국비반납 -->
                            <td>
                                {{ num($list['payment_list']->voucher_return_nation_pay) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_return_nation_pay) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_return_nation_pay) }}
                            </td>

                            <!-- 반납승인내역 시도비반납 -->
                            <td>
                                {{ num($list['payment_list']->voucher_return_city_pay) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_return_city_pay) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_return_city_pay) }}
                            </td>

                            <!-- 반납승인내역 합계 -->
                            <td>
                                {{ num($list['payment_list']->voucher_return_total_pay) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_return_total_pay) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_return_total_pay) }}
                            </td>

                            <!-- 바우처상 지급합계 -->
                            <td>
                                {{ num($list['payment_list']->voucherPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucherPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_payment) }}
                            </td>


                            <!-- 근로기준법 기본급 -->
                            <td>
                                {{ num($list['payment_list']->standard_basic_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_basic_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_basic_payment) }}
                            </td>

                            <!-- 근로기준법 연장수당 -->
                            <td>
                                {{ num($list['payment_list']->standard_over_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_over_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_over_payment) }}
                            </td>

                            <!-- 근로기준법 휴일수당 -->
                            <td>
                                {{ num($list['payment_list']->standard_holiday_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_holiday_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_holiday_payment) }}
                            </td>

                            <!-- 근로기준법 야간 -->
                            <td>
                                {{ num($list['payment_list']->standard_night_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_night_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_night_payment) }}
                            </td>

                            <!-- 근로기준법 주휴 -->
                            <td>
                                {{ num($list['payment_list']->standard_weekly_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_weekly_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_weekly_payment) }}
                            </td>

                            <!-- 근로기준법 연차 -->
                            <td>
                                {{ num($list['payment_list']->standard_yearly_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_yearly_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_yearly_payment) }}
                            </td>

                            <!-- 근로기준법 근로자의날 -->
                            <td>
                                {{ num($list['payment_list']->standard_workers_day_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_workers_day_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_workers_day_payment) }}
                            </td>

                            <!-- 근로기준법 공휴일기본지급 -->
                            <td>
                                {{ num($list['payment_list']->standard_public_day_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->standard_public_day_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_public_day_payment) }}
                            </td>

                            <!-- 근로기준법 적용합계 -->
                            <td>
                                {{ num($list['payment_list']->StandardPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->StandardPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->standard_payment) }}
                            </td>

                            <!-- 근로기준법 법정제수당 또는 차액 -->
                            <td>
                                {{ num($list['payment_list']->voucher_sub_standard_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucher_sub_standard_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_sub_standard_payment) }}
                            </td>

                            <!-- 바우처상 지급합계 (지급총액) -->
                            <td>
                                {{ num($list['payment_list']->voucherPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->voucherPaymentFromStandardTable) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->voucher_payment) }}
                            </td>


                            <!-- 제공자 국민연금 -->
                            <td>
                                {{ num($list['payment_list']->tax_nation_pension) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_nation_pension) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_nation_pension) }}
                            </td>

                            <!-- 제공자 건강보험 -->
                            <td>
                                {{ num($list['payment_list']->tax_health) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_health) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_health) }}
                            </td>

                            <!-- 제공자 고용보험 -->
                            <td>
                                {{ num($list['payment_list']->tax_employ) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_employ) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_employ) }}
                            </td>

                            <!-- 제공자 갑근세 -->
                            <td>
                                {{ num($list['payment_list']->tax_gabgeunse) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_gabgeunse) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_gabgeunse) }}
                            </td>

                            <!-- 제공자 주민세 -->
                            <td>
                                {{ num($list['payment_list']->tax_joominse) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_joominse) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_joominse) }}
                            </td>

                            <!-- 제공자 공제합계 -->
                            <td>
                                {{ num($list['payment_list']->tax_total) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_total) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_total) }}
                            </td>

                            <!-- 제공자 차인지급액 -->
                            <td>
                                {{ num($list['payment_list']->tax_sub_payment) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_sub_payment) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_sub_payment) }}
                            </td>

                            <!-- 사업주 사업수입 (계산안되고있음 수정 필요함) -->
                            <td>
                                {{ num($list['payment_list']->company_income) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->company_income) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->company_income) }}
                            </td>

                            <!-- 사업주 국민연금 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_nation) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_nation) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_nation) }}
                            </td>

                            <!-- 사업주 건강보험 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_health) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_health) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_health) }}
                            </td>

                            <!-- 사업주 고용보험 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_employ) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_employ) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_employ) }}
                            </td>

                            <!-- 사업주 산재보험 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_industry) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_industry) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_industry) }}
                            </td>

                            <!-- 사업주 퇴직연금 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_retirement) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_retirement) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_retirement) }}
                            </td>

                            <!-- 사업주 반납승인 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_return_confirm) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_return_confirm) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_return_confirm) }}
                            </td>

                            <!-- 사업주 부담합계 -->
                            <td>
                                {{ num($list['payment_list']->tax_company_tax_total) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->tax_company_tax_total) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->tax_company_tax_total) }}
                            </td>

                            <!--   사업주 차감사업주 수익 (사업수입이 이상해서 다시 계산해야함) -->
                            <td>
                                {{ num($list['payment_list']->company_payment_result) }}
                            </td>
                            <td>
                                {{ num($list['recalculate_list']->company_payment_result) }}
                            </td>
                            <td>
                                {{ num($list['payment_diff']->company_payment_result) }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </article> <!-- article list_contents end -->
        @endif
    </section>
    <script>
        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm",
                view: 'months',
                minView: 'months',
                clearButton: false,
                autoClose: true,
            });

        });


        $(document).ready(function() {
            $('#calc_result').DataTable({
                searching: false,
                lengthChange: false,
                scrollX:        "10000px",
                scrollY:        "600px",

                scrollCollapse: false,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 2,
                }
            });
        } );
    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


    <style>
        .search-form {
            width: 100%;
            height: 80px;
            line-height: 80px;
            background-color: #e8e8e8;
            padding: 0 20px;
            font-size: 15px;
        }

        .search-form input {
            width: 120px;
            height: 25px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .search-form button {
            background-color: #363636;
            width: 50px;
            height: 30px;
            line-height: 30px;
            color: white;
            text-align: center;
            border:none;
        }

        label[for='from_date'] {
            margin-left: -35px;
            margin-right: 20px;
            vertical-align: middle;
        }


        .search-form input[name=birth] {
            margin-right: 5px;
        }
    </style>

@endsection
