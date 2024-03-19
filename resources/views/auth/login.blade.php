<?php
//$test = password_hash("12345678", PASSWORD_BCRYPT, ["cost"=>12]);
//echo $test;

?>
    <!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>파인케어 | 로그인</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

@if (session("msg"))
    <x-toast-warning :msg="session('msg')" :type="session('type')" />
@endif


<section id="login">
    <div id="login-wrap">
        <div class="form-wrap">
            <div class="logo-wrap">
                <img class="left-logo-img" src="{{__IMG__}}/login_left.png" alt="로그인 이미지">
            </div>
        </div>
        <div class="form-wrap">
            <div class="padding-wrap">
                <div class="logo-wrap m-bottom-20">
                    <img src="{{__IMG__}}/login_logo.png" alt="로고">
                </div>
                <div class="m-bottom-40">
                    <h1>파인케어 사이트 방문을 환영합니다</h1>
                </div>
                <form action="/loginAction" method="POST">
                    @csrf
                    <div class="m-bottom-10">
                        <input type="text" name="id">
                    </div>
                    <div class="m-bottom-20">
                        <input type="password" name="password">
                    </div>
                    <div class="m-bottom-20">
                        <button type="submit">로그인</button>
                    </div>
                    <div class="login-bottom">
                        {{--                    <div class="left">--}}
                        {{--                        <input type="checkbox" name="keep_login" id="keep_login" value="1">--}}
                        {{--                        <label for="keep_login">로그인 상태 유지</label>--}}
                        {{--                    </div>--}}
                        <div class="right">
                            <ul>
                                <li><a href="">아이디찾기</a></li>
                                <li class="b-right-b7"> </li>
                                <li><a href="">비밀번호 찾기</a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="sub-link">
        <ul>
            @for($i = 0; $i < 7; $i++)
                <li><a href="#"><img src="{{__IMG__}}/login_sub.png" alt="이미지"></a></li>
            @endfor
        </ul>
    </div>
</section>

<script>

</script>

</body>
</html>
