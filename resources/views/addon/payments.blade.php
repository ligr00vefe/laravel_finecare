@extends("layouts/layout")

@section("title")
    부가기능 - 다른 기관은 어떻게?
@endsection

@php

    $datas = [
        "비포괄임금제(비율)",
        "비포괄임금제(비율) - 지급목표 맞추기",
        "비포괄임금제(비율) - 보전수당 없애기",
        "비포괄임금제(단가)",
        "비포괄임금제(단가) - 지급목표 맞추기",
        "비포괄임금제(단가) - 보전수당 없애기",
        "포괄임금제(비율)",
        "포괄임금제(비율) - 지급목표 맞추기",
        "포괄임금제(비율) - 보전수당 없애기",
        "포괄임금제(정액)",
        "포괄임금제(정액) - 지급목표 맞추기",
        "포괄임금제(정액) - 보전수당 없애기"
    ];

@endphp

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

    </article> <!-- article list_head end -->

    <article id="list_contents">
        <table class="member-list b-last-bottom">
            <thead>
            <tr>
                <th>급여계산방법</th>
                <th>적용 기관 비율</th>
                <th>급여지급 비율</th>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data}}</td>
                    <td class="t-right">0%</td>
                    <td class="t-right">0%</td>
                </tr>
            @endforeach
            <tr>
                <th></th>
            </tr>
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
<!--        --><?//=pagination(10, $page, 30 )?>
    </article> <!-- article list_bottom end -->

</section>

@endsection
