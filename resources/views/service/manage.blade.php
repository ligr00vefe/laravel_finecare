@extends("layouts/layout")

@section("title")
    추가 서비스내역 관리
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("service.side_nav")

    <section id="member_wrap" class="service-manage-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>추가 서비스내역 관리</h1>
                <div class="right-buttons">
                    <button type="button">추가등록</button>
                    <button type="button">엑셀 올리기</button>
                    <button type="button" class="orange">엑셀 내려받기</button>
                </div>
            </div>

            <div class="search-wrap">
                <form action="/service/manage/list/1" method="get" name="member_search">
                    <div class="limit-wrap">
                        <div class="limit-wrap">
                            <span>서비스연월</span>
                            <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                            <label for="from_date">
                                <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                            </label>
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

        <article id="list_contents">

            <table class="member-list b-last-bottom">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" value="1" id="check_all">
                        <label for="check_all"></label>
                    </th>
                    <th>
                        No
                    </th>
                    <th>
                        대상자
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        제공인력
                    </th>
                    <th>
                        제공인력<br>
                        생년월일
                    </th>
                    <th>
                        서비스<br>
                        시작시간
                    </th>
                    <th>
                        서비스<br>
                        종료시간
                    </th>
                    <th>
                        결제시간
                    </th>
                    <th>
                        총결제금액
                    </th>
                    <th>
                        가산금액
                    </th>
                    <th>
                        지원지자체 구분
                    </th>
                    <th>
                        지원기관
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $i => $list)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}" value="{{ $list->id }}">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>
                            {{ ($i+1) + (15 * ($page - 1)) }}
                        </td>
                        <td>
                            {{ $list->target_name }}
                        </td>
                        <td>
                            {{ convert_birth($list->target_birth) }}
                        </td>
                        <td>
                            {{ $list->provider_name }}
                        </td>
                        <td>
                            {{ convert_birth($list->provider_birth) }}
                        </td>
                        <td>
                            {{ $list->event_start_date_time ?? "" }}
                        </td>
                        <td>
                            {{ $list->event_end_date_time ?? "" }}
                        </td>
                        <td>
                            {{ $list->payment_time }}
                        </td>
                        <td>
                            {{ "물어보고" }}
                        </td>
                        <td>
                            {{ number_format($list->add_price) }}
                        </td>
                        <td>
                            {{ $list->business_type }}
                        </td>
                        <td>
                            강원도 (물어보기)
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination(10, ceil($paging/15)) !!}
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
