@php
@endphp

@extends("layouts.layout")

@section("title")
    활동지원사 - 월별 결제 현황
@endsection


<?php
        $months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
use App\Classes\Custom;
use App\Classes\Input;

?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("worker.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">

            <div class="head-top">

                <div class="top-info">
                    <h1>월별 결제 현황</h1>
                    <ul>
                        {{--<li>--}}
                            {{--<button type="button" class="orange">엑셀 내려받기</button>--}}
                        {{--</li>--}}
                    </ul>
                </div>

                <div class="top-search">
                    <form action="">
                        <table class="table-auto">
                            <tr>
                                <th>구분</th>
                                <td>
                                    <select name="payment_type" id="payment_type">
                                        <option value="">소급결제</option>
                                    </select>
                                </td>
                                <th></th>
                                <td>
                                    <input type="text" name="from_date" id="from_date" class="input-datepicker" readonly autocomplete="off" value="{{ $_GET['from_date'] ?? "" }}">
                                    <label for="from_date">
                                        <img src="/storage/img/icon_calendar.png" alt="기준연월 선택">
                                    </label>
                                </td>
                                <td>
                                    <button class="btn-black-small">조회</button>
                                </td>
                            </tr>
                        </table>

                        {{--<div class="search-input">--}}
                            {{--<input type="text" name="term" placeholder="검색">--}}
                            {{--<button type="submit">--}}
                                {{--<img src="/storage/img/search_icon.png" alt="검색하기">--}}
                            {{--</button>--}}
                        {{--</div>--}}
                    </form>
                </div>
            </div>

            <div class="content-top">

                <div class="info-wrap">

                    <ul class="list-div-3">
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/payment_icon.png" alt="소급결제비율">
                            </div>
                            <div class="text-wrap">
                                <p>소급결제비율</p>
                                <p><b class="acc-orange">{{ $all_sogeup_payment_total ? round(($all_sogeup_payment_total/$all_payment_total)*100, 1) : 0.0 }}</b>%</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/payment_icon.png" alt="소급결제건수">
                            </div>
                            <div class="text-wrap">
                                <p>소급결제건수</p>
                                <p><b class="acc-orange">{{ number_format($all_sogeup_payment_total) ?? 0 }}</b>건</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/payment_icon.png" alt="전체결제건수">
                            </div>
                            <div class="text-wrap">
                                <p>전체결제건수</p>
                                <p><b class="acc-orange">{{ number_format($all_payment_total) ?? 0 }}</b>건</p>
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

            <div class="content-body m-top-20">

                <span class="ib-desc m-bottom-10">(단위: 건)</span>

                <table class="table-1 worker-payment__table">
                    <thead>
                    <tr class="head-desc">

                        <th rowspan="2" >
                            No
                        </th>
                        <th rowspan="2">
                            이름
                        </th>
                        <th rowspan="2" >
                            생년월일
                        </th>
                        <th rowspan="2">
                            입사일자
                        </th>
                        <th rowspan="2">
                            퇴사일자
                        </th>
                        <th colspan="12">
                            소급결제건수
                        </th>
                        <th rowspan="2">
                            소급결제<br>총건수
                        </th>
                    </tr>
                    <tr class="month-list">
                        @foreach ($months as $month)
                        <th>{{$year}}-{{$month}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="total">

                        <th colspan="5" class="t-left">소급결제 합계(결제비율)</th>
                        @foreach ($month_total as $month => $total)
                        <th>
                            {{ $total }}<br>
                            ({{$denominator[$month]}}%)
                        </th>
                        @endforeach
                        <th></th>
                    </tr>
                    @foreach ($lists as $list)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>{{ $list[0]->provider_name }}</td>
                            <td>{{ Custom::rsno_to_birth($list[0]->provider_birth) }}</td>
                            <td>{{ Custom::provider_key_to_join_date($list[0]->provider_key) }}</td>
                            <td>{{ Custom::provider_key_to_resign_date($list[0]->provider_key) }}</td>
                            @foreach ($months as $i => $month)
                            <td>
                                {{ $list[$i]->count ?? "" }}
                            </td>
                            @endforeach
                            <td>{{ $list[0]->total }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </article>

    </section>

    <script>
        $("input[name='from_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });
    </script>
@endsection
