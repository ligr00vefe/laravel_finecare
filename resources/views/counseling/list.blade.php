@extends("layouts/layout")

@section("title")
    상담 - 상담일지 조회
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")

<section id="member_wrap" class="counseling-users list_wrapper">

    <style>
        .go-show {
            cursor: pointer;
        }
    </style>

    <article id="list_head">

        <div class="head-info">
            <h1>상담일지 조회</h1>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date" value="{{ $from_date ?? "" }}">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <span>~</span>
                        <input type="text" name="to_date" autocomplete="off" readonly id="to_date" value="{{ $to_date ?? "" }}">
                        <label for="to_date">
                            <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                        </label>
                        <button type="submit">조회</button>
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

        <div class="sort-tab m-top-20">
            <ul>
                <li class="{{$type=="all" ? "on" : ""}}">
                    <a href="/counseling/log/list/?type=all">전체</a>
                </li>
                <li class="{{$type=="worker" ? "on" : ""}}">
                    <a href="/counseling/log/list/?type=worker">활동지원사</a>
                </li>
                <li class="{{$type=="member" ? "on" : ""}}">
                    <a href="/counseling/log/list/?type=member">이용자</a>
                </li>
            </ul>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents">
        <table class="member-list b-last-bottom">
            <colgroup>
                <col width="3%;">
                <col width="2%;">
                <col width="5%;">
                <col width="5%;">
                <col width="5%;">
                <col width="5%">
                <col width="15%">
                <col width="5%;">
                <col width="5%;">
                <col width="5%;">
            </colgroup>
            <thead>
            <tr>

                <th>
                    No
                </th>
                <th>
                    구분
                </th>
                <th>
                    이름
                </th>
                <th>
                    생년월일
                </th>
                <th>
                    분류
                </th>
                <th>
                    제목
                </th>
                <th>
                    상담방법
                </th>
                <th>
                    시작일지
                </th>
                <th>
                    종료일시
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lists as $i => $list)
            <tr class="go-show" data-id="{{ $list->id }}">

                <td>{{($i+1)+(15*($page-1))}}</td>
                <td>{{ $list->type == "member" ? "이용자" : "활동지원사" }}</td>
                <td>{{ $list->target_info->name ?? "" }}</td>
                <td>{{ convert_birth($list->target_info->rsNo ?? "") }}</td>
                <td>
                    {{ convert_log_category($list->category ?? "") }}
                </td>
                <td class="t-left">
                    {{ $list->title ?? "" }}
                </td>
                <td>
                    {{ convert_log_way($list->way ?? "") }}
                </td>
                <td>
                    {{ date("Y-m-d H:i", strtotime($list->from_date.$list->from_date_time ?? "")) }}
                </td>
                <td>
                    {{ date("Y-m-d H:i", strtotime($list->to_date.$list->to_date_time)) }}
                </td>
            </tr>
            @endforeach
            @if ($lists->isEmpty())
                <tr>
                    <td colspan="9" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
                </tr>
            @endif
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
        {!! pagination(10, ceil($paging/15)) !!}
    </article> <!-- article list_bottom end -->

</section>

<script>

    $(".go-show").on("click", function () {
        var id = $(this).data("id");
        location.href = '{{ route("counseling.show") }}/' + id;
    });

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
