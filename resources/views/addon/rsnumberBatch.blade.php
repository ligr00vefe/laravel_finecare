@extends("layouts/layout")

@section("title")
    부가기능 - 편리한 연말정산 자료
@endsection

<?php
$lists = [ [] ];
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")

<style>

    .addon-rsnum-batch .page-info {
        display: inline-block;
    }
    .addon-rsnum-batch .page-info p {
        color: #252525;
        font-size: 15px;
    }

    .addon-rsnum-batch table.member-list input[type=text] {
        width: 135px;
        background-color: #f3ece4;
    }
</style>


<section id="member_wrap" class="addon-rsnum-batch list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>편리한 연말정산 자료</h1>
            <div class="action-wrap">
                <ul>
                    <li>
                        <button>선택수정</button>
                    </li>
                    <li class="hide"></li>
                </ul>
            </div>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <div class="page-info">
                    <p>
                        ※ <b class="fc-red">주민등록번호 뒷자리만</b> 수정하신 후 체크박스로 수정한 항목을 선택하고 선택수정하기 버튼을 눌러주세요.
                    </p>
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
            </tr>
            </thead>
            <tbody>
            @forelse ($lists as $i => $list)
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
                        <span>611111-</span>
                        <input type="text" name="rsnum_{{$i}}" class="sand">
                        <label for="rsnum_{{$i}}"></label>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"></td>
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
