@extends("layouts/layout")

@section("title")
    급여관리 - 급여계산
@endsection

@php
use App\Classes\Custom;
@endphp


@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    <style>

        #list_contents input{
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

        .DTFC_LeftBodyLiner {
            overflow-x: hidden !important;
        }


    </style>

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>급여계산</h1>
                <div class="right-buttons">
                    <button type="button" class="btn-orange-wrap">
                        <img src="{{__IMG__}}/button_orange_plus.png" alt="항목별 활용방법 아이콘">
                        지급방식안내
                    </button>
                </div>
            </div>

            <div class="search-wrap">
                <form action="" method="post" name="payment_action" onsubmit="return paymentAction(this)">
                    @csrf
                    <table class="m-bottom-20">
                        <thead>
                        <tr>
                            <th>급여기준연월</th>
                            <td colspan="3">
                                <input type="text" readonly name="from_date" id="from_date" class="input-datepicker" autocomplete="off" value="{{ $_POST['from_date'] ?? "" }}">
                                <label for="from_date">
                                    <img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p>
                                    관공서공휴일<br>적용여부
                                </p>
                            </th>
                            <td colspan="3">
                                <div>
                                    <input type="radio" id="public_officers_holiday_check_1" name="public_officers_holiday_check" value="1" checked>
                                    <label for="public_officers_holiday_check_1" class="m-right-20">휴일적용</label>
                                    <input type="radio" id="public_officers_holiday_check_2" name="public_officers_holiday_check" value="2">
                                    <label for="public_officers_holiday_check_2">평일적용</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p>
                                    시간당인건비단가
                                </p>
                            </th>
                            <td>
                                <input type="text" name="pay_per_hour" value="{{ $_POST['pay_per_hour'] ?? "" }}" class="input-pay-auto">
                            </td>
                        </tr>
                        <tr>
                            <th class="required_mark">
                                <p class="t-left">
                                기본급 및 수당<br> 산정기준
                                </p>
                            </th>
                            <td class="search-type__td" colspan="3">
                                <table class="search-in-table">
                                    <tr>
                                        <td>기본시급(원)</td>
                                        <td>연장수당(%)</td>
                                        <td>휴일수당(%)</td>
                                        <td>휴일연장수당(%)</td>
                                        <td>야간수당(%)</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="pay_hour" value="{{ $_POST['pay_hour'] ?? "" }}">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_over_time" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_holiday" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_holiday_over_time" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_night" value="100">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>연차수당(%)</td>
                                        <td>1년 미만자 연차수당(%)</td>
                                        <td>공휴일의 유급휴일임금(%)</td>
                                        <td>근로자의 날 수당(%)</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="pay_annual" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_one_year_less_annual" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_public_holiday" value="100">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_workers_day" value="100">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>주휴수당 계산</th>
                            <td class="td_01" colspan="3">
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="checkbox" name="week_pay_apply_check" id="week_pay_apply_check" class="p-m-clear" value="1" checked>
                                    <label for="week_pay_apply_check" class="m-right-10">적용</label>
                                    <select name="week_pay_apply_type" id="week_pay_apply_type">
                                        <option value="">선택하세요</option>
                                        <option value="all" selected>전체적용</option>
                                        <option value="basic60">기본시간 60시간 이상 적용</option>
                                        <option value="basic65">기본시간 65시간 이상 적용</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="radio" id="week_pay_selector_1" name="week_pay_selector" value="5" checked>
                                    <label for="week_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="week_pay_selector_2" name="week_pay_selector" value="6">
                                    <label for="week_pay_selector_2">주 6일</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">연차수당 계산</p>
                            </th>
                            <td class="td_01">
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="checkbox" name="year_pay_apply_check" id="year_pay_apply_check" class="p-m-clear" value="1" checked>
                                    <label for="year_pay_apply_check" class="m-right-10">적용</label>
                                    <select name="year_pay_apply_type" id="year_pay_apply_type">
                                        <option value="">선택하세요</option>
                                        <option value="all" selected>전체적용</option>
                                        <option value="basic60">기본시간 60시간 이상 적용</option>
                                        <option value="basic65">기본시간 65시간 이상 적용</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="radio" id="year_pay_selector_1" name="year_pay_selector" value="5" checked>
                                    <label for="year_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="year_pay_selector_2" name="year_pay_selector" value="6">
                                    <label for="year_pay_selector_2">주 6일</label>
                                </div>
                            </td>
                            <th>
                                <p class="t-left">
                                    1년 미만자 연차<br>
                                    수당 계산
                                </p>
                            </th>
                            <td class="td_01">
                                <div class="fc-black-36 m-bottom-10">
                                    <input type="checkbox" name="one_year_less_annual_pay_check" id="one_year_less_annual_pay_check" class="p-m-clear" value="1" checked>
                                    <label for="one_year_less_annual_pay_check" class="m-right-10">적용</label>
                                    <select name="one_year_less_annual_pay_type" id="one_year_less_annual_pay_type">
                                        <option value="">선택하세요</option>
                                        <option value="all" selected>전체적용</option>
                                        <option value="basic60">기본시간 60시간 이상 적용</option>
                                        <option value="basic65">기본시간 65시간 이상 적용</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="radio" id="one_year_less_annual_pay_selector_1" name="one_year_less_annual_pay_selector" value="5"
                                    {{ isset($_POST['one_year_less_annual_pay_selector']) ? $_POST['one_year_less_annual_pay_selector'] == 5 ? "checked" : "" : "checked" }}
                                    >
                                    <label for="one_year_less_annual_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="one_year_less_annual_pay_selector_2" name="one_year_less_annual_pay_selector" value="6"
                                    {{ isset($_POST['one_year_less_annual_pay_selector']) ? $_POST['one_year_less_annual_pay_selector'] == 6 ? "checked" : "" : "" }}
                                    >
                                    <label for="one_year_less_annual_pay_selector_2">주 6일</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">
                                    공휴일의 유급휴일임금 계산
                                </p>
                            </th>
                            <td class="td_01">
                                <div class="fc-black-36 m-bottom-10">
                                    <input type="checkbox" name="public_allowance_check" id="public_allowance_check" class="p-m-clear" value="1" checked>
                                    <label for="public_allowance_check" class="m-right-10">적용</label>
                                    <select name="public_allowance_selector" id="public_allowance_selector">
                                        <option value="">선택하세요</option>
                                        <option value="all" selected>전체적용</option>
                                        <option value="basic60">기본시간 60시간 이상 적용</option>
                                        <option value="basic65">기본시간 65시간 이상 적용</option>
                                    </select>
                                    <button type="button">적용 여부 체크</button>
                                </div>
                                <div>
                                    <input type="radio" id="public_allowance_day_selector_1" name="public_allowance_day_selector" value="5" checked>
                                    <label for="public_allowance_day_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="public_allowance_day_selector_2" name="public_allowance_day_selector" value="6">
                                    <label for="public_allowance_day_selector_2">주 6일</label>
                                </div>
                                <div>
                                    <table class="table-style-default1">
                                        <tr>
                                            <th>
                                                근무→근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_1" id="timetable_1_1" value="1"
                                                {{ isset($_POST['timetable_1']) ? $_POST['timetable_1'] == 1 ? "checked" : "" : "checked" }}
                                                >
                                                <label for="timetable_1_1">지급</label>
                                                <input type="radio" name="timetable_1" id="timetable_1_2" value="2"
                                                {{ isset($_POST['timetable_1']) ? $_POST['timetable_1'] == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_1_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→미근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_2" id="timetable_2_1" value="1"
                                                {{ isset($_POST['timetable_2']) ? $_POST['timetable_2'] == 1 ? "checked" : "" : "checked" }}
                                                >
                                                <label for="timetable_2_1">지급</label>
                                                <input type="radio" name="timetable_2" id="timetable_2_2" value="2"
                                                {{ isset($_POST['timetable_2']) ? $_POST['timetable_2'] == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_2_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                근무→미근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_3" id="timetable_3_1" value="1"
                                                {{ isset($_POST['timetable_3']) ? $_POST['timetable_3'] == 1 ? "checked" : "" : "checked" }}
                                                >
                                                <label for="timetable_3_1">지급</label>
                                                <input type="radio" name="timetable_3" id="timetable_3_2" value="2"
                                                {{ isset($_POST['timetable_3']) ? $_POST['timetable_3'] == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_3_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_4" id="timetable_4_1" value="1"
                                                {{ isset($_POST['timetable_4']) ? $_POST['timetable_4'] == 1 ? "checked" : "" : "checked" }}
                                                >
                                                <label for="timetable_4_1">지급</label>
                                                <input type="radio" name="timetable_4" id="timetable_4_2" value="2"
                                                {{ isset($_POST['timetable_4']) ? $_POST['timetable_4'] == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_4_2">미지급</label>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <th>
                                <p class="t-left">
                                    근로자의 날<br>
                                    수당 계산
                                </p>
                            </th>
                            <td colspan="3">
                                <div class="fc-black-36 m-bottom-10">
                                    <input type="checkbox" name="workers_day_allowance_check" id="workers_day_allowance_check" class="p-m-clear" value="1"
                                    {{ isset($_POST['workers_day_allowance_check']) ? $_POST['workers_day_allowance_check'] == 1 ? "checked" : "" : "checked" }}
                                    >
                                    <label for="workers_day_allowance_check" class="m-right-10">적용</label>
                                </div>
                                <div>
                                    <input type="radio" id="workers_day_allowance_day_selector_1" name="workers_day_allowance_day_selector" value="5"
                                    {{ isset($_POST['workers_day_allowance_day_selector']) ? $_POST['workers_day_allowance_day_selector'] == 5 ? "checked" : "" : "checked" }}
                                    >
                                    <label for="workers_day_allowance_day_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="workers_day_allowance_day_selector_2" name="workers_day_allowance_day_selector" value="6"
                                    {{ isset($_POST['workers_day_allowance_day_selector']) ? $_POST['workers_day_allowance_day_selector'] == 6 ? "checked" : "" : "" }}
                                    >
                                    <label for="workers_day_allowance_day_selector_2">주 6일</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">
                                    바우처상 지급합계
                                </p>
                            </th>
                            <td>
                                <table class="calc_table_in">
                                    <tr>
                                        <td>
                                            <input type="radio" name="voucher_pay_total" id="voucher_pay_total_1" value="1" checked>
                                            <label class="m-right-20" for="voucher_pay_total_1">휴일수당 고정값</label>
                                        </td>
                                        <td style="border: none;">
                                            <input type="text" name="voucher_holiday_pay_fixing" placeholder="휴일수당 고정값 입력" value="13000">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="radio" name="voucher_pay_total" id="voucher_pay_total_2" value="2">
                                            <label for="voucher_pay_total_2">휴일수당 비례값</label>
                                        </td>
                                        <td>
                                            <input type="text" name="voucher_holiday_pay_hour_per_price" placeholder="시간당인건비단가 입력" disabled>
                                        </td>
                                    </tr>
                                </table>
                                {{--<div class="fc-black-36 m-top-10 m-bottom-10">--}}
                            {{----}}
                                {{--</div>--}}
                            </td>
                            <th>
                                <p class="t-left">
                                    예외결제<br>포함여부
                                </p>
                            </th>
                            <td>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="radio" name="except_payment_check" id="except_payment_check_1" value="1">
                                    <label class="m-right-20" for="except_payment_check_1">포함</label>
                                    <input type="radio" name="except_payment_check" id="except_payment_check_2" value="2">
                                    <label for="except_payment_check_2">미포함</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">
                                    퇴직적립금 적립방식
                                </p>
                            </th>
                            <td>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="radio" name="retirement_saving_pay_type" id="retirement_saving_pay_type_1" value="" >
                                    <label for="retirement_saving_pay_type_1">해당없음</label>
                                    <br>
                                    <input type="radio" name="retirement_saving_pay_type" id="retirement_saving_pay_type_2" value="a" checked>
                                    <label for="retirement_saving_pay_type_2">법적적립요율 A형 (제공인력급여총액/12)</label>
                                    <br>
                                    <input type="radio" name="retirement_saving_pay_type" id="retirement_saving_pay_type_3" value="b">
                                    <label for="retirement_saving_pay_type_3">법적적립요율 B형 (제공인력급여총액 * 0.083)</label>
                                    <br>
                                    <input type="radio" name="retirement_saving_pay_type" id="retirement_saving_pay_type_4" value="company">
                                    <label for="retirement_saving_pay_type_4">사업장적립요율</label>
                                    <input type="text" name="retirement_saving_pay_company_percentage">
                                    <br>
                                    <input type="radio" name="retirement_saving_pay_type" id="retirement_saving_pay_type_5" value="fix">
                                    <label for="retirement_saving_pay_type_5">월 고정액</label>
                                </div>
                            </td>
                            <th>
                                <p class="t-left">
                                    사회 보험 계산
                                </p>
                            </th>
                            <td>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">국민연금</span>
                                    <input type="radio" name="tax_nation_selector" id="tax_nation_selector_1" value="percentage"
                                        {{ isset($_POST['tax_nation_selector']) ? $_POST['tax_nation_selector'] == "percentage" ? "checked" : "" : "checked" }}
                                    >
                                    <label for="tax_nation_selector_1">비율</label>
                                    <input type="radio" name="tax_nation_selector" id="tax_nation_selector_2" value="pay"
                                        {{ isset($_POST['tax_nation_selector']) ? $_POST['tax_nation_selector'] == "pay" ? "checked" : "" : "" }}
                                    >
                                    <label for="tax_nation_selector_2">금액</label>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">건강보험</span>
                                    <input type="radio" name="tax_health_selector" id="tax_health_selector_1" value="percentage"
                                        {{ isset($_POST['tax_health_selector']) ? $_POST['tax_health_selector'] == "percentage" ? "checked" : "" : "checked" }}
                                    >
                                    <label for="tax_health_selector_1">비율</label>
                                    <input type="radio" name="tax_health_selector" id="tax_health_selector_2" value="pay"
                                        {{ isset($_POST['tax_health_selector']) ? $_POST['tax_health_selector'] == "pay" ? "checked" : "" : "" }}
                                    >
                                    <label for="tax_health_selector_2">금액</label>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">고용보험</span>
                                    <input type="radio" name="tax_employ_selector" id="tax_employ_selector_1" value="percentage"
                                        {{ isset($_POST['tax_employ_selector']) ? $_POST['tax_employ_selector'] == "percentage" ? "checked" : "" : "checked" }}
                                    >
                                    <label for="tax_employ_selector_1">비율</label>
                                    <input type="radio" name="tax_employ_selector" id="tax_employ_selector_2" value="pay"
                                        {{ isset($_POST['tax_employ_selector']) ? $_POST['tax_employ_selector'] == "pay" ? "checked" : "" : "" }}
                                    >
                                    <label for="tax_employ_selector_2">금액</label>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">산재보험</span>
                                    <input type="radio" name="tax_industry_selector" id="tax_industry_selector_1" value="percentage"
                                        {{ isset($_POST['tax_industry_selector']) ? $_POST['tax_industry_selector'] == "percentage" ? "checked" : "" : "checked" }}
                                    >
                                    <label for="tax_industry_selector_1">비율</label>
                                    <input type="radio" name="tax_industry_selector" id="tax_industry_selector_2" value="pay"
                                        {{ isset($_POST['tax_industry_selector']) ? $_POST['tax_industry_selector'] == "pay" ? "checked" : "" : "" }}
                                    >
                                    <label for="tax_industry_selector_2">금액</label>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">갑근세</span>
                                    <input type="radio" name="tax_gabgeunse_selector" id="tax_gabgeunse_selector_1" value="percentage"
                                        {{ isset($_POST['tax_gabgeunse_selector']) ? $_POST['tax_gabgeunse_selector'] == "percentage" ? "checked" : "" : "checked" }}
                                    >
                                    <label for="tax_gabgeunse_selector_1">비율</label>
                                    <input type="radio" name="tax_gabgeunse_selector" id="tax_gabgeunse_selector_2" value="pay"
                                        {{ isset($_POST['tax_gabgeunse_selector']) ? $_POST['tax_gabgeunse_selector'] == "pay" ? "checked" : "" : "" }}
                                    >
                                    <label for="tax_gabgeunse_selector_2">금액</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">
                                    고용보험료율
                                </p>
                            </th>
                            <td>
                                <select name="employ_tax_selector" id="employ_tax_selector">
                                    <option value="">선택하세요</option>
                                    <option value="basic" selected>기본(0.80%)</option>
                                    <option value="150under">150인 미만기업(1.05%)</option>
                                    <option value="150over">150인 이상 우선지원대상기업(1.25%)</option>
                                    <option value="1000under">150인~1000인 미만 기업(1.45%)</option>
                                    <option value="1000over">1,000인 이상 기업 / 국가지방자치단체(1.65%)</option>
                                </select>
                            </td>
                            <th>
                                <p class="t-left">
                                    산재요율
                                </p>
                            </th>
                            <td>
                                <input type="text" name="industry_tax_percentage" value="2"> %
                            </td>
                        </tr>
                        </thead>
                    </table>

                    <div class="btn-wrap">
                        <button class="btn-black">
                            계산하기
                        </button>
                    </div>

                </form>
            </div>

        </article> <!-- article list_head end -->


        @if ($lists)
        <article id="list_contents" class="m-top-20">
            <form action="" id="payment_calc" name="payment_calc" method="post">
                <input type="hidden" name="payment_ym" value="{{ $_POST['from_date'] ?? "" }}">
                <input type="hidden" name="public_officers_holiday_check" value="{{ $_POST['public_officers_holiday_check'] ?? "" }}">
                <input type="hidden" name="pay_per_hour" value="{{ $_POST['pay_per_hour'] ?? "" }}">
                <input type="hidden" name="pay_hour" value="{{ $_POST['pay_hour'] ?? "" }}">
                <input type="hidden" name="pay_over_time" value="{{ $_POST['pay_over_time'] ?? "" }}">
                <input type="hidden" name="pay_holiday" value="{{ $_POST['pay_holiday'] ?? "" }}">
                <input type="hidden" name="pay_holiday_over_time" value="{{ $_POST['pay_holiday_over_time'] ?? "" }}">
                <input type="hidden" name="pay_night" value="{{ $_POST['pay_night'] ?? "" }}">
                <input type="hidden" name="pay_annual" value="{{ $_POST['pay_annual'] ?? "" }}">
                <input type="hidden" name="pay_one_year_less_annual" value="{{ $_POST['pay_one_year_less_annual'] ?? "" }}">
                <input type="hidden" name="pay_public_holiday" value="{{ $_POST['pay_public_holiday'] ?? "" }}">
                <input type="hidden" name="pay_workers_day" value="{{ $_POST['pay_workers_day'] ?? "" }}">
                <input type="hidden" name="week_pay_apply_check" value="{{ $_POST['week_pay_apply_check'] ?? "" }}">
                <input type="hidden" name="week_pay_apply_type" value="{{ $_POST['week_pay_apply_type'] ?? "" }}">
                <input type="hidden" name="week_pay_selector" value="{{ $_POST['week_pay_selector'] ?? "" }}">
                <input type="hidden" name="year_pay_apply_check" value="{{ $_POST['year_pay_apply_check'] ?? "" }}">
                <input type="hidden" name="year_pay_apply_type" value="{{ $_POST['year_pay_apply_type'] ?? "" }}">
                <input type="hidden" name="year_pay_selector" value="{{ $_POST['year_pay_selector'] ?? "" }}">
                <input type="hidden" name="one_year_less_annual_pay_check" value="{{ $_POST['one_year_less_annual_pay_check'] ?? "" }}">
                <input type="hidden" name="one_year_less_annual_pay_type" value="{{ $_POST['one_year_less_annual_pay_type'] ?? "" }}">
                <input type="hidden" name="one_year_less_annual_pay_selector" value="{{ $_POST['one_year_less_annual_pay_selector'] ?? "" }}">
                <input type="hidden" name="public_allowance_check" value="{{ $_POST['public_allowance_check'] ?? "" }}">
                <input type="hidden" name="public_allowance_selector" value="{{ $_POST['public_allowance_selector'] ?? "" }}">
                <input type="hidden" name="public_allowance_day_selector" value="{{ $_POST['public_allowance_day_selector'] ?? "" }}">

                <input type="hidden" name="workers_day_allowance_check" value="{{ $_POST['workers_day_allowance_check'] ?? "" }}">
                <input type="hidden" name="workers_day_allowance_day_selector" value="{{ $_POST['workers_day_allowance_day_selector'] ?? "" }}">
                <input type="hidden" name="voucher_pay_total" value="{{ $_POST['voucher_pay_total'] ?? "" }}">
                <input type="hidden" name="voucher_holiday_pay_fixing" value="{{ $_POST['voucher_holiday_pay_fixing'] ?? "" }}">
                <input type="hidden" name="retirement_saving_pay_type" value="{{ $_POST['retirement_saving_pay_type'] ?? "" }}">
                <input type="hidden" name="retirement_saving_pay_company_percentage" value="{{ $_POST['retirement_saving_pay_company_percentage'] ?? "" }}">
                <input type="hidden" name="tax_nation_selector" value="{{ $_POST['tax_nation_selector'] ?? "" }}">
                <input type="hidden" name="tax_health_selector" value="{{ $_POST['tax_health_selector'] ?? "" }}">
                <input type="hidden" name="tax_employ_selector" value="{{ $_POST['tax_employ_selector'] ?? "" }}">
                <input type="hidden" name="tax_industry_selector" value="{{ $_POST['tax_industry_selector'] ?? "" }}">
                <input type="hidden" name="tax_gabgeunse_selector" value="{{ $_POST['tax_gabgeunse_selector'] ?? "" }}">
                <input type="hidden" name="employ_tax_selector" value="{{ $_POST['employ_tax_selector'] ?? "" }}">
                <input type="hidden" name="industry_tax_percentage" value="{{ $_POST['industry_tax_percentage'] ?? "" }}">


                <input type="hidden" name="timetable_1" value="{{ $_POST['timetable_1'] ?? "" }}">
                <input type="hidden" name="timetable_2" value="{{ $_POST['timetable_2'] ?? "" }}">
                <input type="hidden" name="timetable_3" value="{{ $_POST['timetable_3'] ?? "" }}">
                <input type="hidden" name="timetable_4" value="{{ $_POST['timetable_4'] ?? "" }}">

                <div class="">
                <table id="calc_result" class=" stripe row-border order-column">
                    <thead class="thead-origin">
                        <tr class="table-top">
                            <th rowspan="2" colspan="11" class="b-right-b7">
                                개인기본 및 사회보험 가입현황
                            </th>
                            <th colspan="22">
                                바우처 승인내역 집계 ( 반납건, 지급보류건 제외 )
                            </th>

                            <th colspan="9">
                                반납승인내역
                            </th>
                            <th colspan="20">
                                제공자 법정 지급항목 시뮬레이션
                            </th>
                            <th colspan="6">
                                제공자 급여공제내역
                            </th>
                            <th colspan="3">
                                급여지급 및 이체현황
                            </th>
                            <th colspan="8">
                                사업수입 및 사업주 부담내역
                            </th>

                            <th>

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
                        <th colspan="4">
                            승인금액 분리
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


                        <th rowspan="3">
                            바우처상 지급합계
                        </th>
                        <th colspan="2">
                            기본급
                        </th>
                        <th colspan="2">
                            연장수당
                        </th>
                        <th colspan="2">
                            휴일수당
                        </th>
                        <th colspan="2">
                            야근수당
                        </th>
                        <th colspan="2">
                            주휴수당
                        </th>
                        <th colspan="2">
                            연차수당
                        </th>
                        <th colspan="2">
                            근로자의날 수당
                        </th>
                        <th colspan="2">
                            공휴일 유급휴일 수당
                        </th>
                        <th rowspan="3">
                            적용합계
                        </th>

                        <th rowspan="3">
                            법정제수당(또는 차액)
                        </th>


                        <th rowspan="3">
                            지급총액
                        </th>

                        <th rowspan="3">
                            국민연금
                        </th>
                        <th rowspan="3">
                            건강보험
                        </th>
                        <th rowspan="3">
                            고용보험
                        </th>
                        <th rowspan="3">
                            갑근세
                        </th>
                        <th rowspan="3">
                            주민세
                        </th>

                        <th rowspan="3">
                            공제합계
                        </th>
                        <th rowspan="3">
                            차인지급액
                        </th>
                        <th rowspan="3">
                            은행명
                        </th>
                        <th rowspan="3">
                            계좌번호
                        </th>

                        <th rowspan="3">
                            사업수입
                        </th>
                        <th rowspan="3">
                            국민연금
                        </th>
                        <th rowspan="3">
                            건강보험
                        </th>
                        <th rowspan="3">
                            고용보험
                        </th>
                        <th rowspan="3">
                            산재보험
                        </th>
                        <th rowspan="3">
                            퇴직연금
                        </th>
                        <th rowspan="3">
                            반납승인(사업주)
                        </th>
                        <th rowspan="3">
                            사업주 부담합계
                        </th>
                        <th rowspan="3">
                            차감 사업주 수익
                        </th>
                    </tr>
                    <tr class="table-top">
                        <th rowspan="2">
                            No
                        </th>
                        <th rowspan="2">
                            대상자명
                        </th>
                        <th rowspan="2">
                            제공자KEY
                        </th>
                        <th rowspan="2">
                            제공자<br>미등록
                        </th>
                        <th rowspan="2">
                            입사일
                        </th>
                        <th rowspan="2">
                            퇴사일
                        </th>
                        <th colspan="4">
                            사회보험
                        </th>
                        <th rowspan="2">
                            연차갯수
                        </th>
                        <th rowspan="2">
                            근무일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            가산금
                        </th>
                        <th rowspan="2">
                            총 시간
                        </th>
                        <th rowspan="2">
                            휴일시간
                        </th>
                        <th rowspan="2">
                            야간시간
                        </th>
                        <th rowspan="2">
                            근무<br>일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            가산금
                        </th>
                        <th rowspan="2">
                            총 시간
                        </th>
                        <th rowspan="2">
                            휴일시간
                        </th>
                        <th rowspan="2">
                            야간시간
                        </th>
                        <th rowspan="2">
                            근무<br>일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            가산금
                        </th>
                        <th rowspan="2">
                            총시간
                        </th>
                        <th rowspan="2">
                            휴일시간
                        </th>
                        <th rowspan="2">
                            야간시간
                        </th>
                        <th rowspan="2">
                            기본급
                        </th>
                        <th rowspan="2">
                            휴일수당
                        </th>
                        <th rowspan="2">
                            야근수당
                        </th>
                        <th rowspan="2">
                            승인금액차
                        </th>

                        <th rowspan="2">
                            근무일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            총 시간
                        </th>
                        <th rowspan="2">
                            근무일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            총 시간
                        </th>
                        <th rowspan="2">
                            근무일수
                        </th>
                        <th rowspan="2">
                            승인금액
                        </th>
                        <th rowspan="2">
                            총 시간
                        </th>

                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                        <th rowspan="2">
                            시간
                        </th>
                        <th rowspan="2">
                            금액
                        </th>
                    </tr>
                    <tr class="table-top">
                        <th>국</th>
                        <th>건</th>
                        <th>고</th>
                        <th>퇴</th>
                    </tr>
                </thead>
                <tbody>
                @if (isset($total))
                <tr class="total">
                    <th></th>
                    <th>합계</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                    <th >
                    </th>
                    <th> {{-- 10번째 --}}
                    </th>
                    <th>

                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_DAY']) }}
                    </th> <!-- 바우처 국비일수 -->
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_PAYMENT_EXTRA']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_TIME_HOLIDAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_NATION_TIME_NIGHT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_DAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_PAYMENT_EXTRA']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_TIME_HOLIDAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_CITY_TIME_NIGHT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_DAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_PAYMENT_EXTRA']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_TIME_HOLIDAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_TIME_NIGHT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_DETACH_PAYMENT_BASIC']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_DETACH_PAYMENT_HOLIDAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_DETACH_PAYMENT_NIGHT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['VOUCHER_DETACH_PAYMENT_DIFF']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['RETURN_NATION_DAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_NATION_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_NATION_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_CITY_DAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_CITY_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_CITY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_DAY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETURN_TIME']) }}
                    </th>
                    <th>
                        {{-- 바우처상 지급합계 --}}
                        <span id="voucher_pay_total">{{ decimalFormat($total['VOUCHER_PAY_TOTAL']) }}</span>
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_BASIC_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_BASIC_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_OVER_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_OVER_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_HOLIDAY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_HOLIDAY_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_NIGHT_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_NIGHT_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_WEEKLY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_WEEKLY_PAYMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_YEARLY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_YEARLY_PAYMENT']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['STANDARD_WORKERS_DAY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['STANDARD_WORKERS_DAY_PAYMENT']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['PUBLIC_HOLIDAY_TIME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['PUBLIC_HOLIDAY_PAY']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['STANDARD_PAYMENT']) }}
                    </th>

                    <th>
                        {{-- 법정제수당 또는 차액 total --}}
                        <span id="payment_diff_total">{{ decimalFormat($total['PAYMENT_DIFF']) }}</span>
                    </th>



                    <th>
                        {{ decimalFormat($total['VOUCHER_PAY_TOTAL']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['TAX_WORKER_NATION']) }}
                    </th>

                    <th>
                        {{ decimalFormat($total['TAX_WORKER_HEALTH']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_WORKER_EMPLOY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_WORKER_GABGEUNSE']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_WORKER_RESIDENT']) }}
                    </th>
                    <th>
                        <span class="workers_tax_total">{{ decimalFormat($total['TAX_WORKER_TOTAL']) }}</span>
                    </th>

                    <th>
                        <span class="tax_sub_total">{{ decimalFormat($total['PAYMENT_TAX_SUB']) }}</span>
                    </th>

                    {{-- 은행명, 계좌번호 --}}
                    <th></th>
                    <th></th>

                    <th>
                        {{ decimalFormat($total['COMPANY_INCOME']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_COMPANY_NATION']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_COMPANY_HEALTH']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_COMPANY_EMPLOY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_COMPANY_INDUSTRY']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['RETIREMENT']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['CONFIRM_RETURN']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['TAX_COMPANY_TOTAL']) }}
                    </th>
                    <th>
                        {{ decimalFormat($total['PAYMENT_COMPANY_TAX_SUB']) }}
                    </th>
                </tr>
                @endif
                @forelse($lists as $key => $list)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <input type="text" name="provider_name[]" value="{{ $list['User']->name ?? "정보없음" }}">
                        </td>
                        <td>
                            <input type="text" name="provider_key[]" value="{{ $list['User']->target_key ?? $key }}">
                        </td>
                        <td>
                            <input type="text" name="provider_reg_check[]" value="{!! isset($list['User']->target_id) ? "" : "미등록"  !!}" style="color: red; text-align: center">
                        </td>
                        <td>
                            <input type="text" name="join_date[]" value="{{ isset($list['User']->contract_start_date) ? date("Y-m-d", strtotime($list['User']->contract_start_date)) : "정보없음" }}">
                        </td>
                        <td>
                            <input type="text" name="resign_date[]" value="{{ isset($list['User']->contract_end_date) ? $list['User']->contract_end_date != "1970-01-01 00:00:00" ?
                            date("Y-m-d", strtotime($list['User']->contract_end_date))
                            : ""
                            : "" }}">
                        </td>
                        <td>
                            <input type="text" name="national_pension[]" value="{{ $list['User']->national_ins_check ?? "미가입" }}">
                        </td>
                        <td>
                            <input type="text" name="health_insurance[]" value="{{ $list['User']->health_ins_check ?? "미가입" }}">
                        </td>
                        <td>
                            <input type="text" name="employment_insurance[]" value="{{ $list['User']->employ_ins_check ?? "미가입" }}">
                        </td>
                        <td>
                            <input type="text" name="retirement_pay_contract[]" value="{{ $list['User']->retire_added_check ?? "미가입" }}">
                        </td>
                        <td>
                            <input type="text" name="year_rest_count[]" value="{{ $list['User']->year_rest_count ?? "정보없음" }}">
                        </td>
                        {{-- 국가비 --}}

                        <td>
                            <input type="hidden" name="provider_id[]" value="{{ $list['User']->id ?? "" }}">

                            {{-- 국비 근무일수 --}}
                            <input type="text" name="country_day_count[]" value="{{ $list['Voucher']['COUNTRY']['DAY_COUNT'] }}">
                        </td>
                        <td>
                            {{-- 국비 승인금액 --}}
                            <input type="text" name="country_payment_total[]" value="{{ decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_TOTAL']) }}">
                        </td>
                        <td>
                            {{-- 국비 가산금 --}}
                            <input type="text" name="country_payment_extra[]" value="{{ decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_EXTRA']) }}">
                        </td>
                        <td>
                            {{-- 국비 총 시간 --}}
                            <input type="text" name="country_time_total[]" value="{{ decimalFormat($list['Voucher']['COUNTRY']['TIME_TOTAL']) }}">

                        </td>
                        <td>
                            {{-- 국비 휴일시간 --}}
                            <input type="text" name="country_time_holiday[]" value="{{ decimalFormat($list['Voucher']['COUNTRY']['TIME_HOLIDAY']) }}">
                        </td>
                        <td>
                            <!-- 국비 야간시간 -->
                            <input type="text" name="country_time_night[]" value="{{ decimalFormat($list['Voucher']['COUNTRY']['TIME_NIGHT']) }}">
                        </td>

                        {{-- 시도비 --}}
                        <td>
                            <input type="text" name="city_day_count[]" value="{{ decimalFormat($list['Voucher']['CITY']['DAY_COUNT']) }}">

                        </td>
                        <td>
                            {{-- 시도비 승인금액 --}}
                            <input type="text" name="city_payment_total[]" value="{{ decimalFormat($list['Voucher']['CITY']['PAYMENT_TOTAL']) }}">
                        </td>
                        <td>
                            {{-- 시도비 승인금액 --}}
                            <input type="text" name="city_payment_extra[]" value="{{ decimalFormat($list['Voucher']['CITY']['PAYMENT_EXTRA']) }}">
                        </td>
                        <td>
                            {{-- 시도비 총 시간 --}}
                            <input type="text" name="city_time_total[]" value="{{ decimalFormat($list['Voucher']['CITY']['TIME_TOTAL']) }}">
                        </td>
                        <td>
                            {{-- 시도비 휴일시간 --}}
                            <input type="text" name="city_time_holiday[]" value="{{ decimalFormat($list['Voucher']['CITY']['TIME_HOLIDAY']) }}">
                        </td>
                        <td>
                            <!-- 시도비 야간시간 -->
                            <input type="text" name="city_time_night[]" value="{{ decimalFormat($list['Voucher']['CITY']['TIME_NIGHT']) }}">
                        </td>

                        {{-- 승인합계 --}}
                        <td>
                            <!-- 근무일수 -->
                            <input type="text" name="voucher_total_day_count[]" value="{{ Custom::array_merge_with_duplicate_keys($list['Voucher']['COUNTRY']['DAY'], $list['Voucher']['CITY']['DAY']) }}">
                        </td>
                        <td>
                            <!-- 승인금액 -->
                            <input type="text" name="voucher_total_payment[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_TOTAL']
                            + $list['Voucher']['CITY']['PAYMENT_TOTAL'])
                            }}">
                        </td>
                        <td>
                            <!-- 가산금 -->
                            <input type="text" name="voucher_total_payment_extra[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_EXTRA']
                           + $list['Voucher']['CITY']['PAYMENT_EXTRA'])
                           }}">
                        </td>
                        <td>
                            <!-- 총시간 -->
                            <input type="text" name="voucher_total_time[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['TIME_TOTAL']
                            + $list['Voucher']['CITY']['TIME_TOTAL'])
                            }}">
                        </td>
                        <td>
                            <!-- 휴일시간 -->
                            <input type="text" name="voucher_total_time_holiday[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['TIME_HOLIDAY']
                            + $list['Voucher']['CITY']['TIME_HOLIDAY'])
                            }}">
                        </td>
                        <td>
                            <!-- 야간시간 -->
                            <input type="text" name="voucher_total_time_night[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['TIME_NIGHT']
                            + $list['Voucher']['CITY']['TIME_NIGHT'])
                            }}">
                        </td>

                        {{-- 승인금액 분리 --}}
                        <td>
                            <!-- 기본급 -->
                            <input type="text" name="voucher_detach_payment_normal[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_NORMAL']
                            + $list['Voucher']['CITY']['PAYMENT_NORMAL'])
                            }}">
                        </td>
                        <td>
                            <!-- 휴일수당 -->
                            <input type="text" name="voucher_detach_payment_holiday[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_HOLIDAY']
                            + $list['Voucher']['CITY']['PAYMENT_HOLIDAY'])
                            }}">
                        </td>
                        <td>
                            <!-- 야근수당 -->
                            <input type="text" name="voucher_detach_payment_night[]" value="{{
                            decimalFormat($list['Voucher']['COUNTRY']['PAYMENT_NIGHT']
                            + $list['Voucher']['CITY']['PAYMENT_NIGHT'])
                            }}">
                        </td>
                        <td>
                            <!-- 승인금액차 -->
                            <input type="text" name="voucher_detach_payment_diff[]" value="{{
                            decimalFormat(
                            ($list['Voucher']['COUNTRY']['PAYMENT_TOTAL'] + $list['Voucher']['CITY']['PAYMENT_TOTAL'])
                            - ($list['Voucher']['COUNTRY']['PAYMENT_NORMAL']
                            + $list['Voucher']['CITY']['PAYMENT_NORMAL']
                            + $list['Voucher']['COUNTRY']['PAYMENT_HOLIDAY']
                            + $list['Voucher']['CITY']['PAYMENT_HOLIDAY']
                            + $list['Voucher']['COUNTRY']['PAYMENT_NIGHT']
                            + $list['Voucher']['CITY']['PAYMENT_NIGHT']))
                            }}">
                        </td>


                        {{-- 반납승인내역 --}}
                        <td>
                            <!-- 반납승인내역 국비반납 근무일수 -->
                            <input type="text" name="return_country_day_count[]" value="{{ decimalFormat(count($list['Return']['COUNTRY']['DAYS'])) }}">
                        </td>
                        <td>
                            <!-- 반납승인내역 국비반납 승인금액 -->
                            <input type="text" name="return_country_payment[]" value="{{ decimalFormat($list['Return']['COUNTRY']['PAYMENT_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 반납승인내역 총 시간 -->
                            <input type="text" name="return_country_time[]" value="{{ decimalFormat($list['Return']['COUNTRY']['TIME_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 반납승인내역 도비반납 근무일수 -->
                            <input type="text" name="return_city_day_count[]" value="{{ decimalFormat(count($list['Return']['CITY']['DAYS'])) }}">
                        </td>
                        <td>
                            <!-- 반납승인내역 도비반납 승인금액 -->
                            <input type="text" name="return_city_payment[]" value="{{ decimalFormat($list['Return']['CITY']['PAYMENT_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 반납승인내역 도비 총 시간 -->
                            <input type="text" name="return_city_time[]" value="{{ decimalFormat($list['Return']['CITY']['TIME_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 반납합계 근무일수 -->
                            <input type="text" name="return_total_day_count[]" value="{{ decimalFormat(count($list['Return']['COUNTRY']['DAYS']) + count($list['Return']['CITY']['DAYS'])) }}">
                        </td>
                        <td>
                            <!-- 반납합계 승인금액 -->
                            <input type="text" name="return_total_payment[]" value="{{ decimalFormat($list['Return']['COUNTRY']['PAYMENT_TOTAL'] + $list['Return']['CITY']['PAYMENT_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 반납합계 승인시간 -->
                            <input type="text" name="return_total_time[]" value="{{ decimalFormat($list['Return']['COUNTRY']['TIME_TOTAL'] + $list['Return']['CITY']['TIME_TOTAL']) }}">
                        </td>


                        {{-- 제공자 법정 지급항목 시뮬레이션(근로기준법) --}}
                        <td>
                            <!-- 바우처상 지급합계(기본지급, 바우처상 계산 총합이다) -->
                            <input type="text" name="voucher_payment_total[]" class="voucher_payment_total" value="{{ decimalFormat($list['Payment']) }}">
                        </td>
                        <td>
                            <!-- 기본급 시간 계산해보니 기본급 야간포함 -->
                            <input type="text" name="standard_basic_time[]" value="{{ decimalFormat($list['Standard']['TIME_BASIC']) }}">
                        </td>
                        <td>
                            <!-- 기본급 금액 -->
                            <input type="text" name="standard_pay_basic[]" value="{{ decimalFormat($list['Standard']['PAY_BASIC']) }}">
                        </td>
                        <td>
                            <!-- 연장수당 시간 -->
                            <input type="text" name="standard_time_overtime[]" value="{{ decimalFormat($list['Standard']['TIME_OVERTIME']) }}">
                        </td>
                        <td>
                            <!-- 연장수당 금액 -->
                            <input type="text" name="standard_pay_overtime[]" value="{{ decimalFormat($list['Standard']['PAY_OVERTIME']) }}">
                        </td>
                        <td>
                            <!-- 휴일수당 시간 -->
                            <input type="text" name="standard_time_holiday[]" value="{{ decimalFormat($list['Standard']['TIME_HOLIDAY']) }}">
                        </td>
                        <td>
                            <!-- 휴일수당 금액 -->
                            <input type="text" name="standard_pay_holiday[]" value="{{ decimalFormat($list['Standard']['PAY_HOLIDAY']) }}">
                        </td>
                        <td>
                            <!-- 야근수당 시간 -->
                            <input type="text" name="standard_time_night[]" value="{{ decimalFormat($list['Standard']['TIME_NIGHT']) }}">
                        </td>
                        <td>
                            <!-- 야근수당 금액 -->
                            <input type="text" name="standard_pay_night[]" value="{{ decimalFormat($list['Standard']['PAY_NIGHT']) }}">
                        </td>
                        <td>
                            <!-- 주휴수당 시간 -->
                            <input type="text" name="standard_time_weekly[]" value="{{ decimalFormat($list['Standard']['ALLOWANCE_WEEK_TIME']) }}">
                        </td>
                        <td>
                            <!-- 주휴수당 금액 -->
                            <input type="text" name="standard_pay_weekly[]" value="{{ decimalFormat($list['Standard']['ALLOWANCE_WEEK_PAY']) }}">
                        </td>
                        <td>
                            <!-- 연차수당 시간 1년미만연차수당포함이다 -->
                            <input type="text" name="standard_time_yearly[]" value="{{ decimalFormat($list['Standard']['ALLOWANCE_YEAR_TIME'] + $list['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_TIME']) }}">
                        </td>
                        <td>
                            <!-- 연차수당 금액 -->
                            <input type="text" name="standard_pay_yearly[]" value="{{ decimalFormat($list['Standard']['ALLOWANCE_YEAR_PAY'] + $list['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR']) }}">
                        </td>
                        <td>
                            <!-- 근로자의날 시간 -->
                            <input type="text" name="standard_time_workers_day[]" value="{{ decimalFormat($list['Standard']['WORKERS_DAY_TIME']) }}">
                        </td>
                        <td>
                            <!-- 근로자의날 금액 -->
                            <input type="text" name="standard_pay_workers_day[]" value="{{ decimalFormat($list['Standard']['WORKERS_DAY_PAY']) }}">
                        </td>
                        <td>
                            <!-- 공휴일 유급휴일 시간 -->
                            <input type="text" name="standard_time_public_rest[]" value="{{ decimalFormat($list['Standard']['PUBLIC_HOLIDAY_TIME']) }}">
                        </td>
                        <td>
                            <!-- 공휴일 유급휴일 금액 -->
                            <input type="text" name="standard_pay_public_rest[]" value="{{ decimalFormat($list['Standard']['PUBLIC_HOLIDAY_PAY']) }}">
                        </td>
                        <td>
                            <!-- 적용합계(근로기준법합계) -->
                            <input type="text" name="standard_total_pay[]" class="standard_total_pay" value="{{ decimalFormat($list['Standard']['PAY_TOTAL']) }}">
                        </td>
                        <td>
                            <!-- 차액(법정제수당) -->
                            <input type="text" name="standard_pay_diff[]" value="{{ decimalFormat($list['Payment'] - $list['Standard']['PAY_TOTAL']) }}">
                        </td>

                        <td>
                            {{-- 지급총액 --}}
                            <input type="text" name="selected_payment[]" class="selected_payment total_payment_voucher_select" value="{{ decimalFormat($list['Payment']) }}">
                            <input type="text" name="selected_payment_standard[]" class="selected_payment standard_total" value="{{ decimalFormat($list['Standard']['PAY_TOTAL']) }}">
                        </td>

                        {{-- 제공자 급여공제내역 --}}
                        <td>
                            {{-- 국민연금 --}}
                            <input type="text" name="worker_nation[]" class="worker_tax writing" value="{{ decimalFormat($list['Tax']['WORKER_NATIONAL']) }}">
                            <input type="text" name="worker_nation_standard[]" class="worker_tax writing standard_tax" value="{{ decimalFormat($list['Tax']['WORKER_NATIONAL_STANDARD']) }}">
                        </td>
                        <td>
                            {{-- 건강보험 --}}
                            <input type="text" name="worker_health[]" class="worker_tax writing" value="{{ decimalFormat($list['Tax']['WORKER_HEALTH']) }}">
                            <input type="text" name="worker_health_standard[]" class="worker_tax writing standard_tax" value="{{ decimalFormat($list['Tax']['WORKER_HEALTH_STANDARD']) }}">
                        </td>
                        <td>
                            {{-- 고용보험 --}}
                            <input type="text" name="worker_employ[]" class="worker_tax writing" value="{{ decimalFormat($list['Tax']['WORKER_EMPLOY']) }}">
                            <input type="text" name="worker_employ_standard[]" class="worker_tax writing standard_tax" value="{{ decimalFormat($list['Tax']['WORKER_EMPLOY_STANDARD']) }}">
                        </td>

                        <td>
                            {{-- 갑근세 --}}
                            <input type="text" name="worker_gabgeunse[]" class="worker_tax writing" value="{{ decimalFormat($list['Tax']['CLASS_A_WAGE']) }}">
                            <input type="text" name="worker_gabgeunse_standard[]" class="worker_tax writing standard_tax" value="{{ decimalFormat($list['Tax']['CLASS_A_WAGE_STANDARD']) }}">
                        </td>

                        <td>
                            {{-- 주민세 --}}
                            <input type="text" name="worker_juminse[]" class="worker_tax writing" value="{{ decimalFormat($list['Tax']['RESIDENT_TAX']) }}">
                            <input type="text" name="worker_juminse_standard[]" class="worker_tax writing standard_tax" value="{{ decimalFormat($list['Tax']['RESIDENT_TAX_STANDARD']) }}">
                        </td>

                        <td>
                            {{-- 공제합계 --}}
                            <input type="text" name="worker_tax_total[]" class="worker_tax_total worker_tax" value="{{ decimalFormat($list['WorkerTaxTotal']) }}">
                            <input type="text" name="worker_tax_total_standard[]" class="worker_tax_total_standard standard_tax" value="{{ decimalFormat($list['WorkerTaxTotal_STANDARD']) }}">
                        </td>

                        {{-- 급여지급 및 이체현황 --}}
                        <td>
                            {{-- 차인지급액(세후) --}}
                            <input type="text" name="worker_tax_after[]" class="worker_tax" value="{{ decimalFormat($list['Payment'] - $list['WorkerTaxTotal']) }}">
                            <input type="text" name="worker_tax_after_standard[]" class="standard_tax" value="{{ decimalFormat($list['Payment'] - $list['WorkerTaxTotal_STANDARD']) }}">
                        </td>
                        <td>
                            <!-- 은행명 -->
                            <input type="text" name="worker_bank[]"  value="{{ $list['User']->bank_name ?? "정보없음" }}">
                        </td>
                        <td>
                            <!-- 계좌번호 -->
                            <input type="text" name="worker_bank_number[]"  value="{{ $list['User']->bank_account_number ?? "정보없음" }}">
                        </td>

                        {{-- 사업주(기관) 세금공제 --}}
                        <td>
                            {{-- 사업수입 (바우처합계+기타청구)-근로기준법합계 --}}
                            <input type="text" name="company_business_pay_total[]" class="company_tax" value="{{ decimalFormat($list['CompanyBusinessTotal']) }}">
                            <input type="text" name="company_business_pay_total_standard[]" class="company_tax standard_tax" value="{{ decimalFormat($list['CompanyBusinessTotal_STANDARD']) }}">
                        </td>
                        <td>
                            <!-- 국민연금기관 -->
                            <input type="text" name="company_tax_nation[]" class="company_tax" value="{{ decimalFormat($list['Tax']['COMPANY_NATIONAL']) }}">
                            <input type="text" name="company_tax_nation_standard[]" class="company_tax standard_tax" value="{{ decimalFormat($list['Tax']['COMPANY_NATIONAL_STANDARD']) }}">
                        </td>
                        <td>
                            <!-- 건강보험기관 -->
                            <input type="text" name="company_tax_health[]" class="company_tax" value="{{ decimalFormat($list['Tax']['COMPANY_HEALTH']) }}">
                            <input type="text" name="company_tax_health_standard[]" class="company_tax standard_tax" value="{{ decimalFormat($list['Tax']['COMPANY_HEALTH_STANDARD']) }}">
                        </td>
                        <td>
                            <!-- 고용보험기관 -->
                            <input type="text" name="company_tax_employ[]" class="company_tax" value="{{ decimalFormat(round($list['Tax']['COMPANY_EMPLOY'])) }}">
                            <input type="text" name="company_tax_employ_standard[]" class="company_tax standard_tax" value="{{ decimalFormat(round($list['Tax']['COMPANY_EMPLOY_STANDARD'])) }}">
                        </td>
                        <td>
                            <!-- 산재보험기관 -->
                            <input type="text" name="company_tax_industry[]" class="company_tax" value="{{ decimalFormat($list['Tax']['COMPANY_INDUSTRY']) }}">
                            <input type="text" name="company_tax_industry_standard[]" class="company_tax standard_tax" value="{{ decimalFormat(round($list['Tax']['COMPANY_INDUSTRY_STANDARD'])) }}">
                        </td>
                        <td>
                            <!-- 퇴직적립금 -->
                            <input type="text" name="company_requirement[]" class="company_tax" value="{{ decimalFormat($list['Retirement']) }}">
                            <input type="text" name="company_requirement_standard[]" class="company_tax standard_tax" value="{{ decimalFormat(round($list['Retirement_STANDARD'])) }}">
                        </td>
                        <td>
                            <!-- 반납승인(사업주) -->
                            <input type="text" name="company_return_total[]" class="" value="{{ decimalFormat(($list['Return']['COUNTRY']['PAYMENT_TOTAL']
                            + $list['Return']['CITY']['PAYMENT_TOTAL']) * 0.236) }}">
                        </td>
                        <td>
                            <!-- 사업주부담합계 -->
                            <input type="text" name="company_tax_total[]" class="company_tax" value="{{ decimalFormat($list['CompanyTaxTotal']) }}">
                            <input type="text" name="company_tax_total_standard[]" class="company_tax standard_tax" value="{{ decimalFormat($list['CompanyTaxTotal_STANDARD']) }}">
                        </td>
                        <td>
                            <!-- 차감 사업주 수익(사업수입-사업주공제) -->
                            <input type="text" name="company_sub_payment[]" class="company_tax" value="{{ decimalFormat($list['CompanyBusinessTotal'] - $list['CompanyTaxTotal']) }}">
                            <input type="text" name="company_sub_payment_standard[]" class="standard_tax company_tax" value="{{ decimalFormat($list['CompanyBusinessTotal_STANDARD'] - $list['CompanyTaxTotal_STANDARD']) }}">
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="91"></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
                </div>
            </form>
        </article> <!-- article list_contents end -->
        @endif

        <article id="list_bottom">
            <div class="form-wrap">
                <form action="" id="paymentsSaveForm" name="paymentsSaveForm" method="post">
                    @csrf
                    <input type="radio" name="save" value="voucher" id="voucher">
                    <label for="voucher">바우처 기준 지급</label>
                    <input type="radio" name="save" value="standard" id="standard">
                    <label for="standard">근로기준법 기준 지급</label>
                    <input type="hidden" name="data" value="">
                    <button class="saveAction" type="button">확정하기</button>
                    {{--<button class="excelDownload" type="button">엑셀 다운받기</button>--}}
                </form>
            </div>
        </article> <!-- article list_bottom end -->

    </section>

    @if ($lists)
    <div class="arrow-btn">
        <button type="button">
            <svg width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd" />
            </svg>
        </button>
        <button type="button">
            <svg width="30px" height="30px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    @endif

    @if (isset($_POST['pay_per_hour']))
        <div class="body-wrapper"></div>
    @endif

    <script src="/js/CalcInputChange.js"></script>
    <script>

        @if (isset($_POST['pay_per_hour']))

            window.onload = function () {
                $(".body-wrapper").remove();
                $("#list_contents input").attr("readonly", true);
        };

        @endif

        function paymentAction(f) {

            var error = "";

            error = f.from_date.value == "" ? "급여기준연월을 입력해주세요." : error;
            error = f.public_officers_holiday_check.value == "" ? "관공서 공휴일 적용여부를 선택해주세요" : error;
            error = f.pay_per_hour.value == "" ? "시간당 인건비 단가를 입력해주세요" : error;
            error = f.pay_hour.value == "" ? "기본시급을 입력해주세요" : error;
            error = f.pay_over_time.value == "" ? "연장수당 비율을 입력해주세요" : error;
            error = f.pay_holiday.value == "" ? "휴일수당 비율을 입력해주세요" : error;
            error = f.pay_holiday_over_time.value == "" ? "휴일연장수당 비율을 입력해주세요" : error;
            error = f.pay_night.value == "" ? "야간수당 비율을 입력해주세요" : error;
            error = f.pay_annual.value == "" ? "연차수당 비율을 입력해주세요" : error;
            error = f.pay_one_year_less_annual.value == "" ? "1년 미만자 연차수당 비율을 입력해주세요" : error;
            error = f.pay_public_holiday.value == "" ? "공휴일의 유급휴일임금 비율을 입력해주세요" : error;
            error = f.pay_workers_day.value == "" ? "근로자의 날 수당 비율을 입력해주세요" : error;

            if (f.week_pay_apply_check.checked) {
                if (f.week_pay_apply_type.value == "") error = "주휴수당 계산 옵션을 선택해주세요";
                if (f.week_pay_selector.value == "" || !f.week_pay_selector.value) error = "주휴수당 계산 주 근무일 옵션을 선택해주세요";
            }

            if (f.year_pay_apply_check.checked) {
                if (f.year_pay_apply_type.value == "") error = "연차수당 계산 옵션을 선택해주세요";
                if (f.year_pay_selector.value == "" || !f.year_pay_selector.value) error = "연차수당 계산 주 근무일 옵션을 선택해주세요";
            }

            if (f.one_year_less_annual_pay_check.checked) {
                if (f.one_year_less_annual_pay_type.value == "") error = "1년 미만자 연차 수당 계산 옵션을 선택해주세요";
                if (f.one_year_less_annual_pay_selector.value == "" || !f.one_year_less_annual_pay_selector.value) error = "1년 미만자 연차 수당 계산 주 근무일 옵션을 선택해주세요";
            }

            if (f.public_allowance_check.checked) {
                if (f.public_allowance_selector.value == "") error = "공휴일의 유급휴일임금 계산 옵션을 선택해주세요";
                if (f.public_allowance_day_selector.value == "" || !f.public_allowance_day_selector.value) error = "공휴일의 유급휴일임금 계산 주 근무일 옵션을 선택해주세요";
            }

            if (f.workers_day_allowance_check.checked) {
                if (f.workers_day_allowance_day_selector.value == "" || !f.workers_day_allowance_day_selector.value) error = "근로자의 날 수당 계산 주 근무일 옵션을 선택해주세요";
            }

            if (f.voucher_pay_total.value == 1) {
                if (f.voucher_holiday_pay_fixing.value == "") error = "휴일수당 고정값을 입력해주세요.";
            }

            // if (!f.retirement_saving_pay_type.value) {
            //     error = "퇴직적립금 적립방식을 선택해주세요";
            // }


            if (f.employ_tax_selector.value == "" || !f.employ_tax_selector.value) {
                error = "고용보험료율을 선택해주세요.";
            }

            if (f.industry_tax_percentage.value == "" || !f.industry_tax_percentage.value) {
                error = "산재요율을 선택해주세요.";
            }

            if (error != "") {
                alert(error);
                return false;
            }

            $("#main").append("<div class='body-wrapper'></div>");

            return true;
        }

        $(".saveAction").on("click", function () {
            var f = document.paymentsSaveForm;
            var lists = document.payment_calc;

            f.data.value = $(lists).serialize();
            f.action = "/salary/calc/save";

            f.submit();
        });


        $("input[name='voucher_pay_total']").on("change", function() {
            if ($(this).val() == 1) {
                $("input[name='voucher_holiday_pay_fixing']").prop("disabled", false);
                $("input[name='voucher_holiday_pay_hour_per_price']").prop("disabled", true);
            } else {
                $("input[name='voucher_holiday_pay_fixing']").prop("disabled", true);
                $("input[name='voucher_holiday_pay_hour_per_price']").prop("disabled", false);
            }
        });

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
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소날짜 설정해주기
                    if (idx%2 == 0) {
                        $(datepicker_selector[idx+1]).datepicker({
                            minDate: new Date(dateText),
                            dateFormat:"yyyy-mm",
                            clearButton: false,
                            autoClose: true,
                        })
                    }

                }
            });

        });


        $(document).ready(function() {
            $('#calc_result').DataTable({
                searching: false,
                lengthChange: false,
                scrollX:        "2400px",
                scrollY:        "600px",
                @if (!collect($lists)->isEmpty())
                        @endif
                scrollCollapse: false,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 4,
                }
            });
        } );


        $("a[href='/salary/calc']").parent("li").addClass("on");

    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


@endsection
