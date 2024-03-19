@extends("layouts/layout")

@section("title")
    급여관리 - 포괄임금제 계산
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")


    <style>


        .salary-cal-wrap tr.table-top th:nth-child(n+5) {
            border-right: 1px solid #b7b7b7;
        }

        .salary-cal-wrap .table-5x-large tbody tr th:nth-child(5),
        .salary-cal-wrap .table-5x-large tbody tr td:nth-child(5)
        {
            border-right: 1px solid #b7b7b7;
        }

        .b-right-b7 {
            border-right: 1px solid #b7b7b7;
        }


        .salary-cal-wrap .search-input {
            font-size: 0;
        }

        .salary-cal-wrap .search-input input {
            width: 160px;
            height: 32px;
            border-right: none;
        }

        .salary-cal-wrap .search-input button {
            width: 35px !important;
            vertical-align: bottom;
            background-color: #e2e2e2 !important;
        }

        .salary-cal-wrap .search-input button img {

        }


    </style>

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>포괄임금제 계산</h1>
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
                                시간당 인건비
                            </th>
                            <td class="search-type__td">
                                <table class="search-in-table">
                                    <tr>
                                        <td>기본시급(원)</td>
                                        <td>주휴수당</td>
                                        <td>연월차수당(원)</td>
                                        <th>법정제수당(원)</th>
                                        <th>합계(원)</th>
                                        <th></th>
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
                                        </td>
                                    </tr>
                                </table>
                                <p class="help">* 주휴, 연월차 단가 적용 최대 시간 : 옵션 설정에서 선택한 시간을 적용하여 계산합니다.</p>
                            </td>
                        </tr>
                        <tr>
                            <th>급여지급 목표</th>
                            <td class="td_01">
                                <div class="m-bottom-10">
                                    <span>* 결제시간당 지정단가로 계산, 보전수당 없이 계산 - 평일 : 10,310원 | 휴일/야간 : 15,465원</span>
                                </div>
                                <div class="m-top-20">
                                    <p>
                                        - (+) 보전수당 <span class="fc-red">분배</span>
                                    </p>
                                </div>
                                <div class="fc-black-36 m-top-10">
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_1" class="p-m-clear">
                                    <label for="pay_distribution_1" class="m-right-10">주휴수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_2" class="p-m-clear">
                                    <label for="pay_distribution_2" class="m-right-10">연월차수당</label>
                                    <input type="checkbox" name="pay_distribution" id="pay_distribution_3" class="p-m-clear">
                                    <label for="pay_distribution_3" class="m-right-10">법정제수당</label>
                                </div>
                                <div class="m-top-20">
                                    <p>
                                        - (-) 보전수당 차감
                                    </p>
                                </div>
                                <div class="fc-black-36 m-top-10 m-bottom-10">
                                    <input type="checkbox" name="pay_sub" id="pay_sub_1" class="p-m-clear">
                                    <label for="pay_sub_1" class="m-right-10">주휴수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_2" class="p-m-clear">
                                    <label for="pay_sub_2" class="m-right-10">연월차수당</label>
                                    <input type="checkbox" name="pay_sub" id="pay_sub_3" class="p-m-clear">
                                    <label for="pay_sub_3" class="m-right-10">법정제수당</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>근로시간 기준</th>
                            <td class="fc-black-36">
                                <span>전자바우처 결제시간</span>
                            </td>
                        </tr>
                        <tr>
                            <th>기본금/수당<br>원단위 처리</th>
                            <td>
                                <span>올림</span>
                            </td>
                        </tr>
                        <tr>
                            <th>기본금/수당<br>원단위 처리</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">공제기준:</span>
                                    <span class="fc-black-36">급여 내역이 있는 경우만 공제</span>
                                </div>
                                <div>
                                    <span class="m-right-10">(=) 4대보험 공제여부 : </span>
                                    <span class="fc-black-36">공제</span>
                                </div>
                                <div>
                                    <span class="m-right-10">요율 계산 공제 기준 : </span>
                                    <span class="fc-black-36">전체</span>
                                </div>
                                <div>
                                    <span class="m-right-10">요율 계산 원단위 처리 : </span>
                                    <span class="fc-black-36">버림</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>소득세</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">부과여부 : </span>
                                    <span class="fc-black-36">부과</span>
                                </div>
                                <div>
                                    <span class="m-right-10">지방소득세 원단위 처리 : </span>
                                    <span class="fc-black-36">버림</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>퇴직적립금 기준</th>
                            <td class="td_02">
                                <div>
                                    <span class="m-right-10">적립 대상: </span>
                                    <span class="fc-black-36">활동시간(60시간 이상)</span>
                                </div>
                                <div>
                                    <span class="m-right-10">원단위 처리: </span>
                                    <span class="fc-black-36">버림</span>
                                </div>
                            </td>
                        </tr>

                        </thead>
                    </table>


                </form>
            </div>

        </article> <!-- article list_head end -->

        <article class="search-detail-wrap m-top-10 ">

            <table>
                <colgroup>
                    <col width="10%">
                    <col width="30%">
                    <col width="10%">
                    <col width="30%">
                    <col width="auto">
                    <col width="10%">

                </colgroup>
                <tr>
                    <td>
                        <input type="checkbox" name="total_activity_time" id="total_activity_time" class="p-m-clear">
                        <label for="total_activity_time">총 활동시간</label>
                    </td>
                    <td>
                        <input type="text" name="search_detail_start_time" >
                        <span>시간</span>
                        <div class="wrap-ib btn-wrap">
                            <button type="button" class="type1">이상</button>
                            <button type="button" class="type2">초과</button>
                        </div>
                        <span>~</span>
                        <input type="text">
                        <span>시간</span>
                        <div class="wrap-ib btn-wrap">
                            <button type="button" class="type1">이하</button>
                            <button type="button" class="type2">미만</button>
                        </div>
                    </td>
                    <td>
                        <input type="checkbox" name="add_percentage_check" id="add_percentage_check" class="p-m-clear">
                        <label for="add_percentage_check">수가지급비율</label>
                    </td>
                    <td>
                        <input type="text" name="add_percentage"> %
                        <div class="wrap-ib btn-wrap">
                            <button type="button" class="type1">이상</button>
                            <button type="button" class="type2">초과</button>
                        </div>
                        <span>~</span>
                        <input type="text">
                        <span class="m-right-10">%</span>
                        <div class="wrap-ib btn-wrap">
                            <button type="button" class="type1">이하</button>
                            <button type="button" class="type2">미만</button>
                        </div>
                    </td>
                    <td colspan="2">
                        <button class="btn-submit-small">검색</button>
                    </td>
                    <td class="t-right">
                        <div class="search-input">
                            <input type="text" name="term" placeholder="검색">
                            <button type="submit">
                                <img src="/storage/img/search_icon.png" alt="검색하기">
                            </button>
                        </div>
                    </td>
                </tr>
            </table>

        </article>

        <article id="list_contents" >

            <table class="member-list b-last-bottom table-5x-large">
                <thead>
                <tr class="table-top">
                    <th rowspan="2">
                        No
                    </th>
                    <th rowspan="2">
                        대상자명
                    </th>
                    <th rowspan="2">
                        생년월일
                    </th>
                    <th rowspan="2">
                        근속기간<br>
                        (개월)
                    </th>
                    <th rowspan="2">
                        근속기간<br>
                        (일)
                    </th>
                    <th colspan="4">
                        홀 활동시간(시간)
                    </th>
                    <th rowspan="2">
                        주 평균<br>
                        활동시간<br>
                        (시간)
                    </th>
                    <th colspan="2">
                        가산활동시간(시간)
                    </th>
                    <th colspan="2">
                        보건복지부 세부시간(시간)
                    </th>
                    <th colspan="2">
                        기초 세무시간(시간)
                    </th>
                    <th colspan="4">
                        세부활동시간(시간)
                    </th>
                    <th rowspan="2">
                        평일단가<br>
                        환산시간<br>
                        (시간)
                    </th>
                    <th rowspan="2">
                        기본급(원)
                    </th>
                    <th colspan="5">
                        수당(원)
                    </th>
                    <th rowspan="2">
                        기본급+수당합계<br>
                        (원)
                    </th>
                    <th rowspan="2">
                        결제금액합계<br>
                        (원)
                    </th>
                    <th rowspan="2">
                        수가지급<br>
                        비율(%)
                    </th>
                    <th rowspan="2">
                        휴일8시간<br>
                        초과수장(원)
                    </th>
                    <th rowspan="2">
                        공휴일수당<br>
                        (원)
                    </th>
                    <th rowspan="2">
                        중증가산수당<br>
                        (원)
                    </th>
                    <th rowspan="2">
                        원거리교통비<br>
                        (원)
                    </th>
                    <th colspan="7">
                        공재금액(원)
                    </th>
                    <th rowspan="2">
                        동글이금액(원)
                    </th>
                    <th rowspan="2">
                        실급여액(원)
                    </th>
                    <th rowspan="2">
                        퇴직적립금(원)
                    </th>
                </tr>
                <tr>
                    <th>
                        보건복지부
                    </th>
                    <th>
                        광역
                    </th>
                    <th>
                        기초
                    </th>
                    <th>합계</th>
                    <th>일반</th>
                    <th class="b-right-b7">가산</th>
                    <th>일반</th>
                    <th class="b-right-b7">가산</th>
                    <th>일반</th>
                    <th class="b-right-b7">가산</th>


                    <th>야간</th>
                    <th>휴일</th>
                    <th>연장</th>
                    <th>휴일8시간초과</th>

                    <th>주휴수당</th>
                    <th>연월차수당</th>
                    <th>법정제수당</th>
                    <th>보전수당</th>
                    <th>합계</th>

                    <th>국민연금</th>
                    <th>건강보험</th>
                    <th>장기요양보험</th>
                    <th>고용보험</th>
                    <th>소득세</th>
                    <th>지방소득세</th>
                    <th>합계</th>
                </tr>
                </thead>
                <tbody>
                <tr class="total">
                    <th></th>
                    <th>합계</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        11,826
                    </th>
                    <th>
                        11,826
                    </th>
                    <th>
                        11,826
                    </th>
                    <th class="b-right-b7">
                        11,826
                    </th>
                    <th class="b-right-b7"> {{-- 10번째 --}}
                        11,826
                    </th>
                    <th>
                        12,690.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th>
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                    <th class="b-right-b7">
                        2,604.5
                    </th>
                </tr>
                @for ($i=0; $i<15; $i++)
                    <tr>
                        <td>
                            {{$i+1}}
                        </td>
                        <td>
                            홍길동
                        </td>
                        <td>
                            56-01-01
                        </td>
                        <td>40</td>
                        <td>20</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td>73</td>
                        <td >73</td>
                        <td>73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
                        <td class="b-right-b7">73</td>
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
