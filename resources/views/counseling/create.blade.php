@extends("layouts/layout")

@section("title")
    상담일지 작성
@endsection

<?php
use App\Classes\Input;
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("addon.side_nav")
<section id="container" class="counseling-write">

    <article id="contents">
        <div class="contents-top">
            <h3>상담일지</h3>

            <div class="top-user-info">
                <span>
                    <img src="{{__IMG__}}/user_icon_small.png" alt="회원 정보">
                    {{ $type == "member" ? "이용자" : "활동지원사" }}:<b>{{ $target->name }} ({{$target->gender ?? "성별미입력"}}, {{ convert_birth($target->rsNo) }})</b>
                </span>
                <span>
                    <img src="{{__IMG__}}/icon_tel_small.png" alt="연락처">
                    {{ $target->phone }}
                </span>
                {{--<button class="btn-connect-user">--}}
                    {{--<img src="{{__IMG__}}/icon_6point.png" alt="연결된 이용자 보기">--}}
                    {{--연결된 이용자 보기--}}
                {{--</button>--}}
            </div>
        </div>

        <div class="contents-body">
            <form action="{{ isset($edit) ? "/counseling/log/edit" : "/counseling/log/store" }}" method="post" onsubmit="return formValidation(this)">
                @csrf
                @if (isset($edit))
                    @method("put")
                    <input type="hidden" name="id" value="{{$id}}">
                @endif
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="target_id" value="{{ $id }}">
                <table>
                    <tbody>
                    <tr>
                        <th>
                            <label for="writer">작성자</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="writer" id="writer" value="{{ $edit->writer ?? "" }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="category">분류</label>
                        </th>
                        <td>
                            <select name="category" id="category" required>
                                <option value="" >선택</option>
                                <option value="etc" {{ Input::select("etc", $edit->category ?? "") }}>특이사항</option>
                            </select>
                        </td>
                        <th>
                            <label for="way">상담방법</label>
                        </th>
                        <td>
                            <select name="way" id="way" required>
                                <option value="">선택</option>
                                <option value="tel" {{ Input::select("tel", $edit->way ?? "") }}>유선</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            시작일시
                        </th>
                        <td>
                            <input type="text" name="from_date" id="from_date" autocomplete="off" readonly required value="{{ $edit->from_date ?? "" }}">
                            <label for="from_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                            <input type="text" name="from_date_time" id="from_date_time" placeholder="시간입력" readonly autocomplete="off" required value="{{ $edit->from_date_time ?? "" }}">
                        </td>
                        <th>
                            종료일시
                        </th>
                        <td>
                            <input type="text" name="to_date" id="to_date" autocomplete="off" readonly required value="{{ $edit->to_date ?? "" }}">
                            <label for="to_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                            <input type="text" name="to_date_time" id="to_date_time" readonly autocomplete="off" placeholder="시간켜기" required value="{{ $edit->to_date_time ?? "" }}">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="title">제목</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="title" id="title" class="input-large" required value="{{ $edit->title ?? "" }}">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="content">내용</label>
                        </th>
                        <td colspan="3">
                            <textarea name="content" id="content" required>{{ $edit->content ?? "" }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="result">상담결과</label>
                        </th>
                        <td colspan="3">
                            <textarea name="result" id="result" required>{{ $edit->result ?? "" }}</textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="btn-wrap m-top-20">
                    <button type="button" class="btn-cancel" onclick="location.href='{{ route("counseling.log", [ "page" => 1 ]) }}'">취소</button>
                    <button type="submit" class="btn-submit">등록</button>
                </div>

            </form>

        </div>

    </article>

</section>

<script>

    function formValidation(f)
    {
        return true;
    }


    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

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
        clearButton: false,
        autoClose: true,
    });



    $("#from_date_time").datepicker({
        language: 'ko',
        timepicker: true,
        onlyTimepicker: true,
        timeFormat: "hh:ii",
    });

    $("#to_date_time").datepicker({
        language: 'ko',
        timepicker: true,
        onlyTimepicker: true,
        timeFormat: "hh:ii",
    });

    $("ul.ul_2depth a[href='/counseling/users/all']").parent("li").addClass("on");

</script>

@endsection
