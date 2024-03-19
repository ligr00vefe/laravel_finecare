@extends("layouts/layout")

@section("title")
    근로자별 급여이력조회
@endsection

@section("content")

    @include("salary.side_nav")
    <link rel="stylesheet" href="/css/member/index.css">
    <style>

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


    </style>

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>근로자별 급여이력조회</h1>
            </div>

        </article>

        <div class="search-form">
            <form action="">

                <span>년 검색</span>

                <input type="text" class="from_date" name="from_date" id="from_date" autocomplete="off" readonly value="{{ $from_date ?? "" }}">
                <label for="from_date">
                    <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                </label>

                <span>근로자명</span>
                <input type="text" name="provider_name" value="{{ $provider_name ?? "" }}">

                <span>생년월일</span>
                <input type="text" name="birth" placeholder="ex) 200101" value="{{ $birth ?? "" }}">

                <button>검색</button>

            </form>
        </div>

        @if ($lists)
            <article id="list_contents" class="m-top-20">
            <form action="" id="payment_calc" name="payment_calc" method="post">

                <div class="">
                    <table id="calc_result" class=" stripe row-border order-column">
                        <thead class="thead-origin">
                        <tr class="table-top">
                            <th rowspan="2" colspan="4" class="b-right-b7">
                                제공자 기본 정보
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
                            @endif

                        </thead>

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
                dateFormat:"yyyy",
                view: 'years',
                minView: 'years',
                clearButton: false,
                autoClose: true,

            });

        });


        $(document).ready(function() {
            $('#calc_result').DataTable({
                searching: false,
                lengthChange: false,
                scrollX:        "5000px",
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
