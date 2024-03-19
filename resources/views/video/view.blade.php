@extends("layouts/layout")

@section("title")
    상담 - 동영상 게시판
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("support.side_nav")

    <section id="member_wrap" class="support-qna-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>상담일지</h1>
            </div>

            <div class="sort-tab">
                <ul>
                    <li class="on">
                        <a href="">전체</a>
                    </li>
                </ul>
            </div>

            <div class="search-wrap">
                <form action="" method="post" name="member_search">
                    <div class="search-input">
                        <select name="search-filter" id="search-filter">
                            <option value="">제목 및 내용</option>
                        </select>
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
                        구분
                    </th>
                    <th>
                        제목
                    </th>
                    <th>
                        일시
                    </th>
                </tr>
                </thead>
                <tbody>
                @for ($i=0; $i<15; $i++)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>
                            {{$i+1}}
                        </td>
                        <td>
                            <b>서비스문의</b>
                        </td>
                        <td class="t-left fc-gray-63">
                            회계관리 메뉴는 어디 있나요?
                        </td>
                        <td>
                            2020-10-10
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>

            <div class="btn-wrap">
                <div class="left">
                    <button type="button" class="btn-delete">
                        선택삭제
                    </button>
                </div>
                <div class="right">
                    <button type="button" class="btn-write" onclick="location.href='{{route("support.qna.create")}}'">
                        글쓰기
                    </button>
                </div>
            </div>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">

        </article> <!-- article list_bottom end -->

    </section>
@endsection
