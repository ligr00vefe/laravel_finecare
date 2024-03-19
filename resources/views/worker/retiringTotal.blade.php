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
            <h1>퇴직금 누적적립 현황</h1>
        </div>

        <div class="right-buttons">
            <ul>
                <li>
                    <button type="button" class="modify-all">선택수정</button>
                </li>
                <li>
                    <button type="button" class="orange">엑셀 내려받기</button>
                </li>
            </ul>
        </div>

        <div class="search-wrap retiring-wrap">

            <form action="" method="post" name="member_search">

                <div class="text-wrap">
                    <p>
                        * 접수 상태의 활동지원사는 출력되지 않습니다.
                    </p>
                    <p>
                        * FINECARE에서 급여계산을 하기 이전에 적립된 퇴직금이 있는 경우 ‘급여계산 전 퇴직적립금’ 금액을 클릭하신 후 수정하시기 바랍니다.
                    </p>
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
                <col width="10%">
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
                <th>근무시작일자</th>
                <th>근무종료일자</th>
                <th>급여계산<br>시작년월</th>
                <th>계산된<br>퇴직적립금</th>
                <th>급여계산 전<br>퇴직적립금</th>
                <th>총 누적<br>퇴직적립금</th>
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
                    2020-10-08
                </td>
                <td>
                    2020-10-08
                </td>
                <td>
                    885,590
                </td>
                <td class="table-input-wrap-sand">
                    <input type="text" name="before_pay_retiring_{{$i}}" id="before_pay_retiring_{{$i}}">
                    <label for="before_pay_retiring_{{$i}}"></label>
                </td>
                <td>
                    885,590
                </td>

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