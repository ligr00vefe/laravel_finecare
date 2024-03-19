@extends("layouts/layout")

@section("title")
    서비스 미이용 현황
@endsection

<?php

$list = [
    [
        "id" => 1,
        "name" => "홍길동",
        "birth" => "2020-10-27",
        "gender" => "남자",
        "disabled_type" => "시각",
        "disabled_grade" => "3등급",
        "sub_disabled_type" => "미등록",
        "income_grade" => "기초생활수급자",
        "activity_support_grade" => "15등급",
        "activity_support_grade_type" => "가",
        "tel" => "010-1111-2222",
        "addr" => "경기도 수원시 영봉구 광교호수공원로 277(양천동, 광교중흥에스클래스) 101동 202호",
        "status" => "이용중",
        "regdate" => "2020-01-01",
        "startDate" => "2020-01-01",
        "endDate" => "2020-12-31"
    ]
];
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">

@include("member.side_nav")

<section id="member_wrap" class="list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>서비스 미이용 현황 <span>전자바우처 결제내역이 없는 대기 또는 이용중인 이용자를 조회합니다</span></h1>
            <div class="action-wrap">
            </div>
        </div>


        <div class="search-wrap">
            <form action="" method="post" name="member_list_search">
                <div class="limit-wrap">
                    <p>
                        기준연월
                    </p>
                    <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                    <label for="from_date">
                        <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                    </label>
                    <button type="submit">조회</button>
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
        <table class="member-list in-input">
            <colgroup>
                <col width="2%">
                <col width="3%">
                <col width="auto">
                <col width="auto">
                <col width="auto">
                <col width="auto">
                <col width="auto">
            </colgroup>
            <thead>
            <tr>
                <th>
                    <input type="checkbox" id="check_all" name="check_all" value="1">
                    <label for="check_all"></label>
                </th>
                <th>No</th>
                <th>이름</th>
                <th>생년월일</th>
                <th>성별</th>
                <th>상태</th>
                <th>전화번호</th>
            </tr>
            </thead>
            <tbody>
            @for ($i=0; $i<15; $i++)
            <tr>
                <td>
                    <input type="checkbox" id="check_{{$i}}" name="id[]" value="{{$list[0]['id']}}">
                    <label for="check_{{$i}}"></label>
                </td>
                <td>{{(15*($page-1))+$i+1}}</td>
                <td>
                    홍길동
                </td>
                <td>
                    20-01-01
                </td>
                <td>
                    남
                </td>
                <td>
                    이용중
                </td>
                <td>
                    010-1234-5678
                </td>
            </tr>
            @endfor
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
<!--        --><?//=pagination(10, $page, 30 )?>
    </article> <!-- article list_bottom end -->

</section>


<script>
    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {
            $("input[name='to_date']").datepicker({
                minDate: new Date(dateText),
                dateFormat:"yyyy-mm",
                clearButton: false,
                autoClose: true,
            })
        }
    });


    $("input[name='to_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

        }
    })
</script>
@endsection
