@php
use \App\Classes\Custom;
@endphp

{{--{{dd($lists)}}--}}
<style>
    table th {
        background-color: #363636;
        color: white;
        padding: 10px;
    }
</style>

<div id="recalcOffPay" class="recalcOffPay">

    <table class="recalc-table">
        <tr>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                No
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                대상자명
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                생년월일
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                입사일
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                퇴사일
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                1년차 미만
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                1월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                2월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                3월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                4월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                5월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                6월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                7월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                8월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                9월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                10월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                11월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                12월
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                사용 연차 수
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                총 연차 수
            </th>
            <th style="background-color: #363636; color: white; padding: 10px; text-align: center; height: 35px; vertical-align: center">
                연차수당
            </th>
        </tr>
        @foreach ($lists as $list)
            <tr data-provider="{{ $list->target_key }}">
                <td style="border: 1px solid black; text-align: left;">
                    {{ $loop->iteration }}
                </td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ $list->name }}
                </td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ rsno_to_birth($list->birth) }}
                </td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ $list->join_date }}
                </td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ $list->resign_date }}
                </td>
                <td style="border: 1px solid black; text-align: center;">
                    {{ $list->bool_less_than_one_year ? "1년 미만자" : "" }}
                </td>

                @foreach (range(1, 12) as $month)
                    <td style="border: 1px solid black">
                        <p>
                            {{ $list->offdays->$month ?? 0 }}
                        </p>
                    </td>
                @endforeach
                <td style="border: 1px solid black">
                    {{ $list->year_off_day }}
                </td>
                <td style="border: 1px solid black">
                    {{ $list->off_day_total }}
                </td>
                <td style="border: 1px solid black; text-align: right;">
                    {{ number_format($list->off_pay) }}
                </td>
            </tr>
        @endforeach
    </table>
</div>


