@extends("layouts.layout")

@section("title")
    이용자 생활유형-연령대별 현황
@endsection

<?php
$average_total_of_totals = $average_total = $total = 0;
$average_totals = [];

$types = [
    "가",
    "나",
    "다",
    "라",
    "마",
    "바",
    "미등록",
];

$grades = [
    "1등급",
    "2등급",
    "3등급",
    "4등급",
    "5등급",
    "6등급",
    "7등급",
    "8등급",
    "9등급",
    "10등급",
    "11등급",
    "12등급",
    "13등급",
    "14등급",
    "15등급",
    "-"
];


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
                        활동지원등급/유형
                    </th>
                    <th colspan="2">
                        가
                    </th>
                    <th colspan="2">
                        나
                    </th>
                    <th colspan="2">
                        다
                    </th>
                    <th colspan="2">
                        라
                    </th>
                    <th colspan="2">
                        마
                    </th>
                    <th colspan="2">
                        바
                    </th>
                    <th colspan="2">
                        미등록
                    </th>
                    <th colspan="2">
                        소계
                    </th>
                </tr>
                <tr class="table-header-info">
                    @for($i = 0; $i < count($types)+1; $i++)
                        <th>실인원<br>(명)</th>
                        <th>연인원<br>(시간)</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach($grades as $key => $grade)
                    <tr>
                        <td>{{ $grade }}</td>
                        @foreach ($types as $type)
                            @php @$average_totals[$key] += getAverageData($averageLists[$type][$grade]['people'] ?? 0, $lists[$type][$grade]['people'] ?? 0); @endphp
                            <td>{{ $lists[$type][$grade]['people'] ?? 0 }}</td>
                            <td>{{ getAverageData($averageLists[$type][$grade]['people'] ?? 0, $lists[$type][$grade]['people'] ?? 0) }}</td>
                        @endforeach

                        <td>{{ $lists['소계'][$grade]['people'] }}</td>
                        <td>{{ $average_totals[$key] }}</td>
                        @php $total += $lists['소계'][$grade]['people']; @endphp
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>계</th>
                    @foreach ($types as $type)
                        @php @$average_total_of_totals += $average = getAverageData($averageLists['소계'][$type]['people'], $lists['소계'][$type]['people']); @endphp
                        <th>{{ $lists['소계'][$type]['people'] }}</th>
                        <th>{{ $average }}</th>
                    @endforeach

                    <th>{{ $total }}</th>
                    <th>
                        {{ $average_total_of_totals }}
                    </th>
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
