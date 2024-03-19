@extends("layouts/layout")

@section("title")
    기타수당 - 중증가산수당 조회
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")

    <section id="member_wrap" class="etc-transportation-view list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>중증가산수당 조회</h1>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    <div class="limit-wrap">
                        <div class="limit-wrap">
                            <span>지급일자</span>
                            <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                            <label for="from_date">
                                <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                            </label>
                            <button class="btn-black-small">조회</button>
                        </div>
                    </div>
                    <div class="search-input">
                        <input type="text" name="term" placeholder="검색">
                        <button type="submit">
                            <img src="/storage/img/search_icon.png" alt="검색하기">
                        </button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents" class="over-x-auto">

            <table class="member-list b-last-bottom table-2x-large">
                <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        반납여부
                    </th>
                    <th>
                        대상자명
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        제공인력명
                    </th>
                    <th>
                        제공인력<br>
                        생년월일
                    </th>
                    <th>
                        제공기관명
                    </th>
                    <th>
                        사업자번호
                    </th>
                    <th>
                        시도
                    </th>
                    <th>
                        시군구
                    </th>
                    <th>
                        사업구분
                    </th>
                    <th>
                        사업유형
                    </th>
                    <th>
                        승인번호
                    </th>
                    <th>
                        승인일시
                    </th>
                    <th>
                        서비스시작시간
                    </th>
                    <th>
                        서비스종료시간
                    </th>
                    <th>
                        일반결제시간
                    </th>
                    <th>
                        추가결제시간
                    </th>
                    <th>
                        지급액
                    </th>
                    <th>
                        지급일자
                    </th>
                    <th>
                        반납일자
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $i => $list)
                    <tr>
                        <td>
                            {{ ($i+1) + (($page-1) * 15) }}
                        </td>
                        <td>
                            {{ $list->return_check }}
                        </td>
                        <td>
                            {{ $list->target_name }}
                        </td>
                        <td>
                            {{ convert_birth($list->target_rsNo) }}
                        </td>
                        <td>
                            {{ $list->provider_name }}
                        </td>
                        <td>
                            {{ convert_birth($list->provider_rsNo) }}
                        </td>
                        <td>
                            {{ $list->provider_agency }}
                        </td>
                        <td>
                            {{ print_business_license($list->business_license) }}
                        </td>
                        <td>
                            {{ $list->sido }}
                        </td>
                        <td>
                            {{ $list->sigungu }}
                        </td>
                        <td>
                            {{ $list->business_division }}
                        </td>
                        <td>
                            {{ $list->business_class }}
                        </td>
                        <td>
                            {{ $list->confirm_number }}
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($list->confirm_date)) }}
                        </td>
                        <td>
                            {{ date("Y-m-d H:i:s", strtotime($list->service_start_date_time)) }}
                        </td>
                        <td>
                            {{ date("Y-m-d H:i:s", strtotime($list->service_end_date_time)) }}
                        </td>
                        <td>
                            {{ $list->general_payment_time }}
                        </td>
                        <td>
                            {{ $list->add_payment_time }}
                        </td>
                        <td class="t-right">
                            {{ number_format($list->payment_amount) }}
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($list->amount_date)) }}
                        </td>
                        <td>
                            {{ $list->return_date != "1970-01-01" ? $list->return_date : "" }}
                        </td>
                    </tr>
                @endforeach
                @if ($lists->isEmpty())
                    <tr>
                        <td colspan="21" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination(15, ceil($paging/15)) !!}
        </article> <!-- article list_bottom end -->

    </section>

    <script>

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm",
                view: 'months',
                minView: 'months',
                clearButton: false,
                autoClose: true,
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소날짜 설정해주기
                    if (idx%2 == 0) {
                        $(datepicker_selector[idx+1]).datepicker({
                            minDate: new Date(dateText),
                            dateFormat:"yyyy-mm",
                            clearButton: false,
                            autoClose: true,
                        })
                    }

                }
            });

        });


    </script>

@endsection
