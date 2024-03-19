@extends("layouts/layout")

@section("title")
    부가기능 - 다른 기관은 어떻게?
@endsection


@section("content")
<link rel="stylesheet" href="/css/member/index.css">

@include("addon.side_nav")
<section id="member_wrap" class="addon-other list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>다른 기관은 어떻게?</h1>
        </div>

        <div class="head-tabs-wrap">
            <ul>
                <li class="{{ $type == "payments" ? "on" : "" }}">
                    <a href="{{ route("addon.other", ["type" => "payments"]) }}">
                        계산 방법별 급여지급현황
                    </a>
                </li>
                <li class="{{ $type == "hourlyWage" ? "on" : "" }}">
                    <a href="{{ route("addon.other", ["type" => "hourlyWage"]) }}">
                        기본시급 적용 현황
                    </a>
                </li>
            </ul>
        </div>

        <div class="search-wrap m-top-20">
            <form action="" method="get" name="member_search">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <button class="btn-black-small">조회</button>
                    </div>
                </div>
            </form>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents">

        <h3><span class="dotted-yellow">▪</span>비포괄임금제 기본금 및 수당 산정 기준</h3>

        <table class="member-list b-last-bottom m-bottom-20">
            <thead>
            <tr>
                <th>
                    기본시급(원)
                </th>
                <th>
                    주휴수당(%)
                </th>
                <th>
                    연월차수당(%)
                </th>
                <th>
                    야간수당(%)
                </th>
                <th>
                    휴일수당(%)
                </th>
                <th>
                    연장근로수당(%)
                </th>
                <th>
                    적용기관비율
                </th>
                <th>
                    급여지급 비율
                </th>
            </tr>
            </thead>
            <tbody>
            @for($i=0; $i<20; $i++)
            <tr>
                <td>
                    0
                </td>
                <td>
                    0
                </td>
                <td>
                    0
                </td>
                <td>
                    0
                </td>
                <td>
                    0
                </td>
                <td>
                    0
                </td>
                <td>
                    0%
                </td>
                <td>
                    0%
                </td>
            </tr>
            @endfor
            </tbody>
        </table>

        {{--<h3><span class="dotted-yellow">▪</span>포괄임금제 시간당 인건비</h3>--}}

        {{--<table class="member-list b-last-bottom">--}}
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th>--}}
                    {{--기본시급(원)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--주휴수당(%)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--연월차수당(%)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--야간수당(%)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--휴일수당(%)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--연장근로수당(%)--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--적용기관비율--}}
                {{--</th>--}}
                {{--<th>--}}
                    {{--급여지급 비율--}}
                {{--</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--@for($i=0; $i<20; $i++)--}}
                {{--<tr>--}}
                    {{--<td>--}}
                        {{--8,350--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--100--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--0--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--100--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--100--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--100--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--1.12%--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--0.58%--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--@endfor--}}
            {{--</tbody>--}}
        {{--</table>--}}

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

</script>
@endsection
