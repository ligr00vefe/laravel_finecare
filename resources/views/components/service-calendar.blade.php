@php

$desc = $type == "member"
? "이용자 : <b>홍길동(02-03-27)</b>   활동지원사 : <b>홍길동(02-03-27)</b>    총 이용시간 : <b class='orange'>96.5시간</b>"
: "활동지원사 : <b>홍길동(02-03-27)</b>   이용자 : <b>홍길동(02-03-27)</b>    총 근로시간 : <b class='orange'>96.5시간</b>";

$dayAdd = 0;



@endphp


<div class="calendar">
    <div class="calendar-head">

        <h1>{{ "{$year}년 {$month}월" }}</h1>
        <p>
            {!! $desc !!}
        </p>

        <ul>
            <li class="on">
                <button type="button">서비스활동시간</button>
            </li>
            <li>
                <button type="button">서비스활동종류</button>
            </li>
        </ul>

    </div>

    <div class="calendar-wrap">

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
                    <h3>{{ $print_day }}</h3>
                    <div class="use-time-wrap m-top-5">

                    </div>
                </td>
                @endfor
            </tr>
            @endfor
            </tbody>
        </table>

    </div>

</div>
