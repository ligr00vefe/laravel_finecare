@php
use \App\Classes\Custom;

$desc = $type == "member"
? "이용자 : <b>홍길동(02-03-27)</b>   활동지원사 : <b>홍길동(02-03-27)</b>    총 이용시간 : <b class='orange'>96.5시간</b>"
: "활동지원사 : <b>홍길동(02-03-27)</b>   이용자 : <b>홍길동(02-03-27)</b>    총 근로시간 : <b class='orange'>96.5시간</b>";
$total = $time_total ?? 0;

$members_str = "";

$workers = "";
$desc = "활동지원사 : <b>" .Custom::key_format_divide($provider_key)."</b> 이용자 : <b class='workers_str'>{$workers}</b>    총 이용시간 : <b class='orange'>{$total}시간</b>";
$helper_desc = "";
$dayAdd = 0;


$year = $year ?? date("Y");
$month = $month ?? date("m");



$daySchedule = [
    [
        "day"=>1,
        "time"=> [ "09:31 ~ 13:19", "13:50 ~ 15:45" ],
        "time_total" => 6,
        "type1" => 0,
        "type2" => 0
    ]
];

@endphp


<div class="calendar">
    <div class="calendar-head">
        <h1>{{ "{$year}년 {$month}월" }}</h1>
        <p>
            {!! $desc !!}
        </p>

        <ul>
            <li class="{{$type=="activity_time" ? "on" : ""}}">
                <button type="button" onclick="CALENDAR_CHANGE('activity_time')">서비스활동시간</button>
            </li>
            <li class="{{$type=="activity_kind" ? "on" : ""}}">
                <button type="button" onclick="CALENDAR_CHANGE('activity_kind')">서비스활동종류</button>
            </li>
        </ul>

    </div>

    <div class="calendar-wrap">
        @includeWhen($type=="activity_time", "worker.calendar_activity_time")
        @includeWhen($type=="activity_kind", "worker.calendar_activity_kind")
    </div>

    <script>

        function CALENDAR_CHANGE(type)
        {

            var provider_key = '{{ $provider_key }}';
            var target_key = $("td.target-clicked.on").data("target-id") ? $("td.target-clicked.on").data("target-id") : "";
            var from_date = $("input[name=from_date]").val();


            AjaxGetWorkData({
                type: type,
                provider_key: provider_key,
                from_date: from_date,
                target_key: target_key
            })
                .done(function (data) {

                    var res = data.data;
                    var members = data.members;
                    var timetable = data.timetable;

                    getCalendar({
                        type: type,
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
        }

    </script>



</div>
