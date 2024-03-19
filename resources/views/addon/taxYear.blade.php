@extends("layouts/layout")

@section("title")
    부가기능 - 연말정산안내
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")
<section id="member_wrap" class="addon-tax-year list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>연말정산안내</h1>
        </div>

    </article> <!-- article list_head end -->

    <article id="info">

        <div class="info-in">
            <div class="m-bottom-50">
                <h3><span class="dotted-yellow">▪</span>2020년 연말정산 기간</h3>
                <ul class="before-star m-top-10">
                    <li>2020년 연말정산 간소화 서비스 기간 : 2020년 1월 15일 ~ 2020년 2월 28일까지</li>
                    <li>2020년 편리한 연말정산 근로자 기초자료 등록 기간 ; 2020년 1월 20일 ~ 2020년 3월 10일까지</li>
                    <li>편리한 연말정산 이용방법 : <b class="fc-sky">국세청 홈페이지 안내</b></li>
                </ul>
            </div>

            <div class="">
                <h3><span class="dotted-yellow">▪</span>편리한 연말정산 방법</h3>
                <h4 class="m-top-10">※ 간편제풀(연말정산) 이란?</h4>
                <p class="m-top-10 m-bottom-20">
                    간편제출이란 회사가 근로자의 기초자료를 등록하면 근로자가 공제신고서를 간편제출할 수 있고 회사는 공제신고서를 종이 제출없이 수집할 수 있어 근로자와 회사 모두 절차가 간소화됩니다.
                </p>
                <h4 class="m-top-10">※ 연말정산 간편제출 이용절차</h4>
                <ul class="m-top-10 dot-black">
                    <li>회사는 근로자 정보등록 또는 지급명세서 작성 이송관리 화면에서 근로자 기초자료를 등록</li>
                    <li>근로자는 공제신고서를 작성하여 간편 제출</li>
                    <li>회사는 공제신고서 관리화면에서 근로자가 제출한 공제신고서 검토 (수정이 필요하다면 근로자에게 반송처리)</li>
                    <li>모든 공제신고서가 이상이 없다면 지급명세서 작성, 이송관리 화면에서 지급명세서 보내기를 화면 끝! </li>
                </ul>
            </div>
        </div>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
<!--        --><?//=pagination(10, $page, 30 )?>
    </article> <!-- article list_bottom end -->

</section>

@endsection
