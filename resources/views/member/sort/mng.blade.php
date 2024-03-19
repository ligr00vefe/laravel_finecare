@extends("layouts.layout")

@section("title")
    이용자 주장애-등급별 현황
@endsection

@php
    $columns = [
        "지체장애", "뇌병변장애", "시각장애", "청각장애", "언어장애", "지적장애", "자폐성장애",
        "정신장애", "신장장애", "성장장애", "호흡기장애", "간장애", "안면장애", "장루장애 및 유루장애",
        "간질장애", "발달장애", "중복장애", "미등록"
    ];

    $average_totals = $average_disable_total = $disable_total = [];
    $average_grade_total = $grade_total = [ 0, 0, 0, 0, 0, 0, 0, 0 ];
    $average_total_of_totals = $average_total_of_total = $total_of_total = 0;


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

    // 연인원
     foreach ($averageLists as $i=>$list)
        {
            $average_disable_total[$i] = 0;
            foreach ($list as $val)
            {
                $average_disable_total[$i] += $val;
                $average_total_of_total += $val;
            }
        }

        foreach ($averageLists as $i => $list)
        {
            for ($j=0; $j<8; $j++)
            {
                $average_grade_total[$j] += $list[$j];
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
                    @for($i = 0; $i < count($grade_total)+1; $i++)
                        <th>실인원<br>(명)</th>
                        <th>연인원<br>(시간)</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach($columns as $key => $column)
                    <tr>
                        <td>{{ $column }}</td>
                        @for($i=0; $i < count($grade_total); $i++)
                            @php @$average_totals[$key] += $average = getAverageData($averageLists[$key][$i], $lists[$key][$i]); @endphp
                            <td>{{ number_format($lists[$key][$i]) }}</td>
                            <td>{{$average}}</td>
                        @endfor
                        <td>{{ number_format($disable_total[$key]) }}</td>
                        <td>{{$average_totals[$key]}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>계</th>
                    @for($i=0; $i < count($grade_total); $i++)
                        @php @$average_total_of_totals += $average = getAverageData($average_grade_total[$i], $grade_total[$i]); @endphp
                        <th>{{ number_format($grade_total[$i]) }}</th>
                        <th>{{$average}}</th>
                    @endfor
                    <th>{{ number_format($total_of_total) }}</th>
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
