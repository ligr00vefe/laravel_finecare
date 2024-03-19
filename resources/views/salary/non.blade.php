@extends("layouts/layout")

@section("title")
    급여관리 - 비포괄임금제 계산
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>비포괄임금제 계산</h1>
                <div class="right-buttons">
                    <button type="button" class="btn-orange-wrap">
                        <img src="{{__IMG__}}/button_orange_plus.png" alt="항목별 활용방법 아이콘">
                        지급방식안내
                    </button>
                </div>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    <table>
                        <thead>
                        <tr>
                            <th>급여기준연월</th>
                            <td>
                                <input type="text" name="from_date" id="from_date" class="input-datepicker" autocomplete="off">
                                <label for="from_date">
                                    <img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <p>
                                기본급 및 수당<span class="required_mark"></span><br>
                                산정기준
                                </p>
                            </th>
                            <td class="search-type__td">
                                <table class="search-in-table">
                                    <tr>
                                        <td>기본시급(원)</td>
                                        <td>주휴수당</td>
                                        <td></td>
                                        <td>야간수당</td>
                                        <td>휴일수당</td>
                                        <td>연장근로수당</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="pay_hour">
                                        </td>
                                        <td>
                                            <input type="text" name="">
                                        </td>
                                        <td>
                                            <input type="text" name="">
                                        </td>
                                        <td>
                                            <input type="text" name="">
                                        </td>
                                        <td>
                                            <input type="text" name="">
                                        </td>
                                        <td>
                                            <input type="text" name="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>급여지급 목표</th>
                            <td class="td_01">
                                <div class="m-bottom-10">
                                    <span class="m-right-10">기관목표수가지급비울(%)</span>
                                    <input type="radio" name="payment_hour_select_price" id="payment_hour_select_price"><label for="payment_hour_select_price">결제시간당 지정단가</label>
                                </div>
                                <div>
                                    <img src="{{__IMG__}}/bullet_arrow_right.png" alt="오른쪽화살표">
                                    <span>평일(원)</span>
                                    <input type="text" name="week_day_won" value="10310" class="m-right-10">

                                    <img src="{{__IMG__}}/bullet_arrow_right.png" alt="오른쪽화살표">
                                    <span>휴일/야간(원)</span>
                                    <input type="text" name="week_holiday_won" value="10310">
                                </div>
                                <div class="m-bottom-10">
                                    <p class="acc-orange">휴일/야간 가산금액은 5,155원으로 계산됩니다.(15,465[휴일/야간] - 10,310[평일])</p>
                                </div>
                                <div class="m-bottom-10">
                                    <input type="checkbox" name="pay_goal_cal_type1" id="pay_goal_cal_type1" class="p-m-clear">
                                    <label for="pay_goal_cal_type1">급여지급목표에 맞춰 계산하기</label>
                                </div>
                                <div class="">
                                    <input type="checkbox" name="pay_goal_cal_type2" id="pay_goal_cal_type2" class="p-m-clear">
                                    <label for="pay_goal_cal_type1">보전수당 없이 계산하기 </label>
                                </div>
                                <div class="m-top-20">
                                    <p>
                                        - (+) 보전수당 <span class="fc-red">분배</span> : 분배할 수당을 선택하세요. 선택되지 않은 항목은 보전수당이 분배되지 않고 적용비율대로 고정됩니다.
                                    </p>
                                </div>
                                <div class="fc-black-36 m-top-10">
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_1" class="p-m-clear">
                                    <label for="pay_distribution_1" class="m-right-10">주휴수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_2" class="p-m-clear">
                                    <label for="pay_distribution_2" class="m-right-10">연월차수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_3" class="p-m-clear">
                                    <label for="pay_distribution_3" class="m-right-10">야간수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_4" class="p-m-clear">
                                    <label for="pay_distribution_4" class="m-right-10">휴일수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_5" class="p-m-clear">
                                    <label for="pay_distribution_5" class="m-right-10">연장근로수당</label>
                                </div>
                                <div class="m-top-20">
                                    <p>
                                        - (-) 보전수당 차감 : 차감할 수당을 선택하세요. 선택되지 않은 항목은 보전수당이 차감되지 않고 적용비율대로 고정됩니다.
                                    </p>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="checkbox" name="pay_sub" id="pay_sub_1" class="p-m-clear">
                                    <label for="pay_sub_1" class="m-right-10">주휴수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_2" class="p-m-clear">
                                    <label for="pay_sub_2" class="m-right-10">연월차수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_3" class="p-m-clear">
                                    <label for="pay_sub_3" class="m-right-10">야간수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_4" class="p-m-clear">
                                    <label for="pay_sub_4" class="m-right-10">휴일수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_5" class="p-m-clear">
                                    <label for="pay_sub_5" class="m-right-10">연장근로수당</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>근로시간 기준</th>
                            <td class="fc-black-36">
                                <input type="radio" name="work_hour" id="work_hour_1">
                                <label for="work_hour_1" class="m-right-10">결제시간</label>
                                <input type="radio" name="work_hour" id="work_hour_2">
                                <label for="work_hour_2">시작/종료시간</label>
                            </td>
                        </tr>
                        <tr>
                            <th>기본금/수당<br>원단위 처리</th>
                            <td class="fc-black-36">
                                <input type="radio" name="basic_allowance" id="basic_allowance_1">
                                <label for="basic_allowance_1" class="m-right-10">올림</label>
                                <input type="radio" name="basic_allowance" id="basic_allowance_2">
                                <label for="basic_allowance_2" class="m-right-10">버림</label>
                                <input type="radio" name="basic_allowance" id="basic_allowance_3">
                                <label for="basic_allowance_3" class="m-right-10">반올림</label>
                                <input type="radio" name="basic_allowance" id="basic_allowance_4">
                                <label for="basic_allowance_4" >안함</label>
                            </td>
                        </tr>
                        <tr>
                            <th>기본금/수당<br>원단위 처리</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">공제기준:</span>
                                    <input type="radio" name="tax_type" id="tax_type_1">
                                    <label for="tax_type_1" class="m-right-10">전체</label>
                                    <input type="radio" name="tax_type" id="tax_type_2">
                                    <label for="tax_type_2">급여 내역이 있는 경우만 공제</label>
                                </div>
                                <div>
                                    <span class="m-right-10">(=) 4대보험 공제여부 : </span>
                                    <input type="radio" name="tax_type" id="tax_type_1">
                                    <label for="tax_type_1" class="m-right-10">전체</label>
                                    <input type="radio" name="tax_type" id="tax_type_2">
                                    <label for="tax_type_2">공제하지 않음</label>
                                </div>
                                <div>
                                    <span class="m-right-10">요율 계산 공제 기준 : </span>
                                    <input type="radio" name="tax_type" id="tax_type_1">
                                    <label for="tax_type_1" class="m-right-10">전체</label>
                                    <input type="radio" name="tax_type" id="tax_type_2">
                                    <label for="tax_type_2">활동시간 60시간 이상</label>
                                </div>
                                <div>
                                    <span class="m-right-10">요율 계산 원단위 처리 : </span>
                                    <input type="radio" name="tax_type" id="tax_type_1">
                                    <label for="tax_type_1" class="m-right-10">전체</label>
                                    <input type="radio" name="tax_type" id="tax_type_2">
                                    <label for="tax_type_2">버림</label>
                                    <input type="radio" name="tax_type" id="tax_type_3">
                                    <label for="tax_type_3">반올림</label>
                                    <input type="radio" name="tax_type" id="tax_type_4">
                                    <label for="tax_type_4">안함</label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>소득세</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">부과여부 : </span>
                                    <input type="radio" name="impose_check" id="impose_check_1">
                                    <label for="impose_check_1" class="m-right-10">부과</label>
                                    <input type="radio" name="impose_check" id="impose_check_2">
                                    <label for="impose_check_2">비부과</label>
                                </div>
                                <div>
                                    <span class="m-right-10">지방소득세 원단위 처리 : </span>
                                    <input type="radio" name="local_tax_check" id="local_tax_check_1">
                                    <label for="local_tax_check_1" class="m-right-10">부과</label>
                                    <input type="radio" name="local_tax_check" id="local_tax_check_2">
                                    <label for="local_tax_check_2">비부과</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>퇴직적립금 기준</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">적립 대상: </span>
                                    <input type="radio" name="save_target" id="save_target_1">
                                    <label for="save_target_1" class="m-right-10">활동시간(60시간 이상)</label>
                                    <input type="radio" name="save_target" id="save_target_2">
                                    <label for="save_target_2" class="m-right-10">64시간 이상</label>
                                    <input type="radio" name="save_target" id="save_target_3">
                                    <label for="save_target_3" class="m-right-10">65시간 이상</label>
                                    <input type="radio" name="save_target" id="save_target_4">
                                    <label for="save_target_4" class="m-right-10">전체</label>
                                    <input type="radio" name="save_target" id="save_target_5">
                                    <label for="save_target_5">활동지원사 계약조건에 따라</label>
                                </div>
                                <div>
                                    <span class="m-right-10">원단위 처리: </span>
                                    <input type="radio" name="won_processing" id="won_processing_1">
                                    <label for="won_processing_1" class="m-right-10">올림</label>
                                    <input type="radio" name="won_processing" id="won_processing_2">
                                    <label for="won_processing_2" class="m-right-10">버림</label>
                                    <input type="radio" name="won_processing" id="won_processing_3">
                                    <label for="won_processing_3" class="m-right-10">반올림</label>
                                    <input type="radio" name="won_processing" id="won_processing_4">
                                    <label for="won_processing_4">안함</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td></td>
                        </tr>

                        </thead>
                    </table>

                    <div class="btn-wrap m-top-10">
                        <button class="btn-black">계산하기</button>
                    </div>

                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents" class="m-top-30">

            <p>서비스내역 총 <b class="acc-orange">4860</b> 건</p>

            <table class="member-list b-last-bottom">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" value="1" id="check_all">
                        <label for="check_all"></label>
                    </th>
                    <th>
                        No
                    </th>
                    <th>
                        대상자명
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        등급
                    </th>
                    <th>
                        제공인력명
                    </th>
                    <th>
                        제공인력생년월일
                    </th>
                    <th>
                        시군구
                    </th>
                    <th>
                        사업유형ID
                    </th>
                    <th>
                        사업유형
                    </th>
                    <th>
                        서비스유형
                    </th>
                    <th>
                        승인일자
                    </th>
                </tr>
                </thead>
                <tbody>
                @for ($i=0; $i<15; $i++)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}" value="{{$i}}">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>{{$i+1}}</td>
                        <td>홍길동</td>
                        <td>57-07-30</td>
                        <td>1등급(나형)</td>
                        <td>홍길동</td>
                        <td>57-07-03</td>
                        <td class="t-left">경기도 용인시</td>
                        <td>HWG001</td>
                        <td>장애인활동지원</td>
                        <td>활동보조</td>
                        <td>2020-02-01 03:21</td>
                    </tr>
                @endfor
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">

        </article> <!-- article list_bottom end -->

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


    </script>

@endsection
