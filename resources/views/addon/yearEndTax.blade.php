@extends("layouts/layout")

@section("title")
    부가기능 - 편리한 연말정산 자료
@endsection

<?php
$lists = [];
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")
<section id="member_wrap" class="addon-year-end-tax list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>편리한 연말정산 자료</h1>
            <div class="action-wrap">
                <ul>
                    <li>
                        <button>엑셀내려받기</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <span>회계연도</span>
                        <input type="text" name="from_date" autocomplete="off" readonly="" id="from_date">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <input type="text" name="to_date" autocomplete="off" readonly="" id="to_date">
                        <label for="to_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
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

        <div class="filter-sort m-top-10">
            <select name="sort" id="sort">
                <option value="">퇴사자 포함</option>
            </select>
            <a href="{{route("addon.rsnumber.batch")}}">주민등록번호 일괄변경</a>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents">
        <table class="member-list in-input b-last-bottom">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" name="check_all" id="check_all">
                    <label for="check_all"></label>
                </th>
                <th>
                    No
                </th>
                <th>
                    활동지원사명
                </th>
                <th>
                    주민등록번호
                </th>
                <th>
                    근무시작일자
                </th>
                <th>
                    근무종료일자
                </th>
                <th>
                    총급여
                </th>
                <th>
                    소득세
                </th>
                <th>
                    지방소득세
                </th>
                <th>
                    국민연금보험료
                </th>
                <th>
                    건강보험료<br>
                    (노인장기요양보헐료 포함)
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse($lists as $list)
                <tr>
                    <td>
                        <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}">
                        <label for="check_{{$i}}"></label>
                    </td>
                    <td>
                        {{$i+1}}
                    </td>
                    <td>
                        홍길동
                    </td>
                    <td>
                        611111-1111111
                    </td>
                    <td>
                        2020-10-10
                    </td>
                    <td>
                        13,422,224
                    </td>
                    <td>
                        885,590
                    </td>
                    <td>
                        0
                    </td>
                    <td>
                        885,590
                    </td>
                    <td>
                        885,590
                    </td>
                    <td>
                        885,590
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">
                        데이터가 없습니다.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
<!--        --><?//=pagination(10, $page, 30 )?>
    </article> <!-- article list_bottom end -->

</section>


<script>
    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {
            $("input[name='to_date']").datepicker({
                minDate: new Date(dateText),
                dateFormat:"yyyy-mm",
                clearButton: false,
                autoClose: true,
            })
        }
    });


    $("input[name='to_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

        }
    })
</script>
@endsection
