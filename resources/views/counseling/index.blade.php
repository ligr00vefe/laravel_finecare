@extends("layouts/layout")

@section("title")
    상담 - 상담일지
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")
<section id="member_wrap" class="counseling-users list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>상담일지</h1>
        </div>

        <div class="sort-tab">
            <ul>
                <li class="{{$type=="all" ? "on" : ""}}">
                    <a href="/counseling/users/all/1">전체</a>
                </li>
                <li class="{{$type=="worker" ? "on" : ""}}">
                    <a href="/counseling/users/worker/1">활동지원사</a>
                </li>
                <li class="{{$type=="member" ? "on" : ""}}">
                    <a href="/counseling/users/member/1">이용자</a>
                </li>
            </ul>
        </div>

        <div class="search-wrap">
            <form action="/counseling/users/{{$type}}/1" method="get" name="member_search">
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
            <colgroup>
                <col width="3%;">
                <col width="5%;">
                <col width="5%;">
                <col width="5%;">
                <col width="5%;">
                <col width="10%">
                <col width="5%;">
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
                    비고
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($lists as $key => $list)
            <tr>
                <td>{{ ($key+1)+(15*($page-1)) }}</td>
                <td>{{ $list->table }}</td>
                <td>{{ $list->name }}</td>
                <td>{{ convert_birth($list->rsNo) }}</td>
                <td>
                    <a href="{{ $list->table == "이용자"
                    ? route("counseling.create", [ "type"=>"member", "id"=>$list->id ])
                    : route("counseling.create", [ "type"=>"worker", "id"=>$list->id ]) }}"
                       class="btn-write-counseling">
                        상담일지작성
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
        {!! pagination(10, ceil($paging/15)) !!}
    </article> <!-- article list_bottom end -->


    <script>
    </script>

</section>
@endsection
