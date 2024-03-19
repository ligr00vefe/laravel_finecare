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



<section id="member_wrap" class="list_wrapper">

    <article id="list_head">

        <div class="head-info exist-right">
            <h1>급여계산 근로시간 현황 <span>급여계산 결과의 근로일수, 근로시간, 수당산정시간 등을 조회합니다.</span></h1>
        </div>

        <div class="right-buttons">
            <ul>
                <li>
                    <button type="button" class="orange">엑셀 내려받기</button>
                </li>
            </ul>
        </div>

        <div class="search-wrap">
            <form action="" method="post" name="member_search">
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

    <article id="list_contents">
        
        <table class="table-1 worker-cal__table">
            <colgroup>
                <col width="3%">
                <col width="3%">
                <col width="5%">
            </colgroup>
            <thead>
            <tr>
                <th>
                    <input type="checkbox" name="check_all" id="check_all">
                    <label for="check_all"></label>
                </th>
                <th>
                    No
                </th>
                <th>이름</th>
                <th>생년월일</th>
                <th>입사일자</th>
                <th>퇴사일자</th>
                <th>총근로일수</th>
                <th>총근무<br>개월수</th>
                <th>보건복지부<br>(시간)</th>
                <th>광역(시간)</th>
                <th>
                    기초(시간)
                </th>
                <th>총활동시간<br>(시간)</th>
                <th>월별(시간)</th>
                <th>야간(시간)</th>
                <th>휴일(시간)</th>
                <th>연장(시간)</th>
                <th>휴일8시간연장(시간)</th>
            </tr>
            </thead>

            <tbody>
            @for($i=0; $i<15; $i++)
            <tr>
                <td>
                    <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}">
                    <label for="check_{{$i}}"></label>
                </td>
                <td>
                    {{$i+1}}
                </td>
                <td>
                    홍길동
                </td>
                <td>
                    57-01-01
                </td>
                <td>
                    2020-10-08
                </td>
                <td>

                </td>
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

            </tr>
            @endfor
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <p class="acc-orange">
        * 급여계산시 저장된 내역으로 조회하므로, 이전에 급여계산 시 반영되지 않았던 기간의 근로일수, 활동시간은 실제와 다를 수 있습니다.
    </p>

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