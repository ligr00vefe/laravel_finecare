@extends("layouts/layout")

@section("title")
    급여관리 - 급여대장
@endsection

<?php
//pp($lists);
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">
        <article id="list_head">

            <div class="head-info">
                <h1>급여대장</h1>

            </div>

             <div>
                 <form action="">
                     <input type="text" name="from_date" id="from_date" value="{{ $_GET['from_date'] ?? "" }}" class="input-datepicker" autocomplete="off" readonly>
                     <label for="from_date">
                         <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                     </label>
                     <button class="btn-black-small">조회</button>
                 </form>
             </div>

            <div class="contents m-top-10">
                {{--@if ($lists)--}}
                <div class="" >
                    <table id="list_table" class="stripe row-border order-column"> {{-- 22개 --}}
                        <thead>
                        <tr>
                            <td rowspan="3">
                                No
                            </td>
                            <td colspan="2">
                                기본사항
                            </td>
                            <td colspan="9">
                                지급항목
                            </td>
                            <td colspan="7">
                                공제항목
                            </td>
                        </tr>
                        <tr>
                            <td>성명</td>
                            <td>입사일시</td>
                            <td>기본급</td>
                            <td>휴일수당</td>
                            <td>야근수당</td>
                            <td>주휴수당</td>
                            <td>연차수당</td>
                            <td>근로자의 날 수당</td>
                            <td rowspan="2">
                                적용합계
                            </td>
                            <td rowspan="2">
                                법정제수당
                            </td>
                            <td rowspan="2">
                                지급총액
                            </td>
                            <td rowspan="2">
                                국민연금
                            </td>
                            <td rowspan="2">
                                건강보험
                            </td>
                            <td rowspan="2">
                                고용보험
                            </td>
                            <td rowspan="2">
                                갑근세
                            </td>
                            <td rowspan="2">
                                주민세
                            </td>
                            <td rowspan="2">
                                공제합계
                            </td>
                            <td rowspan="2">
                                차인지급액
                            </td>
                        </tr>
                        <tr>
                            <td>생년월일</td>
                            <td>근무일시</td>
                            <td>시간</td>
                            <td>시간</td>
                            <td>시간</td>
                            <td>시간</td>
                            <td>시간</td>
                            <td>공휴일 유급휴일 수당</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($lists as $key => $list)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div>{{ $list['worker']->provider_name }}</div>
                                <div>{{ extractNumber($list['worker']->provider_key) }}</div>
                            </td>
                            <td>
                                <div>{{ $list['worker']->join_date }}</div>
                                <div>{{ $list['voucher']->voucher_total_day_count }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['pay_basic'] }}</div>
                                <div>{{ $list['payment']['time_basic'] }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['pay_holiday'] }}</div>
                                <div>{{ $list['payment']['time_holiday'] }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['pay_night'] }}</div>
                                <div>{{ $list['payment']['time_night'] }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['standard_weekly_payment'] }}</div>
                                <div>{{ $list['payment']['standard_weekly_time'] }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['standard_yearly_payment'] }}</div>
                                <div>{{ $list['payment']['standard_yearly_time'] }}</div>
                            </td>
                            <td>
                                <div>{{ $list['payment']['standard_workers_day_payment'] }}</div>
                                <div>{{ $list['payment']['standard_public_day_payment'] }}</div>
                            </td>
                            <td>
                                {{ $list['payment']['payment_total'] }}
                            </td>
                            <td>
                                {{ $list['payment']['voucher_sub_standard_payment'] }}
                            </td>
                            <td>
                                {{ $list['payment']['voucher_payment'] }}
                            </td>
                            <td>{{ $list['tax']->tax_nation_pension }}</td>
                            <td>
                                {{ $list['tax']->tax_health }}
                            </td>
                            <td>
                                {{ $list['tax']->tax_employ }}
                            </td>
                            <td>
                                {{ $list['tax']->tax_gabgeunse }}
                            </td>
                            <td>
                                {{ $list['tax']->tax_joominse }}
                            </td>
                            <td>{{ $list['tax']->tax_total }}</td>
                            <td>
                                {{ $list['tax']->tax_sub_payment }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        {{--<tfoot>--}}

                        {{--</tfoot>--}}
                    </table>
                </div>
                {{--@endif--}}
            </div>

        </article>
    </section>

    <script>

        $(document).ready(function() {
            $('#list_table').DataTable({
                searching: false,
                lengthChange: false,
                @if ($lists)
                scrollX:        "2400px",
                scrollY:        "400px",
                fixedColumns:   {
                    leftColumns: 3,
                },
                @else
                    scrollX:        "1630px",
                @endif
                scrollCollapse: false,
                paging:         false,

                language: {
                    emptyTable: "데이터가 없습니다"
                }
            });
        } );

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $("#from_date").datepicker({

            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });

    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">

@endsection
