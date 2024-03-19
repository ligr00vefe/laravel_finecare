<?php
use App\Classes\Custom;
?>

<table>
    <thead>
    <tr>
        <th>일</th>
        <th>월</th>
        <th>화</th>
        <th>수</th>
        <th>목</th>
        <th>금</th>
        <th>토</th>
    </tr>
    </thead>
    <tbody>
    @for($i=0; $i<5; $i++)
        <tr>
            @for($j=0; $j<7; $j++)
                @php
                    $print_day = "";
                    /* 월의 첫 날짜 설정하기 */
                    if ($week_day >= ( ($i*6+1)+$j )) $print_day = "";
                    else $print_day = ++$dayAdd;

                    /* 월의 마지막날짜설정 "" 설정 */
                    if ($dayAdd > $endDay) $print_day = "";

                    $data_date = $print_day ? date("Y-m-d", strtotime($year."-".$month."-".$print_day)) : "";

                @endphp
                <td data-date="{{ $data_date }}">
                    @if (array_key_exists($data_date, $public_holiday))
                        <h3 style="color: red;">{{ $print_day }} {{ $public_holiday[$data_date] }}</h3>
                    @else
                        <h3>{{ $print_day }}</h3>
                    @endif
                    <div class="use-time-wrap m-top-5">
                        @if ($key = array_search($print_day, array_column($schedule, "day")))
                            <div class="m-bottom-5">총시간: {{ Custom::timeAdd($schedule[$key]['social'], $schedule[$key]['physical'], $schedule[$key]['housekeeping'], $schedule[$key]['etc']) }} </div>
                            <p>
                                사회활동지원: {{ Custom::time_format($schedule[$key]['social']) }}
                            </p>
                            <p>
                                신체활동지원: {{ Custom::time_format($schedule[$key]['physical']) }}
                            </p>
                            <p>
                                가사활동지원: {{ Custom::time_format($schedule[$key]['housekeeping']) }}
                            </p>
                            <p>
                                기타서비스: {{ Custom::time_format($schedule[$key]['etc']) }}
                            </p>
                        @endif
                    </div>
                </td>
            @endfor
        </tr>
    @endfor
    </tbody>
</table>
