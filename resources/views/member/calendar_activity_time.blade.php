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
                            <div class="m-bottom-5">이용시간</div>
                            @foreach ($schedule[$key]['time'] as $time)
                            <p>
                                {{ $time }}
                            </p>
                            @endforeach
                            <div class="use-time-info m-top-5">
                                <p>총시간: {{ $schedule[$key]['time_total'] }}</p>
                                <p>광역 {{ $schedule[$key]['type1'] }}, 기초 {{ $schedule[$key]['type2'] }}</p>
                            </div>
                        @endif
                    </div>
                </td>
            @endfor
        </tr>
    @endfor
    </tbody>
</table>
