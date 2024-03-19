@extends("layouts.layout")

@section("title")
    이용자 - 서비스 이용 확인표
@endsection



@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side_nav")


    <section id="member_wrap" class="list_wrapper">

        <article id="wrap_head">
            <div class="head-top">

                <div class="top-info">
                    <h1>서비스 이용 확인표</h1>
                    {{--<ul>--}}
                        {{--<li>--}}
                            {{--<button type="button">캘린더 출력</button>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<button type="button" class="orange">엑셀 내려받기</button>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<button type="button" class="orange">전체 내려받기</button>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>

                <div class="top-search">
                    <form action="">
                        <input type="hidden" name="member_id" id="member_id" value="">
                        <input type="hidden" name="provider_id" id="provider_id" value="">
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
                                        <option value="1">서비스시작 시작일자 기준</option>
                                        {{--<option value="2">급여 적용연월 기준</option>--}}
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
                            <th>이용자</th>
                            <th>활동지원사</th>
                        </tr>
                        </thead>
                    </table>
                    <table class="service-checklist__table half rtl">
                        <tbody>
                        @foreach($members as $i => $member)
                        <tr class="load_helper" data-id="{{$member->target_key}}">
                            <td>
                                {{ $member->name . "(" . convert_birth($member->rsNo) . ")"  }}
                            </td>
                            <td>
                                {{ $i+1  }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <table class="service-checklist__table half">
                        <tbody id="helper_reloader">
                        </tbody>
                    </table>
                </div>

            </div>

            <div id="calendar_wrap" class="calendar-wrap">

            </div>

        </article>

    </section>

    <script>


        document.addEventListener("DOMContentLoaded", function() {
            calendar_reload({member_id: "", schedule: []})
                .done(function(response) {
                    $("#calendar_wrap").empty().append(response);
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


        $("tr.load_helper").on("click", function() {

            $(this).parents("table").find("tr").removeClass("on");
            $(this).addClass("on");

            var member_id = $(this).data("id");
            var calendar_type = $("#calendar_type").val();
            var call_ajax = get_data(calendar_type, member_id);

            $("input[name='member_id']").val(member_id);


            var data = CALENDARING({
                calendar_type: calendar_type,
                member_id: member_id,
                helper_id: ""
            }, 1);

            var helper_reloader = $("#helper_reloader");
            var $table = "";

            console.log(data)

            if (typeof data.helper != "undefined") {

                $.each(data.helper, function(i, v) {

                    var provider = v.provider_name + "(" +v.provider_birth.substring(0,2)+"-"+v.provider_birth.substring(2,4)+"-"+v.provider_birth.substring(4,6) + ")";

                    $table += "<tr class='load_helper_calendar_list' data-helper='"+ v.provider_key +"'>";
                    $table += "<td>";
                    $table += provider;
                    $table += "</td>";
                    $table += "</tr>";

                });

            }



            helper_reloader.empty().append($table);

        });

        $(document).on("click", "tr.load_helper_calendar_list", function() {

            $(this).parents("table").find("tr").removeClass("on");
            $(this).addClass("on");


            var helper_id = $(this).data("helper");
            var member_id = $("#member_id").val();
            var calendar_type = $("#calendar_type").val();

            CALENDARING({
                calendar_type: calendar_type,
                member_id: member_id,
                helper_id: helper_id
            });

        });

        function CALENDARING(data, helper_reload)
        {
            var save = [];
            console.log(data);
            console.log(helper_reload)
            console.log("asdasdasd")

            get_data(data.calendar_type, data.member_id, data.helper_id)

                .done(function(res) {
                    console.log("....")

                    console.log(res);

                    var helper_reloader = $("#helper_reloader");
                    var $table = "";

                    if (helper_reload == 1) {

                        $.each(res.helpers, function(i, v) {

                            var provider = v.provider_name + "(" +v.provider_birth.substring(0,2)+"-"+v.provider_birth.substring(2,4)+"-"+v.provider_birth.substring(4,6) + ")";

                            $table += "<tr class='load_helper_calendar_list' data-helper='"+ v.provider_key +"'>";
                            $table += "<td>";
                            $table += provider;
                            $table += "</td>";
                            $table += "</tr>";

                        });

                        helper_reloader.empty().append($table);
                    }


                    var schedule = [{day:999}];

                    $.each(res.sort, function(i, _data) {

                        var details = {};

                        if (data.calendar_type == "activity_time") {

                            details = case_activity_time(_data, i);

                        } else if (data.calendar_type == "activity_kind") {

                            details = case_activity_kind(_data, i);

                        }
                        schedule.push(details);
                    });



                    calendar_reload({
                        type: data.calendar_type,
                        member_id: data.member_id,
                        from_date: $("#from_date").val(),
                        schedule: schedule,
                        helpers: res.helpers,
                        time_total: res.total
                    }).done(function(_res) {
                        $("#calendar_wrap").empty().append(_res);
                    });

                });

        }

        function get_data(type, member_id, helper_id)
        {
            var helper_id = helper_id != "" ? helper_id : "";

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                url: '{{ route('member.calendar.list') }}',
                data: {
                    type: type,
                    member_id: member_id,
                    helper_id: helper_id,
                    date: $("#from_date").val(),
                    standard: $("#search_type").val(),
                    mistake: $("#payment_mistake_check_1").val(),
                }
            })
        }

        function calendar_reload(data)
        {
            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                dataType: 'html',
                url: '{{ route('member.calendar.reload') }}',
                data: {
                    type: data.type ? data.type : "activity_time",
                    member_id: data.member_id,
                    from_date: $("#from_date").val(),
                    schedule: data.schedule,
                    helpers: data.helpers,
                    time_total: data.time_total,
                }
            })
        }

        function case_activity_time(arr, day)
        {
            var timeArr = [];
            var timeDiff = 0;
            var type1 = 0;
            var type2 = 0;
            var time_total = 0;


            $.each(arr, function(key, _val) {
                var start_time = moment(_val.service_start_date_time, "YYYY-MM-DD HH:mm");
                var end_time = moment(_val.service_end_date_time, "YYYY-MM-DD HH:mm");
                timeArr.push(start_time.format("HH:mm") + " ~ " + end_time.format("HH:mm"));
                time_total += Number(_val.payment_time);
                type1 += Number(_val.type1);
                type2 += Number(_val.type2);
            });

            return {
                day: moment(day).format("D")*1,
                time: timeArr,
                time_total: time_total,
                type1: type1,
                type2: type2
            };
        }

        function case_activity_kind(arr, day)
        {

            var socialAdd = 0;
            var physicalAdd = 0;
            var housekeepingAdd = 0;
            var etcAdd = 0;
            var totalAdd = 0;


            $.each(arr, function(key, v) {

                socialAdd += cal_time(v.social_activity_support_time);
                physicalAdd += cal_time(v.physical_activity_support_time);
                housekeepingAdd += cal_time(v.housekeeping_activity_support_time);
                etcAdd += cal_time(v.etc_service_time);
                totalAdd += v.payment_time;

            });

            return {
                day: moment(day).format("D")*1,
                social: minute_to_hour(socialAdd),
                physical: minute_to_hour(physicalAdd),
                housekeeping: minute_to_hour(housekeepingAdd),
                etc: minute_to_hour(etcAdd),
                total: minute_to_hour(totalAdd)
            }

        }

    </script>

@endsection

