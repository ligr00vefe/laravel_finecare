@extends("layouts/layout")

@section("title")
    연차수당 재정산 차액신청
@endsection

@section("content")

    @include("salary.side_nav")
    <link rel="stylesheet" href="/css/member/index.css">

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>연차수당 재정산 차액신청</h1> <span></span>
            </div>

        </article>

        <div class="search-form">
            <form action="">

                <span>년 검색</span>

                <input type="text" class="from_date" name="target_ym" id="from_date" autocomplete="off" readonly value="{{ $target_ym ?? "" }}">
                <label for="from_date">
                    <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                </label>

                <span>근로자명</span>
                <input type="text" name="provider_name" value="{{ $provider_name ?? "" }}">

                <span>생년월일</span>
                <input type="text" name="provider_birth" placeholder="ex) 200101" value="{{ $provider_birth ?? "" }}">

                <button>검색</button>

            </form>
        </div>

        @if ($lists)
            <article id="list_contents" class="m-top-20">
                <table class="recalc-table">
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            대상자명
                        </th>
                        <th>
                            생년월일
                        </th>
                        <th>
                            입사일
                        </th>
                        <th>
                            퇴사일
                        </th>
                        <th>
                            급여 계산 개월
                        </th>
                        <th>
                            1년차 미만
                        </th>
                        <th>
                            사용 연차 수
                        </th>
                        <th>
                            총 연차 수
                        </th>
                        <th>
                            연차수당
                        </th>
                        <th>
                            기지급 연차수당
                        </th>
                        <th>
                            차액
                        </th>
                    </tr>
                    @foreach ($lists as $list)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $list['recalc']->provider_key }}
                        </td>
                        <td>
                            {{ $list['recalc']->provider_key }}
                        </td>
                        <td>
                            {{ $list['recalc']->join_date }}
                        </td>
                        <td>
                            {{ $list['recalc']->resign_date }}
                        </td>
                        <td>
                            {{ $list['calc']->payment_month }}
                        </td>
                        <td>
                            {{ $list['recalc']->less_than_one_year == 1 ? "1년 미만" : "" }}
                        </td>
                        <td>
                            {{ $list['recalc']->use_off_day }}
                        </td>
                        <td>
                            {{ $list['recalc']->total_off_day }}
                        </td>
                        <td>
                            {{ number_format($list['recalc']->off_day_price_total) }}
                        </td>
                        <td>
                            {{ number_format($list['calc']->sumOffDayPayment) }}
                        </td>
                        <td>
                            {{ number_format($list['recalc']->off_day_price_total - $list['calc']->sumOffDayPayment) }}
                        </td>
                    </tr>
                    @endforeach
                </table>
            </article> <!-- article list_contents end -->
        @endif
    </section>

    <style>

        .recalc-table {
            border-right: 2px solid white;
            border-left: 2px solid white;
        }

        .recalc-table th,
        .recalc-table td {
            border: 1px solid #9c9c9c;
            text-align: center;
        }

        .recalc-table th {
            background-color: #E7E7E7;
            height: 45px;
            color: #414141;
        }

        .recalc-table tr:nth-child(2n + 1) td {
            background-color: #F3F1F2;
        }


    </style>

    <script>
        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy",
                view: 'years',
                minView: 'years',
                clearButton: false,
                autoClose: true,
            });

        });


        $(document).ready(function() {
            $('#calc_result').DataTable({
                searching: false,
                lengthChange: false,
                scrollX:        "2400px",
                scrollY:        "600px",

                scrollCollapse: false,
                paging:         false,
                fixedColumns:   {
                    leftColumns: 4,
                }
            });
        } );
    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


    <style>
        .search-form {
            width: 100%;
            height: 80px;
            line-height: 80px;
            background-color: #e8e8e8;
            padding: 0 20px;
            font-size: 15px;
        }

        .search-form input {
            width: 120px;
            height: 25px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .search-form button {
            background-color: #363636;
            width: 50px;
            height: 30px;
            line-height: 30px;
            color: white;
            text-align: center;
            border:none;
        }

        label[for='from_date'] {
            margin-left: -35px;
            margin-right: 20px;
            vertical-align: middle;
        }


        .search-form input[name=birth] {
            margin-right: 5px;
        }
    </style>

@endsection
