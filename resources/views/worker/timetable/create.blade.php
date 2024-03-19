@extends("layouts/layout")

@section("title")
    유급휴일 근무계획
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")

    @if(session()->get("batch_msg"))
        <script>
            alert("{{session()->get("batch_msg")}}");
        </script>
    @endif


    <section id="member_register">

        <div class="head-info">
            <h1>유급휴일 근무계획</h1>
        </div>

        <div class="register-content">

            <div class="content-wrap">

                <ul>
                    <li>
                        <div class="box-in">
                            <div class="box-top">
                                <div class="img-wrap">
                                    <img src="/storage/img/member_basic_excel.png" alt="이용자 등록 기본 양식 다운 받기">
                                </div>
                                <div class="text-wrap">

                                    <h3>활동지원사 계획표 다운로드 받기</h3>

                                    <form action="{{ route("excel.export.schedule") }}" method="post">
                                        @csrf
                                        <button>
                                            <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                                        </button>
                                    </form>


                                    <p class="latest-info">활동지원사 계획표 양식 최근 업데이트 : {{ $last_updated }}</p>
                                    <p class="upload-info">
                                        엑셀파일에 필수사항을 반드시 입력해주세요.<br>
                                        각 항목은 안내된 설명을 확인하시고 반드시 형식에 맞게 입력하셔야합니다.
                                    </p>

                                </div>
                            </div>
                            <div class="box-bottom">

                                <form action="/worker/timetable/upload" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <h3 class="m-bottom-10">기본양식 파일 업로드</h3>
                                    <p class="m-bottom-10">
                                        한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                    </p>

                                    <div class="upload-wrap">
                                        <input type="hidden" name="remember_token" value="{{session()->get("user_token")}}">
                                        <input class="fileDrag" type="text" name="basic_file_name" placeholder="선택된 파일 없음" readonly>
                                        <label for="basic_excel_upload">찾아보기</label>
                                        <input type="file" name="timetable" id="basic_excel_upload" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"><br>
                                        <button class="m-top-20 btn-black">업데이트</button>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </li>
                </ul>

            </div>

        </div>

    </section>


    <script>
        $("#basic_excel_upload").on("change", function(e) {
            var _name = e.target.files[0]['name'];
            $("input[name='basic_file_name']").val(_name);
        });

        $("#detail_excel_upload").on("change", function(e) {
            var _name = e.target.files[0]['name'];
            $("input[name='detail_file_name']").val(_name);
        });

        $(".fileDrag").on("dragenter", function(e) {
            e.preventDefault();
            e.stopPropagation();
        }).on("dragover", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).css("background-color", "#FFD8D8");
        }).on("drop", function(e) {
            e.preventDefault();

            var files = e.originalEvent.dataTransfer.files;
            var $inputfile;
            var $inputtext;
            var thisname = $(this).attr("name");
            if (thisname == "basic_file_name") {
                $inputfile = document.getElementById("basic_excel_upload");
                $inputtext = $("input[name='basic_file_name']")
            }

            $inputfile.files = files;
            $inputtext.val(files[0].name);
            $(this).css("background-color", "#ffffff");

        });


        $("a[href='/worker/timetable/upload']").parent("li").addClass("on");
    </script>

@endsection
