@extends("layouts/layout")

@section("title")
    활동지원사 - 월별 급여 현황
@endsection

<?php

$members = [
    [
        "name" => "홍길동",
        "birth" => "2020-10-27",
        "gender" => "남자",
        "tel" => "010-1111-2222",
        "addr" => "경기도 수원시 영봉구 광교호수공원로 277(양천동, 광교
                    중흥에스클래스) 101동 202호",
        "status" => "이용중",
        "regdate" => "2020-01-01",
        "startDate" => "2020-01-01",
        "endDate" => "2020-12-31"
    ]
];

?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("worker.side_nav")


<section id="member_wrap" class="worker_pay list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>월별 급여현황</h1>
        </div>

        <div class="right-buttons">
            <ul>
                <li>
                    <button type="button" class="orange">엑셀 내려받기</button>
                </li>
            </ul>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <span>~</span>
                        <input type="text" name="to_date" autocomplete="off" readonly id="to_date">
                        <label for="to_date">
                            <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                        </label>
                        <button type="submit">조회</button>
                    </div>
                </div>
                <div class="search-input">
                    <input type="text" name="term" placeholder="검색">
                    <button type="submit">
                        <img src="/storage/img/search_icon.png" alt="검색하기">
                    </button>
                </div>
            </form>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents" class="over-x-auto">

        <div class="search-info">
            <p>검색결과 총 <b class="acc-orange">149명</b></p>
        </div>
        
        <table class="worker-monthlyPay__table">
            <thead>
            <tr>
                <th rowspan="2">
                    <input type="checkbox" name="check_all" id="check_all">
                    <label for="check_all"></label>
                </th>
                <th rowspan="2">No</th>
                <th rowspan="2">이름</th>
                <th rowspan="2">생년월일</th>
                <th rowspan="2">입사일자</th>
                <th rowspan="2">퇴사일자</th>
                <th colspan="3">2020-01</th>
                <th colspan="3">2020-02</th>
                <th colspan="3">2020-03</th>
                <th colspan="3">2020-04</th>
                <th colspan="3">2020-05</th>
                <th colspan="3">2020-06</th>
                <th colspan="3">2020-07</th>
                <th colspan="3">2020-08</th>
                <th colspan="3">2020-09</th>
                <th colspan="3">2020-10</th>
            </tr>
            <tr class="monthly-info">
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
                <th>지급합계</th>
                <th>공제합계</th>
                <th>지인지급액</th>
            </tr>
            </thead>
            <tbody>
            <tr class="total">
                <th></th>
                <th>합계</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

                <th>17,058,574</th>
                <th>17,058,574</th>
                <th>17,058,574</th>

                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
            </tr>

            @foreach ($lists as $i=>$list)
                <tr class="data">
                    <td class="t-center">
                        <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}" value="{{$list->id}}">
                        <label for="check_{{$i}}"></label>
                    </td>
                    <td class="t-center">{{ ($i+1) + (($page-1) * 15) }}</td>
                    <td class="t-center">{{ $list->name }}</td>
                    <td class="t-center">{{ convert_birth($list->rsNo) }}</td>
                    <td class="t-center">{{ date("Y-m-d", strtotime($list->join_date)) }}</td>
                    <td class="t-center">{{ date("Y-m-d", strtotime($list->resign_date)) }}</td>
                    <td>19</td>
                    <td>1</td>
                    <td>73</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <p class="acc-orange">
        * 급여계산시 저장된 내역으로 조회하므로, 이전에 급여계산 시 반영되지 않았던 기간의 근로일수, 활동시간은 실제와 다를 수 있습니다.
    </p>

    <article id="list_bottom">
        {!! pagination(15, ceil($paging/15)) !!}
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