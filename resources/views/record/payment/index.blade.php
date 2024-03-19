@extends("layouts/layout")

@section("title")
    급여이력조회
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    <style>

        .table-style-default1 tr td,
        .table-style-default1 tr th
        {
            border-top: none !important;
            text-align: left !important;
            padding: 3px 0 !important;
        }


    </style>

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <article id="list_head">

                <div class="head-info">
                    <h1>급여이력조회</h1>
                </div>

            </article>

            <div class="search-wrap">
                <form action="" method="post" name="payment_action" onsubmit="return false">
                    @csrf
                    <table class="m-bottom-20">
                        <thead>
                        <tr>
                            <th>급여기준연월</th>
                            <td colspan="3">
                                <input type="text" readonly name="from_date" id="from_date" class="input-datepicker" autocomplete="off" value="{{ $_GET['from_date'] ?? "" }}">
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
                                <p>
                                    {{ isset($condition->public_officers_holiday_check) ? $condition->public_officers_holiday_check == 1 ? "휴일적용" : "평일적용" : ""  }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p>
                                    시간당인건비단가
                                </p>
                            </th>
                            <td>
                                <p>
                                    {{ $condition->pay_per_hour ?? "" }}
                                </p>
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
                                            <input type="text" name="pay_hour" value="{{ $condition->pay_hour ?? "" }}" disabled="disabled">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_over_time" value="{{ $condition->pay_over_time ?? "" }}" disabled="disabled">
                                        </td>
                                        <td>
                                            <input type="text" name="pay_holiday" value="{{ $condition->pay_holiday ?? "" }}" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="pay_holiday_over_time" value="{{ $condition->pay_holiday_over_time ?? "" }}" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="pay_night" value="{{ $condition->pay_night ?? "" }}" disabled>
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
                                            <input type="text" name="pay_annual" value="{{ $condition->pay_annual ?? "" }}" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="pay_one_year_less_annual" value="{{ $condition->pay_one_year_less_annual ?? "" }}" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="pay_public_holiday" value="{{ $condition->pay_public_holiday ?? "" }}" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="pay_workers_day" value="{{ $condition->pay_workers_day ?? "" }}" disabled>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>주휴수당 계산</th>
                            <td class="td_01" colspan="3">
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                @if (isset($condition->week_pay_apply_check))
                                    {{ nullCheck($condition->week_pay_apply_check ?? 0)  }},
                                    {{ applyTypeConvertKor($condition->week_pay_apply_type ?? "") }},
                                    {{ applyTypeWorkDay($condition->week_pay_selector ?? "") }}
                                @else
                                    미적용
                                @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">연차수당 계산</p>
                            </th>
                            <td class="td_01">
                                <div>
                                    @if (isset($condition->year_pay_apply_check))
                                        {{ nullCheck($condition->year_pay_apply_check ?? 0)  }},
                                        {{ applyTypeConvertKor($condition->year_pay_apply_type ?? "") }},
                                        {{ applyTypeWorkDay($condition->year_pay_selector ?? "") }}
                                    @else
                                        미적용
                                    @endif
                                </div>
                            </td>
                            <th>
                                <p class="t-left">
                                    1년 미만자 연차<br>
                                    수당 계산
                                </p>
                            </th>
                            <td class="td_01">

                                <div>
                                    @if (isset($condition->one_year_less_annual_pay_check))
                                        {{ nullCheck($condition->one_year_less_annual_pay_check ?? 0)  }},
                                        {{ applyTypeConvertKor($condition->one_year_less_annual_pay_type ?? "") }},
                                        {{ applyTypeWorkDay($condition->one_year_less_annual_pay_selector ?? "") }}
                                    @else
                                        미적용
                                    @endif
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

                                </div>
                                <div>
                                    @if (isset($condition->public_allowance_check))
                                        {{ nullCheck($condition->one_year_less_annual_pay_check ?? 0)  }},
                                        {{ applyTypeConvertKor($condition->public_allowance_selector ?? "") }},
                                        {{ applyTypeWorkDay($condition->public_allowance_day_selector ?? "") }}
                                    @else
                                        미적용
                                    @endif
                                </div>
                                <div>
                                    <table class="table-style-default1">
                                        <tr>
                                            <th>
                                                근무→근무
                                            </th>
                                            <td>
                                                {{ isset($condition->timetable_1) ? $condition->timetable_1 == 1 ? "지급" : "미지급" : "미지급" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→미근무
                                            </th>
                                            <td>
                                                {{ isset($condition->timetable_2) ? $condition->timetable_2 == 1 ? "지급" : "미지급" : "미지급" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                근무→미근무
                                            </th>
                                            <td>
                                                {{ isset($condition->timetable_3) ? $condition->timetable_3 == 1 ? "지급" : "미지급" : "미지급" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                미근무→근무
                                            </th>
                                            <td>
                                                {{ isset($condition->timetable_4) ? $condition->timetable_4 == 1 ? "지급" : "미지급" : "미지급" }}
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
                                @if (isset($condition->workers_day_allowance_check))
                                    {{ nullCheck($condition->workers_day_allowance_check ?? 0)  }},
                                    {{ applyTypeWorkDay($condition->workers_day_allowance_day_selector ?? "") }}
                                @else
                                    미적용
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p class="t-left">
                                    바우처상 지급합계
                                </p>
                            </th>
                            <td>
                                @if (isset($condition->voucher_pay_total) && $condition->voucher_pay_total == 1)
                                    휴일수당 고정값, {{ number_format($condition->voucher_holiday_pay_fixing ?? 0) }}원
                                @else
                                    휴일수당 고정값(원) {{ $condition->voucher_holiday_pay_hour_per_price ?? 0 }}
                                @endif
                            </td>
                            <th>
                                <p class="t-left">
                                    예외결제<br>포함여부
                                </p>
                            </th>
                            <td>

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
                                    @switch($condition->retirement_saving_pay_type ?? "")
                                        @case ("a")
                                            법적적립요율 A형 (제공인력급여총액/12)
                                        @break;
                                        @case ("b")
                                            법적적립요율 B형 (제공인력급여총액 * 0.083)
                                        @break
                                        @case("company")
                                            사업장적립요율 {{ $condition->retirement_saving_pay_company_percentage ?? "" }}
                                        @break
                                        @case("fix")
                                            월 고정액
                                        @break
                                        @default
                                            해당없음
                                        @break
                                    @endswitch
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
                                    {{ isset($condition->tax_nation_selector) && ($condition->tax_nation_selector == "percentage") ? "비율" : "금액" }}
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">건강보험</span>
                                    {{ isset($condition->tax_health_selector) && $condition->tax_health_selector == "percentage" ? "비율" : "금액" }}
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">고용보험</span>
                                    {{ isset($condition->tax_employ_selector) && $condition->tax_employ_selector == "percentage" ? "비율" : "금액" }}
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">산재보험</span>
                                    {{ isset($condition->tax_industry_selector) && $condition->tax_industry_selector == "percentage" ? "비율" : "금액" }}
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <span style="width: 70px; display:inline-block">갑근세</span>
                                    {{ isset($condition->tax_gabgeunse_selector) && $condition->tax_gabgeunse_selector == "percentage" ? "비율" : "금액" }}
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
                                @switch($condition->employ_tax_selector ?? "")
                                    @case ("basic")
                                    기본(0.80%)
                                    @break
                                    @case("150under")
                                    150인 미만기업(1.05%)
                                    @break
                                    @case("150over")
                                    150인 이상 우선지원대상기업(1.25%)
                                    @break
                                    @case("1000under")
                                    150인~1000인 미만 기업(1.45%)
                                    @break
                                    @case("1000over")
                                    1,000인 이상 기업 / 국가지방자치단체(1.65%)
                                    @break
                                    @default
                                    @break
                                @endswitch
                            </td>
                            <th>
                                <p class="t-left">
                                    산재요율
                                </p>
                            </th>
                            <td>
                                {{ $condition->industry_tax_percentage ?? "" }}%
                            </td>
                        </tr>
                        </thead>
                    </table>

                    <div class="btn-wrap">
{{--                        <button class="btn-black">--}}
{{--                            계산하기--}}
{{--                        </button>--}}
                    </div>

                </form>
            </div>

        </article> <!-- article list_head end -->


        @if ($lists)
            <article id="list_contents" class="m-top-20" style="overflow-x: auto; height: 1000px;">


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
                                    @foreach($total as $total_key => $total_value)
                                        <th>
                                            {{ number_format($total_value) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            @endif
                            <tbody>
                            @forelse ($lists as $list)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $list->provider_name }}
                                    </td>
                                    <td>
                                        {{ $list->provider_key }}
                                    </td>
                                    <td>
                                        {{ $list->provider_reg_check == 1 ? "미등록" : "" }}
                                    </td>
                                    <td>
                                        {{ $list->join_date ?? "" }}
                                    </td>
                                    <td>
                                        {{ $list->resign_date ?? "" }}
                                    </td>
                                    <td>
                                        {{ $list->nation_ins ?? "미가입" }}
                                    </td>
                                    <td>
                                        {{ $list->health_ins ?? "미가입" }}
                                    </td>
                                    <td>
                                        {{ $list->employ_ins ?? "미가입" }}
                                    </td>
                                    <td>
                                        {{ $list->retirement ?? "미가입" }}
                                    </td>
                                    <td>
                                        {{ $list->year_rest_count }}
                                    </td>

                                    <!-- 국비 -->
                                    <td>
                                        {{ $list->nation_day_count }}
                                    </td>
                                    <td>
                                        {{ $list->nation_confirm_payment }}
                                    </td>
                                    <td>
                                        {{ $list->nation_add_payment }}
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
                                    <td>
                                        {{ $list->city_day_count }}
                                    </td>
                                    <td>
                                        {{ $list->city_confirm_payment }}
                                    </td>
                                    <td>
                                        {{ $list->city_add_payment }}
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
                                    <td>
                                        {{ $list->voucher_total_day_count }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_total_confirm_payment }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_total_confirm_payment_add }}
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
                                        {{ $list->voucher_detach_payment_basic }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_detach_payment_holiday }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_detach_payment_night }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_detach_payment_difference }}
                                    </td>
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
                                        {{ $list->voucher_return_total_day_count }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_return_total_pay }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_return_total_time }}
                                    </td>
                                    <td>
                                        {{ $list->voucherPaymentFromPaymentVouchers }}
                                    </td>

                                    <!-- 법정시뮬레이션(근로기준법) -->
                                    <td>
                                        {{ $list->standard_basic_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_basic_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_over_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_over_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_holiday_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_holiday_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_night_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_night_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_weekly_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_weekly_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_yearly_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_yearly_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_workers_day_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_workers_day_payment }}
                                    </td>
                                    <td>
                                        {{ $list->standard_public_day_time }}
                                    </td>
                                    <td>
                                        {{ $list->standard_public_day_payment }}
                                    </td>
                                    <td>
                                        {{ $list->StandardPaymentFromStandardTable }}
                                    </td>
                                    <td>
                                        {{ $list->voucher_sub_standard_payment }}
                                    </td>
                                    <td>
                                        {{ $list->voucherPaymentFromStandardTable }}
                                    </td>

                                    <!-- 공제 -->
                                    <td>
                                        {{ $list->tax_nation_pension }}
                                    </td>
                                    <td>
                                        {{ $list->tax_health }}
                                    </td>
                                    <td>
                                        {{ $list->tax_employ }}
                                    </td>
                                    <td>
                                        {{ $list->tax_gabgeunse }}
                                    </td>
                                    <td>
                                        {{ $list->tax_joominse }}
                                    </td>
                                    <td>
                                        {{ $list->tax_total }}
                                    </td>
                                    <td>
                                        {{ $list->tax_sub_payment }}
                                    </td>
                                    <td>
                                        {{ $list->bank_name }}
                                    </td>
                                    <td>
                                        {{ $list->bank_number }}
                                    </td>
                                    <td>
                                        {{ $list->company_income }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_nation }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_health }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_employ }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_industry }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_retirement }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_return_confirm }}
                                    </td>
                                    <td>
                                        {{ $list->tax_company_tax_total }}
                                    </td>
                                    <td>
                                        {{ $list->company_payment_result }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
            </article> <!-- article list_contents end -->
        @endif

    </section>


    <script src="/js/CalcInputChange.js"></script>


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
                onSelect: function(dateText, inst) {

                    console.log(dateText);
                    location.href = "/record/payment?from_date="+ dateText;

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




    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


@endsection
