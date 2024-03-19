@extends("layouts/layout")

@section("title")
    고객지원 - 자료실
@endsection

<?php
use App\Classes\Custom;
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
                        {{ $id->category }}
                    </td>
                    <th>일시</th>
                    <td>
                        {{ $id->created_at }}
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="qna_subject">제목</label>
                    </th>
                    <td colspan="3">
                        {{ $id->subject }}
                    </td>
                </tr>
                @if ($id->file1name || $id->file2name)
                    @if ($id->file1name)
                        <tr>
                            <th class="upload_list" colspan="4">
                                <p>
                                    <img src="{{__IMG__}}/icon_upload.png" alt="파일 업로드">
                                    <a href="{{ __URL__ }}/{{ $id->file1path }}" class="fc-sky">
                                        {{ $id->file1name }}
                                    </a>
                                    <small>({{ Custom::filesizeKB($id->file1path) }} kb)</small>
                                </p>
                            </th>
                        </tr>
                    @endif
                    @if ($id->file2name)
                        <tr>
                            <th class="upload_list" colspan="4">
                                <p>
                                    <img src="{{__IMG__}}/icon_upload.png" alt="파일 업로드">
                                    <a href="{{ __URL__ }}/{{ $id->file1path }}" class="fc-sky">
                                    {{ $id->file2name }}
                                </a>
                                    <small>({{ Custom::filesizeKB($id->file2path) }} kb)</small>
                                </p>
                            </th>
                        </tr>
                    @endif
                @endif
                <tr>
                    <th>
                        내용
                    </th>
                    <td colspan="3" class="min-contents">
                        <p>
                            {{ $id->content }}
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="btn-wrap m-top-20">

                <form action="/admin/board/archives/edit/{{ $id->id }}">
                    <button class="btn-cancel">수정</button>
                </form>
                <form action="/admin/board/archives" onsubmit="if(!confirm('삭제하시겠습니까?')) { return false; }" method="post">
                    @csrf
                    @method("delete")
                    <input type="hidden" name="id" value="{{ $id->id }}">
                    <button class="btn-cancel">삭제</button>
                </form>
                <a href="/support/lib/list" class="btn-black">목록</a>
            </div>

        </div>

    </article>

</section>

<style>
    form {
        display: inline-block;
    }

    a.btn-black {
        width: 123px;
        height: 40px;
        border: none;
        font-size: 15px;
        display: inline-block;
        vertical-align: top;
        line-height: 40px;
        color: #ffffff;
    }

    a.btn-black:hover {
        color: #ffffff;
    }
</style>

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
