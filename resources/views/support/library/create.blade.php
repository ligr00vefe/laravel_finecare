@extends("layouts/layout")

@section("title")
    고객지원 - 자료실
@endsection

<?php
use App\Classes\Input;
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("support.side_nav")

<section id="container" class="qna-write">

    <article id="contents" class="">
        <div class="contents-top">
            <h3>자료실</h3>
        </div>

        <div class="contents-body m-top-20">
            <form action="{{ isset($edit) ? route("support.lib.update", [ "id" => $id->id ]) : route("support.lib.store") }}" method="post" enctype="multipart/form-data">
                @csrf
                @if (isset($edit))
                    @method("put")
                @endif

                <table>
                    <tbody>
                    <tr>
                        <th>
                            <label for="category" >구분</label>
                        </th>
                        <td colspan="3">
                            <select name="category" id="category">
                                <option value="">선택해주세요</option>
                                <option value="서비스문의" {{ Input::select($id->category ?? "", "서비스문의") }}>서비스문의</option>
                                <option value="오류사항" {{ Input::select($id->category ?? "", "오류사항") }}>오류사항</option>
                                <option value="개선사항" {{ Input::select($id->category ?? "", "개선사항") }}>개선사항</option>
                                <option value="불만사항" {{ Input::select($id->category ?? "", "불만사항") }}>불만사항</option>
                                <option value="기타사항" {{ Input::select($id->category ?? "", "기타사항") }}>기타사항</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="subject">제목</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="subject" id="subject" class="input-large" value="{{ $id->subject ?? "" }}">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="content">내용</label>
                        </th>
                        <td colspan="3">
                            <textarea name="content" id="content">{{ $id->content ?? "" }}</textarea>
                        </td>
                    </tr>

                    <tr>
                        <th>
                            첨부파일
                        </th>
                        <td colspan="3" class="file-wrap">
                            <input type="text" name="upload_file_1_name" id="upload_file_1_name" placeholder="선택된 파일 없음" class="input-large" readonly value="{{ $id->file1name ?? "" }}">
                            <label for="file1" class="file-label">찾아보기</label>
                            <input type="file" name="file1" id="file1">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            첨부파일
                        </th>
                        <td class="file-wrap" colspan="3">
                            <input type="text" name="upload_file_2_name" id="upload_file_2_name" placeholder="선택된 파일 없음" class="input-large" readonly value="{{ $id->file2name ?? "" }}">
                            <label for="file2" class="file-label">찾아보기</label>
                            <input type="file" name="file2" id="file2">
                        </td>
                    </tr>

                    </tbody>
                </table>


                <div class="btn-wrap m-top-30">
                    <button type="button" class="btn-cancel">취소</button>
                    <button class="btn-submit">등록</button>
                </div>
            </form>
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
