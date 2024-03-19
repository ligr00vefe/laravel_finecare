@php
use \App\Classes\Custom;

$desc = $type == "member"
? "이용자 : <b>홍길동(02-03-27)</b>   활동지원사 : <b>홍길동(02-03-27)</b>    총 이용시간 : <b class='orange'>96.5시간</b>"
: "활동지원사 : <b>홍길동(02-03-27)</b>   이용자 : <b>홍길동(02-03-27)</b>    총 근로시간 : <b class='orange'>96.5시간</b>";
$total = $time_total ?? 0;
$member = $member_id ?? "홍길동000000";
$member_key = Custom::key_format_divide($member);
$workers = "";

if (isset($helpers)) {

    foreach ($helpers as $helper) {
        if($workers != "") $workers .= ", ";
        $workers .= Custom::key_format_divide($helper['provider_key']);
    }

}



$desc = "이용자 : <b>{$member_key}</b> 활동지원사 : <b>{$workers}</b>    총 이용시간 : <b class='orange'>{$total}시간</b>";
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
        @includeWhen($type=="activity_time", "member.calendar_activity_time")
        @includeWhen($type=="activity_kind", "member.calendar_activity_kind")
    </div>

    <script>

        function CALENDAR_CHANGE(type)
        {

            var calendar_type = "{{ $type }}";
            var member_id = $("input[name='member_id']").val();

            if (type == calendar_type) return;

            $("#calendar_type").val(type);

            get_data(type, member_id)
                .done(function(res) {

                    var sort = [{day:999}];

                    $.each(res.sort, function(i, val) {

                        var details = {};

                            if (type == "activity_time") {
                                details = case_activity_time(val, i);
                            } else if (type == "activity_kind") {
                                details = case_activity_kind(val, i);
                            }

                        sort.push(details);

                    });

                    calendar_reload({
                        type: type,
                        member_id: $("input[name='member_id']").val(),
                        from_date: $("#from_date").val(),
                        schedule: sort,
                        helpers: res.helpers,
                        time_total: res.total
                    }).done(function(res) {
                        $("#calendar_wrap").empty().append(res);
                    })

                })
        }

    </script>

</div>
