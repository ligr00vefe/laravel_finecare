@extends("layouts.layout")

@section("title")
    활동지원사 - 서비스 이용 확인표
@endsection

<?php
$members = [];
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    <style>
        .service-checklist__table.half {
            width: 63%;
        }
    </style>

    @include("worker.side_nav")


    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">
            <div class="head-top">

                <div class="top-info">
                    <h1>서비스 이용 확인표</h1>
{{--                    <ul>--}}
{{--                        <li>--}}
{{--                            <button type="button">캘린더 출력</button>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <button type="button" class="orange">엑셀 내려받기</button>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <button type="button" class="orange">전체 내려받기</button>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
                </div>

                <div class="top-search">
                    <form action="">
                        <input type="hidden" name="provider_id" id="provider_id" value="">
                        <input type="hidden" name="member_id" id="member_id" value="">
                        <input type="hidden" name="calendar_type" id="calendar_type" value="activity_time">
                        <table>
                            <colgroup>
                                <col width="60px">
                                <col width="340px">
                                <col width="100px">
                                <col width="120px">
                                <col width="auto">
                            </colgroup>
                            <tr>
                                <th>
                                    <label for="from_date">기준연월</label>
                                </th>
                                <td>
                                    <input type="text" name="from_date" id="from_date" class="input-datepicker" readonly autocomplete="off" value="{{ $_GET['from_date'] ?? date("Y-m") }}">
                                    <label for="from_date">
                                        <img src="/storage/img/icon_calendar.png" alt="기준연월 선택">
                                    </label>
                                    <select name="search_type" id="search_type">
                                        <option value="1">서비스시작 종료일자 기준</option>
                                    </select>
                                </td>
                                {{--<th>--}}
                                {{--과오결제 조회--}}
                                {{--</th>--}}
                                {{--<td>--}}
                                {{--<input type="radio" name="payment_mistake_check" id="payment_mistake_check_1" value="1">--}}
                                {{--<label for="payment_mistake_check_1">제외</label>--}}
                                {{--<input type="radio" name="payment_mistake_check" id="payment_mistake_check_2" value="2">--}}
                                {{--<label for="payment_mistake_check_2">포함</label>--}}
                                {{--</td>--}}
                                <td>
                                    <button class="btn-black-small">조회</button>
                                </td>
                                <th></th>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

        </article>

        <article id="wrap_content">

            <div class="member-list">

                <div class="service-checklist__table-wrap">
                    <table class="service-checklist__table">
                        <thead>
                        <tr>
                            <th>순번</th>
                            <th style="width: 33%;">활동지원사</th>
                            <th>이용자</th>
                        </tr>
                        </thead>
                    </table>
                    <table class="service-checklist__table half rtl">
                        <tbody>
                        @foreach($lists as $i => $list)
                            <tr class="load_helper" data-id="{{$list->provider_key}}">
                                <td>
                                    {{ $list->provider_name . "(" . convert_birth($list->provider_birth) . ")"  }}
                                </td>
                                <td>
                                    {{ $i+1 }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="service-checklist__table half" style="width: 37%">
                        <tbody id="member_reloader">
                        </tbody>
                    </table>
                </div>

            </div>

            <div id="calendar_wrap" class="calendar-wrap">

            </div>

        </article>

    </section>

    <style>
        .work {
            background-color: #fff2b9
        }

        .off {
            background-color: #ffcbd4;
        }
    </style>

    <script>


        document.addEventListener("DOMContentLoaded", function() {
            // calendar_reload({member_id: "", schedule: []})
            //     .done(function(response) {
            //         $("#calendar_wrap").empty().append(response);
            //     })
        });


        $("input[name='from_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });


        $("tr.load_helper").on("click", function() {

            $(this).parents("table").find("tr.on").removeClass("on");
            $(this).addClass("on");

            var provider_key = $(this).data("id");
            var from_date = $("input[name=from_date]").val();

            AjaxGetWorkData({
                provider_key: provider_key,
                from_date: from_date,
                type: "activity_time"
            })
            .done(function (data) {

                var res = data.data;
                var members = data.members;
                var member_reloader = $("#member_reloader");
                var timetable = data.timetable;

                getCalendar({
                    type: "activity_time",
                    provider_key: provider_key,
                    from_date: from_date,
                    schedule: JSON.stringify(res),
                    timetable: JSON.stringify(timetable)
                })
                .done(function (_res) {

                    $("#calendar_wrap").empty().append(_res);
                    member_reloader.empty();
                    var str = "";
                    var workers_str = "";
                    $.each(members, function (i, v) {
                        str += "<tr>";
                        str += "<td class='target-clicked' data-id='"+ provider_key +"' data-target-id='"+v.target_key+"'>"+v.target_key+"</td>";
                        str += "</tr>";

                        if (workers_str != "")  workers_str += ", ";
                        workers_str += v.target_key;
                    });

                    member_reloader.append(str);

                    $("b.workers_str").text(workers_str);
                });

            });
        });

        $(document).on("click", ".target-clicked", function () {

            $(this).parents("table").find("td.on").removeClass("on");
            $(this).addClass("on");

            var provider_key = $(this).data("id");
            var target_key = $(this).data("target-id");
            var from_date = $("input[name=from_date]").val();

            AjaxGetWorkData({
                provider_key: provider_key,
                from_date: from_date,
                target_key: target_key,
                type: "activity_time",
            })
                .done(function (data) {

                    var res = data.data;
                    var members = data.members;
                    var timetable = data.timetable;

                    getCalendar({
                        type: "activity_time",
                        provider_key: provider_key,
                        from_date: from_date,
                        schedule: JSON.stringify(res),
                        timetable: JSON.stringify(timetable)
                    })
                        .done(function (_res) {
                            $("#calendar_wrap").empty().append(_res);
                            var str = "";
                            var workers_str = "";
                            $.each(members, function (i, v) {
                                str += "<tr>";
                                str += "<td class='target-clicked' data-id='"+ provider_key +"' data-target-id='"+v.target_key+"'>"+v.target_key+"</td>";
                                str += "</tr>";

                                if (workers_str != "")  workers_str += ", ";
                                workers_str += v.target_key;
                            });

                            $("b.workers_str").text(workers_str);
                        });

                });

        });

        function AjaxGetWorkData(obj) {

            var url = obj.type == "activity_time" ? "{{ route('helper.calendar.time') }}" : "{{ route("helper.calendar.kind") }}";

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "post",
                url: url,
                dataType: 'json',
                data: obj
            })
        }

        function getCalendar(obj) {

            if (!obj.type) obj.type = "activity_time";

            var url = obj.type == "activity_time" ? "{{ route('helper.calendar.time.render') }}" : '{{ route('helper.calendar.kind.render') }}';

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "post",
                url: url,
                dataType: 'html',
                data: {
                    type: obj.type,
                    provider_key: obj.provider_key,
                    from_date: obj.from_date,
                    schedule: obj.schedule,
                    timetable: obj.timetable
                }
            })
        }

    </script>

@endsection

