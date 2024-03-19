@extends("layouts/layout")

@section("title")
    파인케어 | 추가 서비스내역
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("service.side_nav")

    <section id="member_wrap" class="service-manage-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>추가 서비스내역</h1>
                <div class="right-buttons">
                    <button onclick="location.href='/service/extra/create'" type="button">추가등록</button>
                    <form action="/service/add/upload" method="post" name="uploadform" enctype="multipart/form-data" style="display: inline-block; vertical-align:bottom;">
                        @csrf
                        <input type="file" name="excel" id="excelupload" class="excelupload" style="display: none;">
                        <label for="excelupload" style="height: 32px; border: 1px solid #b7b7b7; padding: 0 15px; background-color:#efefef; font-size: 13px; line-height:32px; display: inline-block">엑셀 올리기</label>
                    </form>
                    <form action="/export/excel/service/extra" method="post" style="display: inline-block">
                        @csrf
                        <button class="orange">명단내려받기</button>
                    </form>
                </div>
            </div>

            <script>
                $(".excelupload").on("change", function () {
                    if (!confirm("업로드하시겠습니까?")) {
                        return false;
                    }

                    document.uploadform.submit();
                })
            </script>

            <div class="search-wrap">
                <form action="/service/extra/list" method="get" name="member_search">
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
                            {{ (($page-1) * 15) + $loop->iteration }}
                        </td>
                        <td>
                            {{ $list->target_name }}
                        </td>
                        <td>
                            {{ $list->target_key }}
                        </td>
                        <td>
                            {{ $list->provider_name }}
                        </td>
                        <td>
                            {{ convert_birth($list->provider_birth) }}
                        </td>
                        <td>
                            {{ $list->service_start_date_time ?? "" }}
                        </td>
                        <td>
                            {{ $list->service_end_date_time ?? "" }}
                        </td>
                        <td>
                            {{ $list->payment_time }}
                        </td>
                        <td>
                            {{ number_format($list->confirm_pay) }}
                        </td>
                        <td>
                            {{ number_format($list->add_price) }}
                        </td>
                        <td>
                            {{ $list->local_government_name }}
                        </td>
                        <td>
                            {{ $list->organization }}
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


        $("a[href='/service/extra/list']").parent("li").addClass("on");

    </script>

@endsection
