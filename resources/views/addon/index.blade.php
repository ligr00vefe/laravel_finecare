@extends("layouts/layout")

@section("title")
    부가기능 - 기관 현황 및 통계
@endsection

@php

$kinds = [
    "근무 활동지원사 수", "접수 활동지원사 수", "입사 활동지원사 수", "퇴사 활동지원사 수",
    "서비스이용 이용자 수", "접수 이용자 수", "계약시작 이용자 수", "계약해지 이용자 수", "
    근로/이용시간", "파견 횟수"
];

@endphp

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")


<section id="member_wrap" class="addon-statistics list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>기관 현황 및 통계</h1>
            <div class="action-wrap">
                <ul>
                    {{--<li>--}}
                        {{--<button>엑셀내려받기</button>--}}
                    {{--</li>--}}
                </ul>
            </div>
        </div>

        <div class="search-wrap">
            <form action="" method="post" name="member_list_search">
                <div class="limit-wrap">
                    <span>대상연도</span>
                    <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                    <label for="from_date">
                        <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                    </label>
                </div>
            </form>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents">
        <table class="member-list in-input b-last-bottom">
            <thead>
            <tr>
                <th>
                </th>
                <th>2021-01</th>
                <th>2021-02</th>
                <th>2021-03</th>
                <th>2021-04</th>
                <th>2021-05</th>
                <th>2021-06</th>
                <th>2021-07</th>
                <th>2021-08</th>
                <th>2021-09</th>
                <th>2021-10</th>
                <th>2021-11</th>
                <th>2021-12</th>
                <th>합계</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($kinds as $kind)
            <tr>
                <td>
                    {{ $kind }}
                </td>
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
