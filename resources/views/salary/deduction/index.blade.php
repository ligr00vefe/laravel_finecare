@extends("layouts/layout")

@section("title")
    급여관리 - 급여대장
@endsection


@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">
        <article id="list_head">

            <div class="head-info">
                <h1>공제내역</h1>

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

                <div class="" >
                    <table id="list_table" class="table-1 ">
                        <thead>
                        <tr>
                            <th colspan="2" style="border-right: 1px solid #b7b7b7"></th>
                            <th colspan="6">제공자 급여공제내역</th>
                            <th style="border-left: 1px solid #b7b7b7;" colspan="5">사업수입 및 사업주 부담내역</th>
                        </tr>
                        <tr>
                            <th>
                                No
                            </th>
                            <th style="border-right: 1px solid #b7b7b7">대상자명</th>
                            <th>
                                국민연금
                            </th>
                            <th>
                                건강보험
                            </th>
                            <th>
                                고용보험
                            </th>
                            <th>
                                갑근세
                            </th>
                            <th>
                                주민세
                            </th>
                            <th>
                                공제합계
                            </th>
                            <th style="border-left: 1px solid #b7b7b7;">
                                국민연금
                            </th>
                            <th>
                                건강보험
                            </th>
                            <th>
                                고용보험
                            </th>
                            <th>
                                산재보험
                            </th>
                            <th>
                                퇴직연금
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse ($lists as $list)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td style="border-right: 1px solid #b7b7b7">
                                    {{ $list->provider_key }}
                                </td>
                                <td>
                                    {{ $list->tax_nation_pension ?? "" }}
                                </td>
                                <td>
                                    {{ $list->tax_health ?? "" }}
                                </td>
                                <td>
                                    {{ $list->tax_employ ?? "" }}
                                </td>
                                <td>
                                    {{ $list->tax_gabgeunse ?? "" }}
                                </td>
                                <td>
                                    {{ $list->tax_joominse ?? "" }}
                                </td>
                                <td>
                                    {{
                                    \App\Classes\Custom::removeCommaWithInteger([ $list->tax_nation_pension ?? 0,
                                    $list->tax_health ?? 0, $list->tax_employ ?? 0, $list->tax_gabgeunse ?? 0, $list->tax_joominse ?? 0 ])
                                    }}
                                </td>
                                <td style="border-left: 1px solid #b7b7b7;">
                                    {{ $list->tax_company_nation ?? 0 }}
                                </td>
                                <td>
                                    {{ $list->tax_company_health ?? 0 }}
                                </td>
                                <td>
                                    {{ $list->tax_company_employ ?? 0 }}
                                </td>
                                <td>
                                    {{ $list->tax_company_industry ?? 0 }}
                                </td>
                                <td>
                                    {{ $list->tax_company_retirement ?? 0 }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13">
                                    데이터가 없습니다.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </article>
    </section>

    <script>
        $("#from_date").datepicker({

            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });
    </script>


@endsection
