@extends("layouts/layout")

@section("title")
    활동지원사
@endsection

@php
$limit = $_GET['limit'] ?? 15;
@endphp

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("worker.side")


<section id="member_wrap" class="">

    <article id="list_head">

        <div class="head-info">
            <h1>활동지원사</h1>
        </div>

        <div class="sort-tab">
            <ul>
                <li class="{{$type=="all" ? "on" : ""}}">
                    <a href="/worker/main/all/1">전체</a>
                </li>
                <li class="{{$type=="reg" ? "on" : ""}}">
                    <a href="/worker/main/reg/1">접수</a>
                </li>
                <li class="{{$type=="use" ? "on" : ""}}">
                    <a href="/worker/main/use/1">이용중</a>
                </li>
                <li class="{{$type=="termi" ? "on" : ""}}">
                    <a href="/worker/main/termi/1">해지</a>
                </li>
                <li class="{{$type=="cancel" ? "on" : ""}}">
                    <a href="/worker/main/cancel/1">접수취소</a>
                </li>
            </ul>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_search">
                <div class="limit-wrap">
                    <select name="limit" id="limit">
                        <option value="15">15명씩 보기</option>
                        <option value="20">20명씩 보기</option>
                        <option value="25">25명씩 보기</option>
                    </select>
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
                    입사일
                </th>
                <th>
                    퇴사일
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lists as $i => $list)
            <tr>
                <td>{{ (15*($page-1))+$i+1 }}</td>
                <td>{{ $list->name }}</td>
                <td>{{ convert_birth($list->rsNo) }}</td>
                <td>{{ gender_kor($list->gender) }}</td>
                <td>{{ $list->tel }}</td>
                <td class="t-left">{{ $list->address }}</td>
                <td>{{ "이용중" }}</td>
                <td>{{ $list->regdate }}</td>
                <td>{{ $list->join_date }}</td>
                <td>{{ $list->resign_date }}</td>
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
        {!! pagination(10, ceil($paging/$limit)) !!}
    </article> <!-- article list_bottom end -->

</section>
@endsection