@extends("layouts/layout")

@section("title")
    활동지원사 - 서비스 제공 현황
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

<style>

    #confirm_type {
        margin-right: 10px;
    }

    .worker-service__table table {
        width: 100% !important;
    }

    .worker-service__table table thead tr th {
        height: 30px;
        background-color: #e7e7e7;
        color: #363636;
        border-top: 1px solid #b7b7b7;
        font-size: 14.5px;
        font-family: "Noto Sans CJK KR Bold";
    }


    .worker-service__table table thead tr.service-organization th:nth-child(n+3) {
        border-right: 1px solid #b7b7b7;
    }

    .worker-service__table table thead tr.time-info th:nth-child(3n+3) {
        border-right: 1px solid #b7b7b7;
    }

    .worker-service__table table tbody tr td {
        text-align: center;
        height: 40px;
        border-top: 1px solid #b7b7b7;
    }

    .worker-service__table table tbody tr:last-child td {
        border-bottom: 1px solid #b7b7b7;
    }

    .worker-service__table table tbody tr td:nth-child(3n+3) {
        border-right: 1px solid #b7b7b7;
    }

    .worker-service__table table tbody tr td:last-child {
        border-right: none;
    }

    .worker-service__table table tbody tr:nth-child(2n) td {
        text-align: center;
        background-color: #f3f2f2;
        height: 40px;
    }

    #list_tables_info {
        display: none;
    }

    .worker-service__table table tbody tr td:nth-child(3n+3) {
        border-right: none;
    }

</style>


<section id="member_wrap" class="list_wrapper worker_pay">

    <article id="list_head">

        <div class="head-info">
            <h1>서비스 제공 현황</h1>
        </div>

        <div class="search-wrap">
            <form action="" method="" name="member_search">
                <div class="limit-wrap">
                    <select name="confirm_type" id="confirm_type">
                        <option value="">승인일자</option>
                    </select>
                    <input type="text" name="from_date" autocomplete="off" readonly id="from_date" value="{{ $_GET['from_date'] ?? "" }}">
                    <label for="from_date">
                        <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                    </label>
                    <span>~</span>
                    <input type="text" name="to_date" autocomplete="off" readonly id="to_date" value="{{ $_GET['to_date'] ?? "" }}">
                    <label for="to_date">
                        <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                    </label>
                    <button type="submit">조회</button>
                </div>
            </form>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents" class="worker-service__table">

        <table id="list_tables">
            <thead>
            <tr class="service-organization">
                <th rowspan="2">No</th>
                <th rowspan="2">활동지원사</th>
                <th colspan="3">보건복지부</th>
                <th colspan="3">광역자치단체</th>
                <th colspan="3">기초자치단체</th>
            </tr>
            <tr class="time-info">
                <th>일수</th>
                <th>시간</th>
                <th>금액</th>
                <th>일수</th>
                <th>시간</th>
                <th>금액</th>
                <th>일수</th>
                <th>시간</th>
                <th>금액</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($providers as $key => $provider)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $key }}
                    </td>
                    <td>
                        {{ count(array_unique($provider['NATION']['DAY'])) }}
                    </td>
                    <td>
                        {{ $provider['NATION']['TIME'] }}
                    </td>
                    <td>
                        {{ number_format($provider['NATION']['PRICE']) }}
                    </td>
                    <td>
                        {{ count(array_unique($provider['CITY']['DAY'])) }}
                    </td>
                    <td>
                        {{ $provider['CITY']['TIME'] }}
                    </td>
                    <td>
                        {{ number_format($provider['CITY']['PRICE']) }}
                    </td>
                    <td>
                        {{ count(array_unique($provider['PROVINCE']['DAY'])) }}
                    </td>
                    <td>
                        {{ $provider['PROVINCE']['TIME'] }}
                    </td>
                    <td>
                        {{ number_format($provider['PROVINCE']['PRICE']) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">

    </article> <!-- article list_bottom end -->

</section>

<script>

    $('#list_tables').DataTable({
        searching: false,
        lengthChange: false,
        scrollCollapse: false,
        paging:         true,

        language: {
            emptyTable: "데이터가 없습니다.",
            "lengthMenu": "페이지당 _MENU_ 개씩 보기",
            "info": "현재 _START_ - _END_ / _TOTAL_건",
            "infoEmpty": "데이터 없음",
            "infoFiltered": "( _MAX_건의 데이터에서 필터링됨 )",
            "search": "에서 검색: ",
            "zeroRecords": "일치하는 데이터가 없습니다.",
            "loadingRecords": "로딩중...",
            "processing": "잠시만 기다려 주세요...",
            "paginate": {
                "next": "다음",
                "previous": "이전"
            }
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


</script>
@endsection