@php
@endphp

@extends("layouts.layout")

@section("title")
    활동지원사 - 기간별 근무현황
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("worker.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">

            <div class="head-top">

                <div class="top-info">
                    <h1>기간별 근무현황</h1>
                    <ul>
                        <li>
                            <button type="button" class="orange">엑셀 내려받기</button>
                        </li>
                    </ul>
                </div>

                <div class="top-search">
                    <form action="">
                        <table class="table-auto">
                            <tr>
                                <th>기간</th>
                                <td>
                                    <input type="text" name="from_date" id="from_date" class="input-datepicker" readonly autocomplete="off">
                                    <label for="from_date">
                                        <img src="/storage/img/icon_calendar.png" alt="기준연월 선택">
                                    </label>
                                    <span>~</span>
                                    <input type="text" name="to_date" autocomplete="off" class="input-datepicker" readonly id="to_date">
                                    <label for="to_date">
                                        <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                                    </label>
                                </td>
                                <td>
                                    <button class="btn-black-small">조회</button>
                                </td>
                            </tr>
                        </table>

                        <div class="search-input">
                            <input type="text" name="term" placeholder="검색">
                            <button type="submit">
                                <img src="/storage/img/search_icon.png" alt="검색하기">
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="content-top">

                <div class="info-wrap">

                    <ul class="list-div-5">
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/calendar_icon.png" alt="총 근로일수">
                            </div>
                            <div class="text-wrap">
                                <p>총 근로일수</p>
                                <p><b class="acc-orange">8,010</b>일</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/watch_icon.png" alt="총 근로시간">
                            </div>
                            <div class="text-wrap">
                                <p>총 근로시간</p>
                                <p><b class="acc-orange">2,598</b>일</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/calendar_icon.png" alt="평균 근로일수">
                            </div>
                            <div class="text-wrap">
                                <p>평균 근로일수</p>
                                <p><b class="acc-orange">19.56</b>일</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/watch_icon.png" alt="월 평균 근로시간">
                            </div>
                            <div class="text-wrap">
                                <p>월 평균 근로시간</p>
                                <p><b class="acc-orange">113.3</b>시간</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/watch_icon.png" alt="일 평균 근로시간">
                            </div>
                            <div class="text-wrap">
                                <p>일 평균 근로시간</p>
                                <p><b class="acc-orange">5.3</b>시간</p>
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

            <div class="content-body m-top-20">

                <table class="table-1">
                    <colgroup>
                        <col width="2%">
                        <col width="3%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th rowspan="2">
                            <input type="checkbox" name="check_all" id="check_all">
                            <label for="check_all"></label>
                        </th>
                        <th rowspan="2" class="b-right-gray">
                            No
                        </th>
                        <th>이름</th>
                        <th>생년월일</th>
                        <th>총근로시간</th>
                        <th>총근무개월수</th>
                        <th>총근로일수</th>
                        <th>월평균 근로시간</th>
                        <th>일평균 근로시간</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for($i=0; $i<15; $i++)
                        <tr>
                            <td>
                                <input type="checkbox" name="id[]" id="id_{{$i}}">
                                <label for="id_{{$i}}"></label>
                            </td>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>홍길동</td>
                            <td>20-01-01</td>
                            <td>96.5</td>
                            <td>21</td>
                            <td>James bond</td>
                            <td>20-01-01</td>
                            <td>96.5</td>
                        </tr>
                    @endfor
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
