@extends("layouts/layout")

@section("title")
    서비스내역 - 전자바우처내역 등록
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("service.side_nav")

    <section id="member_register" class="service-voucher-upload">

        <div class="head-info">
            <h1>전자바우처내역 등록</h1>
        </div>

        <div class="register-content">

            <div class="content-wrap">

                <ul>
                    <li>
                        <div class="box-in">
                            <div class="box-top">
                                <div class="img-wrap">
                                    <img src="/storage/img/member_basic_excel.png" alt="전자바우처 기본 양식 다운 받기">
                                </div>
                                <div class="text-wrap">

                                    <h3>전자바우처 기본 양식 다운 받기</h3>

                                    <a href="/storage/docs/전자바우처_기본_양식.xlsx">
                                        <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                                    </a>

                                    <p class="latest-info">전자바우처 등록 기본 양식 최근 업데이트 : {{ last_created_at("voucher_logs") }}</p>
                                    <p class="upload-info">
                                        엑셀파일에 필수사항을 반드시 입력해주세요.<br>
                                        각 항목은 안내된 설명을 확인하시고 반드시 형식에 맞게 입력하셔야합니다.
                                    </p>

                                </div>
                            </div>
                            <div class="box-bottom">

                                <h3 class="m-bottom-10">기본양식 파일 업로드</h3>
                                <p class="m-bottom-10">
                                    한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                </p>

                                <form action="/service/voucher/upload" method="post" enctype="multipart/form-data" onsubmit="return formValidation1(this)">
                                    @csrf
                                    <input type="hidden" name="type" value="old">
                                    <div class="m-bottom-15">
                                        <input type="radio" name="upload_type_basic" value="renew" id="upload_type_basic_1">
                                        <label for="upload_type_basic_1" class="m-right-10">업로드 파일로 갱신</label>
                                        <input type="radio" name="upload_type_basic" value="new" id="upload_type_basic_2">
                                        <label for="upload_type_basic_2">신규자료 업데이트</label>
                                    </div>
                                    <div class="upload-wrap">
                                        <input type="text" class="fileDrag" name="basic_file_name" placeholder="선택된 파일 없음" id="basic_file_name" readonly>
                                        <label for="basic_excel_upload">찾아보기</label>
                                        <input type="file" name="basic_excel_upload" id="basic_excel_upload">
                                    </div>

                                    <button class="btn-black m-top-20">업로드</button>
                                </form>

                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box-in">
                            <div class="box-top">
                                <div class="img-wrap">
                                    <img src="/storage/img/member_detail_excel.png" alt="전자바우처 등록 추가 양식 다운 받기">
                                </div>
                                <div class="text-wrap">

                                    <h3>전자바우처 신규 양식 다운 받기</h3>

                                    <p class="latest-info">전자바우처 등록 신규 양식 최근 업데이트 : {{ last_created_at("voucher_logs") }}</p>
                                </div>

                                <div class="download-info">
                                    <p>1. 차세대 전자바우처 사이트(<a href="http://nevs.socialservice.or.kr" class="fc-sky">http://nevs.socialservice.or.kr</a>) 에서 매출 및 정산 > 바우처이용내역조회(신규)로
                                        조회하셔서 사업유형을 전제로 선택하여 해당 월의 바우처이용내역조회를 엑셀로 내려받기 해주시기 바랍니다.
                                    </p>
                                    <p class="acc-orange">
                                        * 반드시 신규양식으로 다운로드하셔야 합니다.
                                    </p>
                                    <p class="m-top-10">
                                        2. 다운로드된 파일을 아래 파일 업로드 영역에 업로드하세요.
                                    </p>
                                    <p class="acc-orange">
                                        * 사회서비스 전자바우처 사이트에서 다운로드한 엑셀의 컬럼을 삭제하거나 순서를 변경한 경우에는 업로드가 되지
                                        않습니다.
                                    </p>

                                </div>

                            </div>
                            <div class="box-bottom">

                                <form action="/service/voucher/upload" method="post" enctype="multipart/form-data" onsubmit="return formValidation2(this)">
                                    @csrf
                                    <input type="hidden" name="type" value="new">
                                    <h3 class="m-bottom-10">신규양식 파일 업로드</h3>
                                    <p class="m-bottom-10">
                                        한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                                    </p>
                                    <div class="m-bottom-15">
                                        <input type="radio" name="detail_upload_type" value="renew" id="detail_upload_type_1">
                                        <label for="detail_upload_type_1" class="m-right-10">업로드 파일로 갱신</label>
                                        <input type="radio" name="detail_upload_type" value="new" id="detail_upload_type_2">
                                        <label for="detail_upload_type_2">신규자료 업데이트</label>
                                    </div>
                                    <div class="upload-wrap">
                                        <input type="text" class="fileDrag" name="detail_file_name" placeholder="선택된 파일 없음" id="detail_file_name" readonly>
                                        <label for="detail_excel_upload">찾아보기</label>
                                        <input type="file" name="detail_excel_upload" id="detail_excel_upload">
                                    </div>

                                    <button class="btn-black m-top-20">업로드</button>

                                </form>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>

        </div>

    </section>


    <script>
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

            } else if (thisname == "detail_file_name") {
                $inputfile = document.getElementById("detail_excel_upload");
                $inputtext = $("input[name='detail_file_name']")
            }

            $inputfile.files = files;
            $inputtext.val(files[0].name);
            $(this).css("background-color", "#ffffff");

        });



        $("#basic_excel_upload").on("change", function(e) {
            var filename = e.target.files[0].name;
            $("#basic_file_name").val(filename);
        });

        $("#detail_excel_upload").on("change", function(e) {
            var filename = e.target.files[0].name;
            $("#detail_file_name").val(filename);
        });

        function formValidation1(f)
        {
            if (!f.upload_type_basic.value) {
                alert("기본양식 업로드 유형을 선택해주세요.");
                return false;
            }

            if (!f.basic_excel_upload.value) {
                alert("기본양식을 선택해주세요");
                return false;
            }
        }

        function formValidation2(f)
        {
            if (!f.detail_upload_type.value) {
                alert("신규양식 업로드 유형을 선택해주세요.");
                return false;
            }

            if (!f.detail_excel_upload.value) {
                alert("신규양식을 선택해주세요");
                return false;
            }
        }
    </script>

@endsection
