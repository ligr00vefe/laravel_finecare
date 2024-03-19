@php
use App\Classes\Custom;
@endphp

@extends("layouts.layout")

@section("title")
    활동지원사 - 근무현황
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    <style>

        .table-provider {
            width: 60%;
            float: left;
        }

        .table-member {
            width: 40%;
            float: left;
            margin-top: 20px;
            text-align: center;
            border-left: none;
        }

        .table-member tr th {
            height: 45px;
            border-top: 1px solid #b7b7b7;
            color: #636363;
        }

        .table-member tr td {
            height: 40px;
            border-top: 1px solid #b7b7b7;
            color: #636363;
        }

        .content-body table {
            border-left: 1px solid #b7b7b7;
            border-right: 1px solid #b7b7b7;
            border-bottom: 1px solid #b7b7b7;
        }

    </style>

    @include("worker.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">

            <div class="head-top">

                <div class="top-info">
                    <h1>근무현황</h1>
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
                                <th>기준연월</th>
                                <td>
                                    <input type="text" name="from_date" id="from_date" class="input-datepicker" readonly autocomplete="off">
                                    <label for="from_date">
                                        <img src="/storage/img/icon_calendar.png" alt="기준연월 선택">
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

                    <ul>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/watch_icon.png" alt="총 이용시간">
                            </div>
                            <div class="text-wrap">
                                <p>총 이용시간</p>
                                <p><b class="acc-orange">{{ number_format($total['time_total'], 1) }}</b>시간</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/calendar_icon.png" alt="총 이용일수">
                            </div>
                            <div class="text-wrap">
                                <p>총 이용일수</p>
                                <p><b class="acc-orange">{{ number_format($total['day_total'], 1) }}</b>일</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/user_icon.png" alt="활동지원사">
                            </div>
                            <div class="text-wrap">
                                <p>활동지원사</p>
                                <p><b class="acc-orange">{{ number_format($total['workers']) }}</b>명</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/user_icon.png" alt="이용자">
                            </div>
                            <div class="text-wrap">
                                <p>이용자</p>
                                <p><b class="acc-orange">{{ number_format($total['member_count']) }}</b>명</p>
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

            <div class="content-body">

                <table class="service-list__table table-provider">
                    <colgroup>
                        <col width="3%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                    <tr>
                        <th rowspan="2" class="b-right-gray">
                            No
                        </th>
                        <th colspan="4" class="b-right-gray">
                            활동지원사
                        </th>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <th>생년월일</th>
                        <th>총이용시간</th>
                        <th class="b-right-gray">총이용일수</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($lists as $list)
                        <tr class="getMembersLog" data-key="{{ $list->provider_key }}">
                            <td>
                                {{ (($page-1)*15)+$loop->iteration }}
                            </td>
                            <td>
                                {{ $list->provider_name }}
                            </td>
                            <td>
                                {{ $list->provider_birth }}
                            </td>
                            <td>
                                {{ $list->payment_time }}
                            </td>
                            <td>
                                {{ Custom::DATE_COUNT_CONCAT_FORMAT($list->dateConcat) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <table class="table-member">
                    <thead>
                        <tr>
                            <th colspan="3" class="bg-sand">
                                이용자별 근로시간
                            </th>
                        </tr>
                        <tr>
                            <th class="bg-sand">이름</th>
                            <th class="bg-sand">생년월일</th>
                            <th class="bg-sand">이용시간</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">활동지원사를 선택해주세요</td>
                        </tr>
                    </tbody>
                </table>

                {!! pagination2(10, ceil($paging/15)) !!}

            </div>

        </article>

    </section>

    <style>
        .getMembersLog.on td {
            background-color: #fff2b9;
        }
    </style>

    <script>
        $("input[name='from_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });


        $(".getMembersLog").on("click", function () {

            $(".getMembersLog.on").removeClass("on");
            $(this).addClass("on");

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                dataType: 'html',
                url: '{{ route('worker.member.service.log') }}',
                data: {
                    key: $(this).data("key"),
                    from_date: '{{ $_GET['from_date'] ?? "" }}'
                },
                success: function(_res) {
                    var res = JSON.parse(_res);

                    var reloader = $(".table-member tbody");
                    reloader.empty();

                    $.each(res, function (i, v) {
                        var $list = "<tr>";
                        $list += "<td>"+v.target_name+"</td>";
                        $list += "<td>"+v.target_birth+"</td>";
                        $list += "<td>"+v.payment_time+"</td>";
                        $list += "</tr>";
                        reloader.append($list);
                    });

                }
            })
        });


    </script>
@endsection
