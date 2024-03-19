@extends("layouts/layout")

@section("title")
    상담일지 상세보기
@endsection

<?php
use App\Classes\Custom;
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")
<section id="container" class="counseling-write">

    <article id="contents">
        <div class="contents-top">
            <h3>상담일지</h3>

            <div class="top-user-info">
                <span>
                    <img src="{{__IMG__}}/user_icon_small.png" alt="회원 정보">
                    {{ $get->type == "worker" ? "활동지원사" : "이용자" }}:<b>{{ $get->target_info->name ?? "" }} ({{ Custom::rsno_to_gender($get->target_info->rsNo ?? "") }},{{ Custom::rsno_to_birth($get->target_info->rsNo ?? "") }})</b>
                </span>
                <span>
                    <img src="{{__IMG__}}/icon_tel_small.png" alt="연락처">
                    {{ $get->target_info->phone ?? "" }}
                </span>

                {{--<button class="btn-connect-user">--}}
                    {{--<img src="{{__IMG__}}/icon_6point.png" alt="연결된 이용자 보기">--}}
                    {{--연결된 이용자 보기--}}
                {{--</button>--}}
            </div>

        </div>

        <div class="contents-body">

            <table class="view-type">
                <tbody>
                <tr>
                    <th>
                        분류
                    </th>
                    <td>
                        {{ $get->category == "etc" ? "특이사항" : "" }}
                    </td>
                    <th>
                        상담방법
                    </th>
                    <td>
                        {{ $get->way == "tel" ? "유선" : "" }}
                    </td>
                </tr>
                <tr>
                    <th>
                        시작일시
                    </th>
                    <td>
                        {{ $get->from_date }} {{ $get->from_date_time }}
                    </td>
                    <th>
                        종료일시
                    </th>
                    <td>
                        {{ $get->to_date }} {{ $get->to_date_time }}
                    </td>
                </tr>
                <tr>
                    <th>
                        제목
                    </th>
                    <td colspan="3">
                        {{ $get->title }}
                    </td>
                </tr>

                <tr>
                    <th>
                        내용
                    </th>
                    <td colspan="3" class="">
                        <div class="min-height-t-area">
                            {{ $get->content }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="result">상담결과</label>
                    </th>
                    <td colspan="3" class="">
                        <div class="min-height-t-area">
                            {{ $get->result }}
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="btn-wrap right m-top-20">
                <form action="{{ route("counseling.delete", [ "id" => $id ]) }}" method="post" style="display: inline-block"
                    onsubmit="if (!confirm('삭제하면 복구할 수 없습니다. 삭제하시겠습니까?')) return false;"
                >
                    @csrf
                    @method("delete")
                    <input type="hidden" name="id" value="{{ $id }}">
                    <button class="btn-cancel">삭제</button>
                </form>
                <form action="{{ route("counseling.edit", [ "id" => $id ]) }}" method="get" style="display: inline-block;">
                    @csrf
                <button class="btn-cancel">수정</button>
                </form>
                <button type="button" class="btn-submit" onclick="location.href='/counseling/log/list' ">목록</button>
            </div>

        </div>

    </article>

</section>

<script>
    $("ul.ul_2depth a[href='/counseling/log/list']").parent("li").addClass("on");

</script>

@endsection
