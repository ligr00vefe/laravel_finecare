@extends("layouts/layout")

@section("title")
    고객지원 - 온라인 문의
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("support.side_nav")

<section id="container" class="qna-write qna-answer">

    <article id="contents" class="">
        <div class="contents-top">
            <h3>온라인 문의</h3>
        </div>

        <div class="contents-body m-top-20">

            <table>
                <colgroup>
                    <col width="10%">
                    <col width="40%">
                    <col width="10%">
                    <col width="40%">
                </colgroup>
                <tbody>
                <tr>
                    <th>
                        <label for="target_name" >구분</label>
                    </th>
                    <td>
                        서비스 문의
                    </td>
                    <th>일시</th>
                    <td>
                        2020-10-10 13:13:05
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="qna_subject">제목</label>
                    </th>
                    <td colspan="3">
                        문의드립니다.
                    </td>
                </tr>
                <tr>
                    <th class="upload_list" colspan="4">
                        <p>
                            <img src="{{__IMG__}}/icon_upload.png" alt="파일 업로드">
                            <span class="fc-sky">
                                문의관련첨부.hwp
                            </span>
                            <small>(1.21KB)</small>
                        </p>
                    </th>
                </tr>
                <tr>
                    <th>
                        내용
                    </th>
                    <td colspan="3" class="min-contents">
                        <p>
                            서비스 관련 문의 드립니다. <br>
                            답변해주시기 바랍니다.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th>
                        답변
                    </th>
                    <td colspan="3" class="min-contents">
                        <textarea name="" id="" cols="" rows=""></textarea>
                    </td>
                </tr>

                </tbody>
            </table>

            <div class="btn-wrap m-top-20">
                <button type="button" class="btn-cancel">취소</button>
                <button type="button" class="btn-black">등록</button>
            </div>

        </div>

    </article>

</section>

<script>
    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        timepicker: true,
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

            var _dateText = dateText.split(" ");

            var am_pm = _dateText[2] == "am" ? "오전" : "오후";

            $("input[name='from_date']").val(_dateText[0]);
            $("input[name='from_date_time']").val(am_pm + " " + _dateText[1]);

            $("input[name='to_date']").datepicker({
                minDate: new Date(dateText),
                dateFormat:"yyyy-mm-dd",
                clearButton: false,
                autoClose: true,
            })
        }
    });


    $("input[name='to_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        timepicker: true,
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

            var _dateText = dateText.split(" ");

            var am_pm = _dateText[2] == "am" ? "오전" : "오후";

            $("input[name='to_date']").val(_dateText[0]);
            $("input[name='to_date_time']").val(am_pm + " " + _dateText[1]);

        }
    })
</script>

@endsection
