@extends("layouts/layout")

@section("title")
    기타수당 - 원거리교통비 등록
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("salary.side_nav")

@if (session()->get("uploadMsg"))
    <script>
        alert("{{session()->get("uploadMsg")}}");
    </script>
@endif

<section id="member_wrap" class="etc-transportation-wrap list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>원거리교통비 등록</h1>
        </div>


    </article> <!-- article list_head end -->

    <article id="contents">

        <div class="contents-box">

            <div class="box-top">
                <h3>
                    <img src="{{__IMG__}}/icon_doc_small.png" alt="문서아이콘">원거리교통비 파일
                </h3>

                <div class="m-top-20">
                    <p>
                        1. 차세대 전자바우처 사이트(<a href="http://nevs.socialservice.or.kr" target="_blank"><b class="fc-sky">http://nevs.socialservice.or.kr</b></a>) 에서 매출 및 정산 > 기타 수당 등 지급조회 > 교통지원금지급내역조회 > 검색버튼을 클릭하여  엑셀파일을 내려받기 하세요.
                    </p>
                    <p>
                        2. 내려받은 엑셀파일을 엑셀통합문서형식으로 다시 저장하세요.
                    </p>
                    <p>
                        3. 저장한 엑셀파일을 아래 파일 업로드 영역에 업로드 하세요.
                    </p>
                </div>
            </div>

            <div class="box-bottom">
                <h3>
                    해당 양식에 입력 완료 후 파일 업로드 하기
                </h3>
                <p class="m-top-10">
                    한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                </p>
                <form action="/etcIncome/transportation/register" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-wrap">
                        <input type="file" name="upload_file" id="upload_file">
                        <input class="fileDrag" type="text" name="upload_file_text" id="upload_file_text" placeholder="선택된 파일 없음">
                        <label for="upload_file">찾아보기</label>
                    </div>
                    <button class="btn-black m-top-10">업로드</button>
                </form>
            </div>

        </div>

    </article>


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
            if (thisname == "upload_file_text") {
                $inputfile = document.getElementById("upload_file");
                $inputtext = $("input[name='upload_file_text']")
            }

            $inputfile.files = files;
            $inputtext.val(files[0].name);
            $(this).css("background-color", "#ffffff");

        });


        $("#upload_file").on("change", function(e) {
            var filename = e.target.files[0].name;
            $("#upload_file_text").val(filename);
        });
    </script>

</section>
@endsection
