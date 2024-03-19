@extends("layouts/layout")

@section("title")
    이용자
@endsection

@php
$postPerPage = $_GET['limit'] ?? 15;
$term = $_GET['term'] ?? "";
$limit = $_GET['limit'] ?? 15;
@endphp


@section("content")


    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side")

    <section id="member_wrap">

        <article id="list_head">
            <div class="head-info">
                <h1>이용자</h1>
            </div>

            <div class="sort-tab">
                <ul>
                    <li class="{{$type=="all" ? "on" : ""}}">
                        <a href="/member/main/all/1">전체</a>
                    </li>
                    <li class="{{$type=="reg" ? "on" : ""}}">
                        <a href="/member/main/reg/1">접수</a>
                    </li>
                    <li class="{{$type=="use" ? "on" : ""}}">
                        <a href="/member/main/use/1">이용중</a>
                    </li>
                    <li class="{{$type=="termi" ? "on" : ""}}">
                        <a href="/member/main/termi/1">해지</a>
                    </li>
                    <li class="{{$type=="cancel" ? "on" : ""}}">
                        <a href="/member/main/cancel/1">접수취소</a>
                    </li>
                </ul>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    <div class="limit-wrap">
                        <select name="limit" id="limit">
                            <option value="15" {{ $limit == 15 ? "selected" : "" }}>15명씩 보기</option>
                            <option value="20" {{ $limit == 20 ? "selected" : "" }}>20명씩 보기</option>
                            <option value="25" {{ $limit == 25 ? "selected" : "" }}>25명씩 보기</option>
                        </select>
                    </div>
                    <div class="search-input">
                        <input type="text" name="term" placeholder="검색" value="{{$term}}">
                        <button type="submit">
                            <img src="/storage/img/search_icon.png" alt="검색하기">
                        </button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents">
            <table class="member-list">
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
                        이름
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        성별
                    </th>
                    <th>
                        전화번호
                    </th>
                    <th>
                        주소
                    </th>
                    <th>
                        상태
                    </th>
                    <th>
                        접수일
                    </th>
                    <th>
                        계약시작일
                    </th>
                    <th>
                        계약종료일
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $i => $list)
                <tr>
                    <td>{{ (15*($page-1))+$i+1 }}</td>
                    <td>{{ $list->name ?? "" }}</td>
                    <td>{{ substr($list->rsNo, 0, 6)  }}</td>
                    <td>{{ substr($list->rsNo, 7, 1) == 1 ? "남성" : "여성"  }}</td>
                    <td>{{ $list->tel }}</td>
                    <td class="t-left">{{ $list->address }}</td>
                    <td>이용중</td>
                    <td>{{ $list->regdate ?? "" }}</td>
                    <td>{{ $list->contract_start_date ?? "" }}</td>
                    <td>{{ $list->contract_end_date ?? "" }}</td>
                </tr>
                @endforeach
                @if ($lists->isEmpty())
                    <tr>
                        <td colspan="10" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination(10, ceil($paging/$postPerPage)) !!}
        </article> <!-- article list_bottom end -->

    </section>
@endsection
