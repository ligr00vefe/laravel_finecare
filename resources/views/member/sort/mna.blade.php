@extends("layouts.layout")

@section("title")
    이용자 주장애-연령대별 현황
@endsection

<?php
$average_total_of_totals = $averageTotals = $totals = 0;
$average_totals = [];

$ages = [
    "0-3",
    "4-6",
    "7-12",
    "13-18",
    "19-30",
    "31-40",
    "41-50",
    "51-64",
    "65-300",
];


foreach ($totalType2 as $key => $total) {
    $totals += $total;
}
foreach ($averageTotalType2 as $key => $total) {
    $averageTotals += $total;
}
$ageTotal = [0, 0];

?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side_nav")

    <section id="member_wrap" class="list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>주장애-연령대별 현황</h1>
                {{--<div class="action-wrap">--}}
                {{--<ul>--}}
                {{--<li>--}}
                {{--<button>엑셀내려받기</button>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</div>--}}
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
                        0-3세
                    </th>
                    <th colspan="2">
                        4-6세
                    </th>
                    <th colspan="2">
                        7-12세
                    </th>
                    <th colspan="2">
                        13-18세
                    </th>
                    <th colspan="2">
                        19-30세
                    </th>
                    <th colspan="2">
                        31-40세
                    </th>
                    <th colspan="2">
                        41-50세
                    </th>
                    <th colspan="2">
                        51-64세
                    </th>
                    <th colspan="2">
                        65세-
                    </th>
                    <th colspan="2">
                        소계
                    </th>
                </tr>
                <tr class="table-header-info">
                    @for($i = 0; $i < count($ages)+1; $i++)
                        <th>실인원<br>(명)</th>
                        <th>연인원<br>(시간)</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach($columns as $key => $column)
                    <tr>
                        <td>{{ $column }}</td>
                        @foreach ($ages as $i => $range)
                            @php @$average_totals[$key] += $average = getAverageData($averageLists[$range][$column], $lists[$range][$column]); @endphp
                            <td>{{ $lists[$range][$column] }}</td>
                            <td>{{ $average  }}</td>
                        @endforeach
                        <td>{{ $totalType1[$column] }}</td>
                        <td>{{ $average_totals[$key] }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>계</th>
                    @foreach($ages as $key => $value)
                        @php @$average_total_of_totals += $average = getAverageData($averageTotalType2[$value], $totalType2[$value]); @endphp
                        <th>{{ $totalType2[$value] }}</th>
                        <th>{{$average}}</th>
                    @endforeach

                    <th>{{$totals}}</th>
                    <th>{{ $average_total_of_totals }}</th>
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
            dateFormat: "yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function (dateText, inst) {
                $("input[name='to_date']").datepicker({
                    minDate: new Date(dateText),
                    dateFormat: "yyyy-mm",
                    clearButton: false,
                    autoClose: true,
                })
            }
        });


        $("input[name='to_date']").datepicker({
            language: 'ko',
            dateFormat: "yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function (dateText, inst) {

            }
        })
    </script>
@endsection
