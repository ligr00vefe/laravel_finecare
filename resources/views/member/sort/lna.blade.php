@extends("layouts.layout")

@section("title")
    이용자 생활유형-연령대별 현황
@endsection

<?php

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

$lifetypes = [
    "기초생활수급자",
    "차상위계층",
    "일반",
    "-",
];

$average_total_of_totals = $averageTotal = $total = 0;
$average_totals = [];
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side_nav")

    <section id="member_wrap" class="list_wrapper">
        <article id="list_head">

            <div class="head-info">
                <h1>주장애-등급별 현황</h1>
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
                        생활유령
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
                @foreach($lifetypes as $key => $type)
                    <?php
                    $average_type1 = $type1 = 0;
                    $average_type2 = $type2 = 0;
                    $average_type3 = $type3 = 0;
                    $average_type4 = $type4 = 0;
                    ?>
                    <tr>
                        <td>
                            {{ $type }}
                        </td>
                        @foreach ($ages as $range)
                            <?php
                            if ($key == "기초생활수급자") {
                                $type1 += $lists[$range][$type];
                                $average_type1 += $averageLists[$range][$type];
                            } else if ($key == "차상위계층") {
                                $type2 += $lists[$range][$type];
                                $average_type2 += $averageLists[$range][$type];
                            } else {
                                $type3 += $lists[$range][$type];
                                $average_type3 += $averageLists[$range][$type];
                            }
                            $total += $lists[$range][$type];
                            $averageTotal += $averageLists[$range][$type];
                            @$average_totals[$key] += $average = getAverageData($averageLists[$range][$type], $lists[$range][$type]);
                            ?>
                            <td>{{ $lists[$range][$type] }}</td>
                            <td>{{ $average }}</td>
                        @endforeach

                        <td>
                            @if ($key == "기초생활수급자")
                                {{ $type1 }}
                            @elseif ($key == "차상위계층")
                                {{ $type2 }}
                            @else
                                {{ $type3 }}
                            @endif
                        </td>
                        <td>
                            @if ($key == "기초생활수급자")
                                {{ $average_totals[$key] }}
                            @elseif ($key == "차상위계층")
                                {{ $average_totals[$key] }}
                            @else
                                {{ $average_totals[$key] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>계</th>
                    @foreach ($ages as $range)
                        @php @$average_total_of_totals += $average = getAverageData($averageTotalType1[$range], $totalType1[$range]); @endphp
                        <th>{{ $totalType1[$range] }}</th>
                        <th>{{ $average }}</th>
                    @endforeach
                    <th>{{ $total }}</th>
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
