@extends("layouts.layout")

@section("title")
    이용자 - 주장애-등급별 현황
@endsection

@php
$columns = [
    "지체장애", "뇌병변장애", "시각장애", "청각장애", "언어장애", "지적장애", "자폐성장애",
    "정신장애", "신장장애", "성장장애", "호흡기장애", "간장애", "안면장애", "장루장애 및 유루장애",
    "간질장애", "발달장애", "중복장애", "미등록"
];

$disable_total = [];
$grade_total = [ 0, 0, 0, 0, 0, 0, 0, 0 ];
$total_of_total = 0;

foreach ($lists as $i=>$list)
{
    $disable_total[$i] = 0;
    foreach ($list as $val)
    {
        $disable_total[$i] += $val;
        $total_of_total += $val;
    }
}

foreach ($lists as $i => $list)
{
    for ($j=0; $j<8; $j++)
    {
        $grade_total[$j] += $list[$j];
    }
}
@endphp

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>주장애-등급별 현황</h1>
                <div class="action-wrap">
                    <ul>
                        <li>
                            <button>엑셀내려받기</button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="search-wrap">
                <form action="" method="post" name="member_list_search">
                    <div class="limit-wrap">
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <span>~</span>
                        <input type="text" name="to_date" autocomplete="off" readonly id="to_date">
                        <label for="to_date">
                            <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                        </label>
                        <button type="submit">조회</button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents">
            <table class="table-1 table-2">
                <thead>
                <tr class="table-header">
                    <th rowspan="2">
                        주장애명
                    </th>
                    <th colspan="2">
                        1급
                    </th>
                    <th colspan="2">
                        2급
                    </th>
                    <th colspan="2">
                        3급
                    </th>
                    <th colspan="2">
                        4급
                    </th>
                    <th colspan="2">
                        5급
                    </th>
                    <th colspan="2">
                        6급
                    </th>
                    <th colspan="2">
                        미등록
                    </th>
                    <th colspan="2">
                        기타
                    </th>
                    <th colspan="2">
                        소계
                    </th>
                </tr>
                <tr class="table-header-info">
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                    <th>
                        실인원<br>(명)
                    </th>
                    <th>
                        연인원<br>(시간)
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($columns as $key => $column)
                <tr>
                    <td>
                        {{ $column }}
                    </td>
                    <td>
                        {{ number_format($lists[$key][0]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][1]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][2]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][3]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][4]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][5]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][5]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][6]) }}
                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($lists[$key][7]) }}
                    </td>
                    <td>

                    </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>
                        계
                    </th>
                    <th>{{ number_format($grade_total[0]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[1]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[2]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[3]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[4]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[5]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[6]) }}</th>
                    <th></th>
                    <th>{{ number_format($grade_total[7]) }}</th>
                    <th></th>
                    <th>{{ number_format($total_of_total) }}</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">

        </article> <!-- article list_bottom end -->

    </section>


    <script>
        $("input[name='from_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function(dateText, inst) {
                $("input[name='to_date']").datepicker({
                    minDate: new Date(dateText),
                    dateFormat:"yyyy-mm",
                    clearButton: false,
                    autoClose: true,
                })
            }
        });


        $("input[name='to_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function(dateText, inst) {

            }
        })
    </script>
@endsection
