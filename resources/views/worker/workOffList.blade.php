@extends("layouts/layout")

@section("title")
    활동지원사 - 근무 미활동 현황
@endsection

<?php

$members = [
    [
        "name" => "홍길동",
        "birth" => "2020-10-27",
        "gender" => "남자",
        "tel" => "010-1111-2222",
        "addr" => "경기도 수원시 영봉구 광교호수공원로 277(양천동, 광교
                    중흥에스클래스)",
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
            <h1>근무 미활동 현황 <span>전자바우처 결제내역이 없는 대기 또는 활동중인 활동지원사를 조회합니다.</span></h1>
        </div>

        <div class="right-buttons">
            <ul>
                {{--<li>--}}
                    {{--<button type="button" class="orange">엑셀 내려받기</button>--}}
                {{--</li>--}}
            </ul>
        </div>

        <div class="search-wrap">
            <form action="" method="" name="member_search">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <span>기준연월</span>
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date" value="{{ $_GET['from_date'] ?? "" }}">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
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
                <col width="5%">
                <col width="8%">
                <col width="5%">
                <col width="10%">
                <col>
                <col width="3%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <col width="8%">
            </colgroup>
            <thead>
            <tr>
                <th>
                    No
                </th>
                <th>이름</th>
                <th>생년월일</th>
                <th>성별</th>
                <th>전화번호</th>
                <th>주소</th>
                <th>상태</th>
                <th>입사일</th>
                <th>퇴사일</th>
                <th>서비스중단월</th>
                <th>휴직일</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($lists as $list)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $list->name ?? "" }}
                </td>
                <td>
                    {{ \App\Classes\Custom::rsno_to_birth($list->birth) }}
                </td>
                <td>
                    {{ \App\Classes\Custom::rsno_to_gender($list->birth) }}
                </td>
                <td>
                    {{ $list->phone ?? "" }}
                </td>
                <td class="t-left lh-22">
                    {{ $list->address ?? "" }}
                </td>
                <td>
                    {{ $list->contract }}
                </td>
                <td>
                    {{ $list->contract_start_date != "1970-01-01 00:00:00" ? date("Y-m-d", strtotime($list->contract_start_date)) : "" }}
                </td>
                <td>
                    {{ $list->contract_end_date != "1970-01-01 00:00:00" ? date("Y-m-d", strtotime($list->contract_end_date)) : "" }}
                </td>
                <td>
                    {{ $list->last_work ? date("Y-m", strtotime($list->last_work)) : "" }}
                </td>
                <td>
                    {{ $list->rest_day ?? "" }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <p class="acc-orange">
        * 급여계산시 저장된 내역으로 조회하므로, 이전에 급여계산 시 반영되지 않았던 기간의 근로일수, 활동시간은 실제와 다를 수 있습니다.
    </p>

    <article id="list_bottom">
        {!! pagination2(10, ceil($paging/15)) !!}
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
