@extends("layouts/layout")

@section("title")
    온라인문의 - FAQ
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("support.side_nav")
<style>
    .faq-Q {
        font-size: 14px !important;
        color: #EB8626 !important;
    }

    .accordion_contents td {
        padding: 0 40px !important;
        text-align: left !important;
        line-height: 22px !important;
        height: 0 !important;
    }

    .accordion_contents td div {
        overflow:hidden;
        display: none;
        border: none !important;
    }

    .support-faq-list table tr th,
    .support-faq-list table tr td {
        text-align: left !important;
    }

    .support-faq-list table tr td {
        border-bottom: none !important;
        border-top: none !important;
    }

    .support-faq-list table tr.accordion_open td {
        border-top: 1px solid #b7b7b7 !important;
    }

    .support-faq-list table {
        border-bottom: 1px solid #b7b7b7 !important;
    }

    .support-faq-list .btn-wrap .left {
        float: left;
    }

    .support-faq-list .btn-wrap .right {
        float: right;
    }


</style>

<section id="member_wrap" class="support-faq-list list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>FAQ <span>아래 목록을 확인하세요. 원하는 질문과 답변이 없으면 온라인문의를 이용해주시기 바랍니다.</span></h1>
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
            <colgroup>
                <col width="1%">
                <col width="auto">
            </colgroup>
            <tbody>
            @for ($i=0; $i<1; $i++)
                <tr class="accordion_open" data-idx="{{$i}}">
                    <td class="faq-Q">
                        Q
                    </td>
                    <td>
                        활동지원사 인전사항 수정은 어떻게 하나요?
                    </td>
                </tr>
                <tr class="accordion_contents" data-open="{{$i}}">
                    <td colspan="3">
                        <div>
                            활동지원사 > 활동지원사 명단 조회 라는 메뉴에 들어가 원하는 활동지원사를 검색 한 후 수정하기 버튼을 눌러주시면 됩니다. <br>
                            수정 후 꼭 수정 완료 버튼을 눌러 새롭게 업데이트 된 내용을 반영시키셔야 합니다.
                        </div>
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>

        <div class="btn-wrap">
            {{--<div class="left">--}}
                {{--<button type="button" class="btn-delete">--}}
                    {{--선택삭제--}}
                {{--</button>--}}
            {{--</div>--}}
            {{--<div class="right">--}}
                {{--<button type="button" class="btn-write">--}}
                    {{--글쓰기--}}
                {{--</button>--}}
            {{--</div>--}}
        </div>

    </article> <!-- article list_contents end -->

    <script>

        $(".accordion_open").on("click", function() {


            var id = $(this).data("idx");
            var content = $(".accordion_contents[data-open='"+id+"'] td div");
            var faq_wrap = $(content).parents("td");
            var prt = $(faq_wrap).parents("tr");
            var allContent = $(".accordion_contents");

            console.log(content.css("display"));

            if (content.css("display") == "none") {
                $(".accordion_contents td div").slideUp();
                content.css("padding", "20px 20px 20px 20px");
                content.slideDown();
            } else {
                content.slideUp();
            }
        });

    </script>

</section>
@endsection
