@extends("layouts/layout")

@section("title")
    급여 재정산
@endsection

@section("content")
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

    </style>

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>급여 재정산</h1>
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
                                    <input type="radio" id="public_officers_holiday_check_1" name="public_officers_holiday_check" value="1" disabled
                                        {{ isset($conditions->public_officers_holiday_check) ? $conditions->public_officers_holiday_check == 1 ? "checked" : "" : "" }}
                                    >
                                    <label for="public_officers_holiday_check_1" class="m-right-20">휴일적용</label>
                                    <input type="radio" id="public_officers_holiday_check_2" name="public_officers_holiday_check" value="2" disabled
                                        {{ isset($conditions->public_officers_holiday_check) ? $conditions->public_officers_holiday_check == 2 ? "checked" : "" : "" }}
                                    >
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
                                    <input type="radio" id="week_pay_selector_1" name="week_pay_selector" value="5"
                                       {{ isset($week_pay_selector) ? $week_pay_selector == "5" ? "checked" : "" : "" }}
                                    >
                                    <label for="week_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="week_pay_selector_2" name="week_pay_selector" value="6"
                                        {{ isset($week_pay_selector) ? $week_pay_selector == "6" ? "checked" : "" : "" }}
                                    >
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
                                    <input type="radio" id="year_pay_selector_1" name="year_pay_selector" value="5"
                                           {{ isset($year_pay_selector) ? $year_pay_selector == "5" ? "checked" : "" : "" }}
                                    >
                                    <label for="year_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="year_pay_selector_2" name="year_pay_selector" value="6"
                                        {{ isset($year_pay_selector) ? $year_pay_selector == "6" ? "checked" : "" : "" }}
                                    >
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
                                        {{ isset($one_year_less_annual_pay_selector) ? $one_year_less_annual_pay_selector == 5 ? "checked" : "" : "" }}
                                    >
                                    <label for="one_year_less_annual_pay_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="one_year_less_annual_pay_selector_2" name="one_year_less_annual_pay_selector" value="6"
                                        {{ isset($one_year_less_annual_pay_selector) ? $one_year_less_annual_pay_selector == 6 ? "checked" : "" : "" }}
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
                                </div>
                                <div>
                                    <input type="radio" id="public_allowance_day_selector_1" name="public_allowance_day_selector" value="5" disabled
                                        {{ isset($conditions->public_allowance_day_selector) ? $conditions->public_allowance_day_selector == 5 ? "checked" : "" : "" }}
                                    >
                                    <label for="public_allowance_day_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="public_allowance_day_selector_2" name="public_allowance_day_selector" value="6" disabled
                                        {{ isset($conditions->public_allowance_day_selector) ? $conditions->public_allowance_day_selector == 6 ? "checked" : "" : "" }}
                                    >
                                    <label for="public_allowance_day_selector_2">주 6일</label>
                                </div>
                                <div>
                                    <table class="table-style-default1">
                                        <tr>
                                            <th>
                                                근무→근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_1" id="timetable_1_1" value="1" disabled
                                                    {{ isset($conditions->timetable_1) ? $conditions->timetable_1 == 1 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_1_1">지급</label>
                                                <input type="radio" name="timetable_1" id="timetable_1_2" value="2" disabled
                                                    {{ isset($conditions->timetable_1) ? $conditions->timetable_1 == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_1_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→미근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_2" id="timetable_2_1" value="1" disabled
                                                    {{ isset($conditions->timetable_2) ? $conditions->timetable_2 == 1 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_2_1">지급</label>
                                                <input type="radio" name="timetable_2" id="timetable_2_2" value="2" disabled
                                                    {{ isset($conditions->timetable_2) ? $conditions->timetable_2 == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_2_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                근무→미근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_3" id="timetable_3_1" value="1" disabled
                                                    {{ isset($conditions->timetable_3) ? $conditions->timetable_3 == 1 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_3_1">지급</label>
                                                <input type="radio" name="timetable_3" id="timetable_3_2" value="2" disabled
                                                    {{ isset($conditions->timetable_3) ? $conditions->timetable_3 == 2 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_3_2">미지급</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→근무
                                            </th>
                                            <td>
                                                <input type="radio" name="timetable_4" id="timetable_4_1" value="1" disabled
                                                    {{ isset($conditions->timetable_4) ? $conditions->timetable_4 == 1 ? "checked" : "" : "" }}
                                                >
                                                <label for="timetable_4_1">지급</label>
                                                <input type="radio" name="timetable_4" id="timetable_4_2" value="2" disabled
                                                    {{ isset($conditions->timetable_4) ? $conditions->timetable_4 == 2 ? "checked" : "" : "" }}
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
                                        {{ isset($workers_day_allowance_day_selector) ? $workers_day_allowance_day_selector == 5 ? "checked" : "" : "checked" }}
                                    >
                                    <label for="workers_day_allowance_day_selector_1" class="m-right-20">주 5일</label>
                                    <input type="radio" id="workers_day_allowance_day_selector_2" name="workers_day_allowance_day_selector" value="6"
                                        {{ isset($workers_day_allowance_day_selector) ? $workers_day_allowance_day_selector == 6 ? "checked" : "" : "" }}
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
            <article id="list_contents" class="m-top-20" style="overflow-x: auto">
                <form action="" id="payment_calc" name="payment_calc" method="post">

                    <div class="">
                        <table id="calc_result" class=" stripe row-border order-column">
                            <thead class="thead-origin">
                            <tr class="table-top">
                                <th rowspan="2" colspan="4">

                                </th>
                                <th rowspan="2" colspan="7" class="b-right-b7">
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
                            @foreach ($lists as $list)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ regexName($list->provider_key) }}
                                </td>
                                <td>
                                    {{ $list->provider_key }}
                                </td>
                                <td>
                                    {{regCheck($list->provider_reg_check) }}
                                </td>
                                <td>
                                    {{ $list->join_date }}
                                </td>
                                <td>
                                    {{ $list->resign_date }}
                                </td>
                                <td>
                                    {{ $list->nation_ins }}
                                </td>
                                <td>
                                    {{ $list->health_ins }}
                                </td>
                                <td>
                                    {{ $list->employ_ins }}
                                </td>
                                <td>
                                    {{ $list->retirement }}
                                </td>
                                <td>
                                    {{ $list->year_rest_count }}
                                </td>

                                <!-- 국비 -->
                                <td>
                                    {{ $list->nation_day_count }}
                                </td>
                                <td>
                                    {{ $nation_total = number_format($list->nation_total_time * $pay_per_hour + ($pay_per_hour / 2 * ($list->nation_holiday_time + $list->nation_night_time))) }}
                                </td>
                                <td>
                                    {{ $nation_add_total = number_format($pay_per_hour / 2 * ($list->nation_holiday_time + $list->nation_night_time)) }}
                                </td>
                                <td>
                                    {{ $list->nation_total_time }}
                                </td>
                                <td>
                                    {{ $list->nation_holiday_time }}
                                </td>
                                <td>
                                    {{ $list->nation_night_time }}
                                </td>

                                <!-- 시도비 -->
                                <td>
                                    {{ $list->city_day_count }}
                                </td>
                                <td>
                                    {{ $city_total = number_format($list->city_total_time * $pay_per_hour + ($pay_per_hour / 2 * ($list->city_holiday_time + $list->city_night_time))) }}
                                </td>
                                <td>
                                    {{ $city_add_total = number_format($pay_per_hour / 2 * ($list->city_holiday_time + $list->city_night_time)) }}
                                </td>
                                <td>
                                    {{ $list->city_total_time }}
                                </td>
                                <td>
                                    {{ $list->city_holiday_time }}
                                </td>
                                <td>
                                    {{ $list->city_night_time }}
                                </td>


                                <!-- 승인합계 -->
                                <td>
                                    {{ $list->voucher_total_day_count }}
                                </td>
                                <td>
                                    {{ $voucher_total = number_format(removeComma($nation_total) + removeComma($city_total)) }}
                                </td>
                                <td>
                                    {{ $voucher_add_total = number_format(removeComma($nation_add_total) + removeComma($city_add_total)) }}
                                </td>
                                <td>
                                    {{ $list->voucher_total_time }}
                                </td>
                                <td>
                                    {{ $list->voucher_total_time_holiday }}
                                </td>
                                <td>
                                    {{ $list->voucher_total_time_night }}
                                </td>
                                <td>
                                    {{ number_format(removeComma($voucher_total) - removeComma($voucher_add_total)) }}
                                </td>
                                <td>
                                    {{ number_format(($list->nation_holiday_time + $list->city_holiday_time) * ($pay_per_hour / 2)) }}
                                </td>
                                <td>
                                    {{ number_format(($list->nation_night_time + $list->city_night_time) * ($pay_per_hour / 2)) }}
                                </td>
                                <td>
                                    {{ $list->voucher_detach_payment_difference }}
                                </td>

                                <!-- 반납승인내역 -->
                                <td>
                                    {{ $list->voucher_return_nation_day_count }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_nation_pay }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_nation_time }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_city_day_count }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_city_pay }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_city_time }}
                                </td>

                                <td>
                                    {{ $list->voucher_return_nation_day_count + $list->voucher_return_city_day_count }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_total_pay }}
                                </td>
                                <td>
                                    {{ $list->voucher_return_total_time }}
                                </td>

                                <!-- 바우처상 지급합계 -->
                                <td>
                                    {{ number_format($list->voucher_payment) }}
                                </td>

                                <td>
                                    {{ $list->standard_basic_time }}
                                </td>
                                <td>
                                    {{ $reBasicPayment = number_format($list->standard_basic_time * $pay_hour) }}
                                </td>

                                <td>
                                    {{ $list->standard_over_time }}
                                </td>
                                <td>
                                    {{ $reOvertimePayment = number_format(($list->standard_over_time * ($pay_hour * 1.5)) * ($pay_over_time/100)) }}
                                </td>

                                <td>
                                    {{ $list->standard_holiday_time }}
                                </td>
                                <td>
                                    {{ $reHolidayPayment = number_format(($list->standard_holiday_time * ($pay_hour * 1.5)) * ($pay_holiday/100)) }}
                                </td>

                                <td>
                                    {{ $list->standard_night_time }}
                                </td>

                                <td>
                                    {{ $reNightPayment = number_format($list->standard_night_time * ($pay_hour * 1.5) * ($pay_night/100)) }}
                                </td>

                                <td>
                                    {{ $list->standard_weekly_time }}
                                </td>

                                <td>
                                    @if ($week_pay_apply_check == 1)
                                        @switch ($week_pay_apply_type)
                                            @case ("all")
                                                {{ $reWeekPayment = number_format(($list->standard_weekly_time * $pay_hour)) }}
                                            @break
                                            @case ("basic60")
                                                @if ($list->standard_weekly_time >= 60)
                                                {{ $reWeekPayment = number_format(($list->standard_weekly_time * $pay_hour)) }}
                                                @else
                                                    0
                                                @endif
                                            @case ("basic65")
                                                @if ($list->standard_weekly_time >= 65)
                                                    {{ $reWeekPayment = number_format(($list->standard_weekly_time * $pay_hour)) }}
                                                @else
                                                    0
                                                @endif
                                            @break
                                        @endswitch
                                    @endif
                                </td>

                                <td>
                                    {{ $list->standard_yearly_time }}
                                </td>
                                <td>
                                    @if ($year_pay_apply_check == 1)

                                        @if ($year_pay_apply_type == "all")
                                            {{ $reYearPayment = number_format($list->standard_yearly_time * $pay_hour) }}
                                        @elseif ($year_pay_apply_type == "basic60" && $list->standard_yearly_time >= 60)
                                            {{ $reYearPayment = number_format($list->standard_yearly_time * $pay_hour) }}
                                        @elseif ($year_pay_apply_type == "basic65" && $list->standard_yearly_time >= 65)
                                            {{ $reYearPayment = number_format($list->standard_yearly_time * $pay_hour) }}
                                        @endif

                                    @endif
                                </td>

                                <td>
                                    {{ $list->standard_workers_day_time }}
                                </td>
                                <td>
                                    @if ($workers_day_allowance_check == 1)
                                        {{ $reWorkersDayPayment = $list->standard_workers_day_time * ($pay_hour * 1.5) }}
                                    @endif
                                </td>

                                <td>
                                    {{ $list->standard_public_day_time }}
                                </td>
                                <td>
                                    {{ $rePublicPayment = $list->standard_public_day_time * $pay_hour }}
                                </td>

                                <td>
                                    {{
                                        $reStandardPayment = number_format(removeComma($reBasicPayment) + removeComma($reOvertimePayment)
                                        + removeComma($reHolidayPayment) + removeComma($reNightPayment)
                                        + removeComma($reWeekPayment) + removeComma($reYearPayment)
                                        + removeComma($reWorkersDayPayment) + removeComma($rePublicPayment))
                                    }}
                                </td>

                                <td>
                                    {{ number_format(removeComma($list->voucher_payment) - removeComma($reStandardPayment)) }}
                                </td>

                                <td>
                                    {{ number_format($list->voucher_payment) }}
                                </td>
                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_national_price)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_national_price)) }}</p>
                                </td>
                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_health_price)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_health_price)) }}</p>
                                </td>
                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_employ_price)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_employ_price)) }}</p>
                                </td>
                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_gabgeunse_price)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_gabgeunse_price)) }}</p>
                                </td>

                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_gabgeunse_price * 0.1)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_gabgeunse_price * 0.1)) }}</p>
                                </td>

                                <td>
                                    <p class="tax-voucher">{{ number_format(round($list->tax_voucher_total)) }}</p>
                                    <p class="tax-standard">{{ number_format(round($list->tax_standard_total)) }}</p>
                                </td>

                                <td>
                                    <p class="tax-voucher">{{ number_format(removeComma($list->voucher_payment) - $list->tax_voucher_total) }}</p>
                                    <p class="tax-standard">{{ number_format(removeComma($list->voucher_payment) - $list->tax_standard_total) }}</p>
                                </td>

                                <td>
                                    {{ $list->bank_name }}
                                </td>
                                <td>
                                    {{ $list->bank_number }}
                                </td>
                                <td>
                                    {{ "사업수입" }}
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->tax_voucher_national_price) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->tax_standard_national_price) }}
                                    </p>
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->tax_voucher_health_price) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->tax_standard_health_price) }}
                                    </p>
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->tax_voucher_employ_company_price) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->tax_standard_employ_company_price) }}
                                    </p>
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->tax_voucher_industry_company_price) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->tax_standard_industry_company_price) }}
                                    </p>
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->retirement_voucher) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->retirement_standard) }}
                                    </p>
                                </td>

                                <td>
                                    {{ number_format($list->company_return_confirm) }}
                                </td>

                                <td>
                                    <p class="tax-voucher">
                                        {{ number_format($list->company_return_pay_total_voucher) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->company_return_pay_total_standard) }}
                                    </p>
                                </td>

                                <td>

                                    <p class="tax-voucher">
                                        {{ number_format($list->company_return_pay_total_voucher) }}
                                    </p>
                                    <p class="tax-standard">
                                        {{ number_format($list->company_return_pay_total_standard) }}
                                    </p>

                                </td>

                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                </form>
            </article> <!-- article list_contents end -->

            <div class="submit-btn-wrap" style="float: right;">
                <form action="/recalc/save" method="post">
                    @csrf
                    <input type="hidden" name="lists" value="{{ json_encode($lists) }}">
                    <input type="hidden" name="request" value="{{ json_encode($request) }}">
                    <input type="hidden" name="conditions" value="{{ json_encode($conditions) }}">
                    <input type="radio" name="save_type_selector" id="save_type_voucher" value="voucher">
                    <label for="save_type_voucher">
                        바우처 기준 지급
                    </label>
                    <input type="radio" name="save_type_selector" id="save_type_standard" value="standard">
                    <label for="save_type_standard">
                        근로기준법 기준 지급
                    </label>

                    <button>저장하기</button>
                </form>
            </div>
        @endif

        <style>
            .tax-standard {
                display: none;
            }
        </style>

    </section>

    @if (isset($_POST['pay_per_hour']))
        <div class="body-wrapper"></div>
    @endif

    <script src="/js/CalcInputChange.js"></script>
    <script>

        $("aside .sub-menu__list ul li").removeClass("on");
        $("aside .sub-menu__list ul.ul_2depth li[data-uri='/recalc']").addClass("on")

        @if (isset($_POST['pay_per_hour']))

        window.onload = function () {
            $(".body-wrapper").remove();
            $("#list_contents input").attr("readonly", true);
        };

        @endif

        function paymentAction(f) {

            var error = "";

            error = f.from_date.value == "" ? "급여기준연월을 입력해주세요." : error;
            // error = f.public_officers_holiday_check.value == "" ? "관공서 공휴일 적용여부를 선택해주세요" : error;
            error = f.pay_per_hour.value == "" ? "시간당 인건비 단가를 입력해주세요" : error;
            error = f.pay_hour.value == "" ? "기본시급을 입력해주세요" : error;
            error = f.pay_over_time.value == "" ? "연장수당 비율을 입력해주세요" : error;
            error = f.pay_holiday.value == "" ? "휴일수당 비율을 입력해주세요" : error;
            error = f.pay_holiday_over_time.value == "" ? "휴일연장수당 비율을 입력해주세요" : error;
            error = f.pay_night.value == "" ? "야간수당 비율을 입력해주세요" : error;
            error = f.pay_annual.value == "" ? "연차수당 비율을 입력해주세요" : error;
            error = f.pay_one_year_less_annual.value == "" ? "1년 미만자 연차수당 비율을 입력해주세요" : error;
            // error = f.pay_public_holiday.value == "" ? "공휴일의 유급휴일임금 비율을 입력해주세요" : error;
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

            // if (f.public_allowance_check.checked) {
            //     if (f.public_allowance_selector.value == "") error = "공휴일의 유급휴일임금 계산 옵션을 선택해주세요";
            //     if (f.public_allowance_day_selector.value == "" || !f.public_allowance_day_selector.value) error = "공휴일의 유급휴일임금 계산 주 근무일 옵션을 선택해주세요";
            // }

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
                scrollX:        "9000px",
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

    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


@endsection
