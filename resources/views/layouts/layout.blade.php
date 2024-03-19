<?php
$access_menu = explode("/", $_SERVER['REQUEST_URI'])[1];
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>파인케어 | @yield("title", "main") </title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/storage/libs/jquery-3.5.1.min.js"></script>
    <script src="/storage/libs/datepicker/datepicker.js"></script>
    <script src="/storage/libs/datepicker/datepicker.ko.js"></script>
    <script src="/storage/libs/moment-with-locales.js"></script>
    <script src="/storage/libs/datatables/datatables.min.js"></script>
    <script src="/storage/libs/datatables/dataTables.fixedColumns.min.js"></script>
    <script src="/storage/libs/html2canvas.js"></script>
    <script src="/storage/libs/jspdf.min.js"></script>
    <script src="/js/common.js"></script>
    <link rel="stylesheet" href="/storage/libs/datepicker/datepicker.css">
    <link rel="stylesheet" href="/storage/libs/datatables/datatables.min.css">
</head>
<body>

@if (session()->get("attack_detected"))
    <script>
        alert("{{session()->get("attack_detected")}}");
    </script>
@endif

@if (session()->get("uploadMsg"))
    <script>
        alert("{{session()->get("uploadMsg")}}");
    </script>
@endif

@if (session()->get("msg"))
    <script>
        alert("{{session()->get("msg")}}");
    </script>
@endif

@if (session()->get("error"))
    <script>
        alert("{{session()->get("error")}}");
    </script>
@endif

<div id="app">

    <nav id="nav">
        <div class="nav-in">
            <div class="logo">
                <a href="/dashboard">
                    <img src="/storage/img/logo.png" alt="파인케어 로고">
                </a>
            </div>
            <div class="menus">
                <ul>
                    @foreach($config['menu'] as $menu)
                    <li class="{{ $menu['name'] == $access_menu ? "on" : "" }}">
                        <a href="{{ $menu['uri'] }}">{{ $menu['title'] }}</a>
                        <div class="sub-menu">
                            <ul>
                                @foreach($menu['sub_menu'] as $sub)
                                    <li>
                                        <a href="{{ $sub['uri'] ?? "" }}">
                                            {{ $sub['name'] ?? "" }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="user-info">
                <ul>
                    <li>
                        <p>
                            <b>{{ request()->cookie("account_id") }}</b> 님 환영합니다
                        </p>
                    </li>
                    <li>
                        <p>
                            사용기한: {{ request()->cookie("available_date") }} 까지
                        </p>
                    </li>
                </ul>
                <a href="{{ route("logout") }}">로그아웃</a>
            </div>
        </div>
    </nav>

    <main id="main">
        @yield("content")
    </main>

    <footer>
        <div class="footer-in">
            <p>COPYRIGHT 2020 FINECARE ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

</div>

<script>
    $(".nav-in .menus > ul > li").mouseenter(function() {
        $(".sub-menu", this).fadeIn(200);
    }).mouseleave(function() {
        $(".sub-menu", this).fadeOut(200);
    });

    var REDIRECT_URL = "<?=$_SERVER['REDIRECT_URL']?>";

    $("ul.ul_2depth li[data-uri='"+ REDIRECT_URL +"']").addClass("on");

</script>

<!--    <script src="//cdn.ckeditor.com/ckeditor5/22.0.0/balloon-block/ckeditor.js"></script>-->

</body>
</html>
