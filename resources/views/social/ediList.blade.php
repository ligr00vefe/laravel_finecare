@extends("layouts/layout")

@section("title")
    사회보험 - EDI 조회
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")


    <section id="member_wrap" class="collect-list-wrap list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>EDI 조회</h1>
            </div>

            <div class="tab-wrap">
                <ul>
                    <li class="{{$title == "국민연금" ? "on" : ""}}">
                        <a href="/social/EDI/list/np">국민연금</a>
                    </li>
                    <li class="{{$title == "건강보험" ? "on" : ""}}">
                        <a href="/social/EDI/list/health">건강보험</a>
                    </li>
                    <li class="{{$title == "산재보험" ? "on" : ""}}">
                        <a href="/social/EDI/list/ind">산재보험</a>
                    </li>
                    <li class="{{$title == "고용보험" ? "on" : ""}}">
                        <a href="/social/EDI/list/emp">고용보험</a>
                    </li>
                </ul>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    <div class="search-input">
                        <input type="text" name="term" placeholder="검색">
                        <button type="submit">
                            <img src="/storage/img/search_icon.png" alt="검색하기">
                        </button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents" style="overflow-x: auto">

            @if ($title == "국민연금")
                @include("social.edi_national_pension_table")
            @elseif ($title == "건강보험")
                @include("social.edi_health_insurance_table")
            @elseif ($title == "고용보험")
                @include("social.edi_employment_insurance")
            @elseif ($title == "산재보험")
                @include("social.edi_industrial_insurance_table")
            @endif

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
