<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>파인케어 | @yield("title", "main") </title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/admin.css">
    <script src="/storage/libs/jquery-3.5.1.min.js"></script>
    <script src="/storage/libs/datepicker/datepicker.js"></script>
    <script src="/storage/libs/datepicker/datepicker.ko.js"></script>
    <script src="/storage/libs/moment-with-locales.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/admin.js"></script>
    <link rel="stylesheet" href="/storage/libs/datepicker/datepicker.css">
</head>
<body>

@if (session()->get("error"))
    <script>
        alert("{{session()->get("error")}}");
    </script>
@endif

@if (session()->get("msg"))
    <script>
        alert("{{session()->get("msg")}}");
    </script>
@endif

@if (session()->get("success"))
    <script>
        alert("{{session()->get("success")}}");
    </script>
@endif

<div id="admin_wrapper">

    <div id="admin_head">
        <div class="head-content">
            <div>
                <p>ADMINISTRATOR</p>
            </div>
            <div>
                <a href="/member/main/all/1">
                    <img src="/storage/img/icon_house.png" alt="홈으로">
                </a>
                <button type="button" onclick="route('{{ route("logout") }}')">
                    로그아웃
                </button>
            </div>
        </div>
    </div>

    <div class="section-wrap">
        <aside id="admin_nav">
            <ul class="ul_1depth">
                <li>
                    <a href="/admin">
                        <img src="/storage/img/icon_menu_house_on.png" alt="대시보드">
                        대시보드
                    </a>
                </li>
                <li>
                    <a href="/admin/user" class="more">
                        <img src="/storage/img/icon_menu_user.png" alt="">
                        회원관리
                    </a>
                    <ul class="ul_2depth">
                        <li>
                            <a href="/admin/user" class="on">- 회원관리</a>
                        </li>
                        <li>
                            <a href="/admin/user/register">- 회원등록</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="more">
                        <img src="/storage/img/icon_menu_board.png" alt="">
                        게시판 관리
                    </a>
                    <ul class="ul_2depth">
                        <li>
                            <a href="/admin/board" class="on">- 게시판 관리</a>
                        </li>
                        <li>
                            <a href="/admin/board/inquiry">- 온라인문의 내역</a>
                        </li>
                        <li>
                            <a href="/admin/board/faq">- FAQ 관리</a>
                        </li>
                        <li>
                            <a href="/admin/board/archives">- 자료실 관리</a>
                        </li>
                        <li>
                            <a href="/admin/board/video">- 동영상 관리</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="more">
                        <img src="/storage/img/icon_menu_payment.png" alt="">
                        결제관리
                    </a>
                    <ul class="ul_2depth">
                        <li>
                            <a href="/admin/payment" class="on">- 결제관리</a>
                        </li>
                        <li>
                            <a href="/admin/product">- 결제상품관리</a>
                        </li>
                    </ul>

                </li>
                <li>
                    <a href="#" class="more">
                        <img src="/storage/img/icon_menu_setting.png" alt="">
                        기타관리
                    </a>
                </li>
            </ul>
        </aside>

        <main id="main">
            @yield("content")
        </main>
    </div>

    <footer>
        <div class="footer-in">
            <p>COPYRIGHT 2020 FINECARE ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

</div>

<script>
    $(document).ready(function() {

        $("#admin_nav p").hide();

        $("#admin_nav ul.ul_1depth > li > a").click(function(){

            if ($(this).parents(".ul_1depth > li").index() == 0) {
                location.href = "/admin/";
            }

            $(this).nextAll().slideToggle(300);
            $("#admin_nav ul li a").not(this).nextAll().slideUp(300);
            return false;
        });

        // $("#admin_nav ul li a").eq(0).trigger("click");
    });
</script>

<!--    <script src="//cdn.ckeditor.com/ckeditor5/22.0.0/balloon-block/ckeditor.js"></script>-->

</body>
</html>
