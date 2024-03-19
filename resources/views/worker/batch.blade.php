@extends("layouts/layout")

@section("title")
    활동지원사 일괄등록
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("worker.side_nav")

    @if(session()->get("batch_msg"))
        <script>
            alert("{{session()->get("batch_msg")}}");
        </script>
    @endif


    <section id="member_register">

        <div class="head-info">
            <h1>활동지원사 일괄등록</h1>
        </div>

        <div class="register-content">

            <div class="content-wrap">

                <ul>
                    @if (!isset($_GET['tax']))
                    <li>
                        <div class="box-in">

                            <div class="box-top">
                                <div class="img-wrap">
                                    <img src="/storage/img/member_basic_excel.png" alt="이용자 등록 기본 양식 다운 받기">
                                </div>
                                <div class="text-wrap">

                                    <h3>활동지원사 등록 기본 양식 다운 받기</h3>

                                    <a href="/storage/docs/활동지원사기본양식_바우처양식(파인케어다운로드).xlsx">
                                        <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                                    </a>

                                    <p class="latest-info">활동지원사 등록 기본 양식 최근 업데이트 : {{ last_created_at("helpers") }}</p>
                                    <p class="upload-info">
                                        엑셀파일에 필수사항을 반드시 입력해주세요.<br>
                                        각 항목은 안내된 설명을 확인하시고 반드시 형식에 맞게 입력하셔야합니다.
                                    </p>

                                </div>
                            </div>
                            <div class="box-bottom">

                                <form action="/worker/add/batch/basic" method="post" enctype="multipart/form-data">
                                    <h3 class="m-bottom-10">기본양식 파일 업로드</h3>
                                    <p class="m-bottom-10">
                                        한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                    </p>
                                    <div class="m-bottom-15">
                                        <input type="radio" name="upload_type_basic" value="renew" id="upload_type_basic_1" checked>
                                        <label for="upload_type_basic_1" class="m-right-10">업로드 파일로 갱신</label>
                                        <input type="radio" name="upload_type_basic" value="new" id="upload_type_basic_2">
                                        <label for="upload_type_basic_2">신규자료 업데이트</label>
                                    </div>
                                    <div class="upload-wrap">
                                        @csrf
                                        <input type="hidden" name="remember_token" value="{{session()->get("user_token")}}">
                                        <input type="text" class="fileDrag" name="basic_file_name" placeholder="선택된 파일 없음" readonly>
                                        <label for="basic_excel_upload">찾아보기</label>
                                        <input type="file" name="basic_excel_upload" id="basic_excel_upload" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"><br>
                                        <button class="m-top-20 btn-black">업데이트</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </li>
                    @endif
                    <li style="border: none;">
                        <ul class="divide-row">
                            <li class="{{ isset($_GET['tax']) ? "tax-in" : "" }}">
                                <div class="box-in">
                                    <div class="box-top">

                                        <div class="text-wrap">

                                            <h3>활동지원사 등록 추가 양식1 다운 받기</h3>


                                            <a href="/storage/docs/활동지원사추가양식1.xlsx">
                                                <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                                            </a>


                                            <p class="latest-info">활동지원사 등록 추가 양식 최근 업데이트 : {{ last_created_at("helper_details") }}</p>


                                        </div>
                                    </div>
                                    <div class="box-bottom">

                                        <form action="/worker/add/batch/detail" method="post" enctype="multipart/form-data">
                                            <h3 class="m-bottom-10">추가양식1 파일 업로드</h3>
                                            <p class="m-bottom-10">
                                                한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                            </p>
                                            <div class="m-bottom-15">
                                                <input type="radio" name="detail_upload_type" value="renew" id="detail_upload_type_1" checked>
                                                <label for="detail_upload_type_1" class="m-right-10">업로드 파일로 갱신</label>
                                                <input type="radio" name="detail_upload_type" value="new" id="detail_upload_type_2">
                                                <label for="detail_upload_type_2">신규자료 업데이트</label>
                                            </div>
                                            <div class="upload-wrap">
                                                @csrf
                                                <input type="text" class="fileDrag" name="detail_file_name" placeholder="선택된 파일 없음" readonly>
                                                <label for="detail_excel_upload">찾아보기</label>
                                                <input type="file" name="detail_excel_upload" id="detail_excel_upload" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                <br>
                                                <button class="m-top-20 btn-black">업데이트</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </li>

                            @if (!isset($_GET['tax']))
                            <li>
                                <div class="box-in">
                                    <div class="box-top">
                                        <div class="text-wrap">

                                            <h3>활동지원사 등록 추가 양식2 다운 받기</h3>

                                            <a href="/storage/docs/활동지원사추가양식2.xlsx">
                                                <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                                            </a>

                                            <p class="latest-info">활동지원사 등록 추가 양식 최근 업데이트 : {{ last_created_at("helper_details_second") }}</p>

                                        </div>
                                    </div>
                                    <div class="box-bottom">

                                        <form action="/worker/add/batch/detail2" method="post" enctype="multipart/form-data">
                                            <h3 class="m-bottom-10">추가양식2 파일 업로드</h3>
                                            <p class="m-bottom-10">
                                                한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                            </p>
                                            <div class="m-bottom-15">
                                                <input type="radio" name="detail_upload_type_2_1" value="renew" id="detail_upload_type_2_1" checked>
                                                <label for="detail_upload_type_2_1" class="m-right-10">업로드 파일로 갱신</label>
                                                <input type="radio" name="detail_upload_type_2_1" value="new" id="detail_upload_type_2_2">
                                                <label for="detail_upload_type_2_2">신규자료 업데이트</label>
                                            </div>
                                            <div class="upload-wrap">
                                                @csrf
                                                <input type="text" class="fileDrag" name="detail_file_name2" placeholder="선택된 파일 없음" readonly>
                                                <label for="detail_excel_upload2_2">찾아보기</label>
                                                <input type="file" name="detail_excel_upload2_2" id="detail_excel_upload2_2" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                <br>
                                                <button class="m-top-20 btn-black">업데이트</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>

                    </li>
                </ul>

            </div>

        </div>

    </section>

    <style>
        #member_register .register-content li .box-in .box-bottom label[for=basic_excel_upload], #member_register .register-content li .box-in .box-bottom label[for=detail_excel_upload2_2] {
            height: 33px;
            line-height: 33px;
            background-color: #111111;
            display: inline-block;
            color: #ffffff;
            font-size: 14px;
            padding: 0 15px;
        }

        #member_register .register-content li .box-in .box-bottom input[name=basic_file_name], #member_register .register-content li .box-in .box-bottom input[name=detail_file_name2] {
            height: 33px;
            padding: 0 10px;
        }

        .divide-row li {
            width: 100% !important;
        }

        #member_register .register-content .divide-row li .box-in .box-top {
            height: 150px !important;
        }

        #member_register .register-content .divide-row li .box-in .box-top {
            padding: 20px 0;
        }

        #member_register .register-content .divide-row li .box-in .box-top .text-wrap p.latest-info {
            margin-top: 10px;
        }

        #member_register .register-content .divide-row li .box-in .box-top .text-wrap button {
            margin-top: 10px;
        }

        #member_register .register-content .divide-row li .box-in .box-bottom {
            height: 174px;
            padding-top: 5px;
        }

        #member_register .register-content .divide-row li .box-in h3 {
            margin-bottom: 5px !important;
        }

        #member_register .register-content .divide-row li .box-in p {
            margin-bottom: 5px !important;
        }

        #member_register .register-content .divide-row li .box-in .m-bottom-15 {
            margin-bottom: 5px;
        }

        #member_register .register-content .divide-row li {
            margin-right: 0;
            border: 1px solid #b7b7b7;
            margin-bottom: 0 !important;
        }

        #member_register .register-content .divide-row li:last-child {
            /*margin-top: 30px;*/
            border-top: 0;
        }

        #member_register .register-content .divide-row li .box-in .btn-black {
            height: 28px;
            margin-top: 8px !important;
            vertical-align: bottom;
            margin-left: 10px;
        }

        #member_register .register-content .divide-row li {

        }

        li.tax-in {
            height: 700px;
            border-top: 1px solid #b7b7b7 !important;
        }

        #member_register .register-content .divide-row li.tax-in .box-in .box-top {
            height: 350px !important;
            padding-top: 130px;
        }

        #member_register .register-content .divide-row li.tax-in .box-in .box-bottom {
            height: 300px !important;
            padding-top: 80px;
        }

        #member_register .register-content .divide-row li.tax-in .box-in .btn-black {
            height: 40px;
        }

</style>


    <script>


        $(".fileDrag").on("dragenter", function(e) {
            e.preventDefault();
            e.stopPropagation();
        }).on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css("background-color", "#FFD8D8");
        }).on("drop", function(e){
            e.preventDefault();

            var files = e.originalEvent.dataTransfer.files;
            var $inputfile;
            var $inputtext;
            var thisname = $(this).attr("name");
            if (thisname == "basic_file_name") {
                $inputfile = document.getElementById("basic_excel_upload");
                $inputtext = $("input[name='basic_file_name']")

            } else if (thisname == "detail_file_name") {
                $inputfile = document.getElementById("detail_excel_upload");
                $inputtext = $("input[name='detail_file_name']")
            } else if (thisname == "detail_file_name2") {
                $inputfile = document.getElementById("detail_excel_upload2_2");
                $inputtext = $("input[name='detail_file_name2']")
            }

            $inputfile.files = files;
            $inputtext.val(files[0].name);
            $(this).css("background-color", "#ffffff");

        });



        $("#basic_excel_upload").on("change", function(e) {
            var _name = e.target.files[0]['name'];
            $("input[name='basic_file_name']").val(_name);
        });

        $("#detail_excel_upload").on("change", function(e) {
            var _name = e.target.files[0]['name'];
            $("input[name='detail_file_name']").val(_name);
        });

        $("#detail_excel_upload2_2").on("change", function(e) {
            var _name = e.target.files[0]['name'];
            $("input[name='detail_file_name2']").val(_name);
        });


        $(".sub-menu__list ul li.on").removeClass("on");
        $(".sub-menu__list ul li[data-uri='/worker']").addClass("on");



    </script>

@endsection
