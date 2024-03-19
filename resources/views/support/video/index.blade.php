@extends("layouts/layout")

@section("title")
    고객지원 - 동영상
@endsection

<?php
use App\Classes\Input;
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("support.side_nav")


<section id="member_wrap" class="support-qna-list list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>동영상</h1>
        </div>



        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <div class="search-input">
                    <select name="search-filter" id="search-filter">
                        <option value="">제목 및 내용</option>
                    </select>
                    <input type="text" name="term" placeholder="검색">
                    <input type="hidden" name="category" value="{{ $_GET['category'] ?? "" }}">
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
                    구분
                </th>
                <th>
                    제목
                </th>
                <th>
                    일시
                </th>
                <th>
                    답변
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($lists as $i => $list)
                <tr class="goShow" data-id="{{ $list->id }}">
                    <td>
                        {{ (($page - 1) * 15) + $loop->iteration }}
                    </td>
                    <td>
                        <b>{{ $list->category }}</b>
                    </td>
                    <td class="t-left fc-gray-63">
                        {{ $list->subject }}
                    </td>
                    <td>
                        {{ $list->created_at }}
                    </td>
                    <td>
                        {{ $list->deleteCheck == 0 ? "답변전" : "답변완료" }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        자료가 없습니다.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="btn-wrap">
            <div class="left">
            </div>
            {{--<div class="right">--}}
                {{--<button type="button" class="btn-write" onclick="location.href='{{route("support.lib.create")}}'">--}}
                    {{--글쓰기--}}
                {{--</button>--}}
            {{--</div>--}}
        </div>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
        {!! pagination2(10, ceil($paging/15)) !!}
    </article> <!-- article list_bottom end -->

</section>

<style>
    .goShow {
        cursor: pointer;
    }
</style>

<script>
    $(".goShow").on("click", function () {
        var id = $(this).data("id");
        location.href = '/support/qna/view/'+ id;
    });
</script>

@endsection
