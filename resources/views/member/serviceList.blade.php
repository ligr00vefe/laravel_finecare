@extends("layouts.layout")

@section("title")
    서비스 이용현황
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    <style>

        .empty {
            border: 1px solid #b7b7b7;
            border-top: none;
            padding: 30px;
        }

    </style>

    @include("member.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">

            <div class="head-top">

                <div class="top-info">
                    <h1>서비스 이용현황</h1>
                    {{--<ul>--}}
                        {{--<li>--}}
                            {{--<button type="button" class="orange">전체 내려받기</button>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>

                <div class="top-search">
                    <form action="">
                        <table class="table-auto">
                            <tr>
                                <th>기준연월</th>
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
                                <p><b class="acc-orange">{{ number_format($total['time_total']) }}</b>시간</p>
                            </div>
                        </li>
                        <li>
                            <div class="img-wrap">
                                <img src="{{__IMG__}}/calendar_icon.png" alt="총 이용일수">
                            </div>
                            <div class="text-wrap">
                                <p>총 이용일수</p>
                                <p><b class="acc-orange">{{ number_format($total['day_total']) }}</b>일</p>
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
                                <p><b class="acc-orange">{{ number_format($total['members']) }}</b>명</p>
                            </div>
                        </li>

                    </ul>

                </div>

            </div>

            <div class="content-body">

                <table class="service-list__table member" id="dataTable">
                    {{--<colgroup>--}}

                        {{--<col width="3%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                        {{--<col width="10%">--}}
                    {{--</colgroup>--}}
                    <thead>
                    <tr>

                        <th rowspan="2" class="b-right-gray">
                            No
                        </th>
                        <th colspan="4" class="b-right-gray">
                            이용자
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
                    @forelse ($lists as $key => $list)
                        <tr class="getWorkersLog" data-key="{{ $key }}">
                            <td>
                                {{ (($page - 1) * 15) + ($loop->iteration) }}
                            </td>
                            <td>{{ $list['name'] }}</td>
                            <td>{{ $list['birth'] }}</td>
                            <td>{{ $list['total_time'] }}</td>
                            <td>{{ count($list['day_count']) }}</td>
                        </tr>
                    @empty
                        {{--<tr>--}}
                            {{--<td colspan="8" class="empty">--}}
                                {{--데이터가 없습니다.--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    @endforelse
                    </tbody>
                </table>

                <table class="service-list__table worker">
                    <thead>
                    <tr>
                        <th colspan="3" class="bg-sand">
                            활동지원사별 이용시간
                        </th>
                    </tr>
                    <tr>
                        <th class="bg-sand">이름</th>
                        <th class="bg-sand">생년월일</th>
                        <th class="bg-sand">이용시간</th>
                    </tr>
                    </thead>
                    <tbody class="worker_list_reload_selector">
                    <tr>
                    </tr>
                    </tbody>
                </table>

            </div>

            {!! pagination2(10, ceil($paging/15)) !!}


        </article>

    </section>

    <style>
        .getWorkersLog.on td {
            background-color: #fff2b9;
        }
    </style>

    <script>

        $(".getWorkersLog").on("click", function () {

            $(".getWorkersLog.on").removeClass("on");
            $(this).addClass("on");

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                dataType: 'html',
                url: '{{ route('member.service.log.worker') }}',
                data: {
                    key: $(this).data("key"),
                    from_date: '{{ $_GET['from_date'] ?? "" }}'
                },
                success: function(_res) {
                    var res = JSON.parse(_res);

                    var reloader = $(".worker_list_reload_selector");
                    reloader.empty();

                    $.each(res, function (i, v) {
                        var $list = "<tr>";
                        $list += "<td>"+v.name+"</td>";
                        $list += "<td>"+v.birth+"</td>";
                        $list += "<td>"+v.time+"</td>";
                        $list += "</tr>";
                        reloader.append($list);
                    });

                }
            })

        });


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
