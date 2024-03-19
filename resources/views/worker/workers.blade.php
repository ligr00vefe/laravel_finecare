@extends("layouts/layout")

@section("title")
    활동지원사 - 활동지원사 명단
@endsection

<?php
use App\Classes\Custom;
?>

@section("content")
<link rel="stylesheet" href="/css/member/index.css">

@include("worker.side_nav")
<section id="member_wrap" class="list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>활동지원사 명단</h1>
            <div class="action-wrap">
                <ul>
                    <li>
                        <button onclick="location.href='{{ route("worker.add", [ "type" => "one" ]) }}'">개별등록</button>
                    </li>
                    <li>
                        <button onclick="location.href='{{ route("worker.add", [ "type" => "batch" ]) }}'">일괄등록</button>
                    </li>
                    {{--<li>--}}
                        {{--<button>일괄수정</button>--}}
                    {{--</li>--}}
                    <li>
                        <form action="{{ route("excel.export.helpers") }}" class="dis-ib" method="post">
                            @csrf
                            <input type="hidden" name="from_date" value="<?=$_GET['from_date'] ?? "" ?>">
                            <input type="hidden" name="to_date" value="<?=$_GET['to_date'] ?? "" ?>">
                            <button>명단내려받기</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <div class="search-wrap">
            <form action="" method="get" name="member_list_search">
                <div class="limit-wrap">
                    <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                    <label for="from_date">
                        <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                    </label>
                    <span>~</span>
                    <input type="text" name="to_date" autocomplete="off" readonly id="to_date">
                    <label for="to_date">
                        <img src="/storage/img/icon_calendar.png" alt="종료날짜">
                    </label>
                    <button type="submit">조회</button>
                </div>
                <div class="search-input">
                    <input type="text" name="term" placeholder="검색">
                    <button type="submit">
                        <img src="/storage/img/search_icon.png" alt="검색하기">
                    </button>
                </div>
            </form>
        </div>

    </article> <!-- article list_head end -->

    <article id="list_contents">
        <table class="member-list in-input">
            <colgroup>
                {{--<col width="3%">--}}
                <col width="3%">
                <col width="5%">
                <col width="9%">
                <col width="5%">
                <col width="10%">
                <col width="20%">
            </colgroup>
            <thead>
            <tr>
                {{--<th>--}}
                    {{--<input type="checkbox" id="check_all" name="check_all" value="1">--}}
                    {{--<label for="check_all"></label>--}}
                {{--</th>--}}
                <th>No</th>
                <th>이름</th>
                <th>생년월일</th>
                <th>성별</th>
                <th>전화번호</th>
                <th>주소</th>
                <th>상태</th>

                <th>입사일</th>
                <th>퇴사일</th>
                <th>근속연수</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($lists as $i => $list)
            <tr>
                {{--<td>--}}
                    {{--<input type="checkbox" id="check_{{$i}}" name="id[]" value="{{$list->id}}">--}}
                    {{--<label for="check_{{$i}}"></label>--}}
                {{--</td>--}}
                <td>{{ $paging - ($loop->iteration - 1)  }}</td>
                <td>
                    <input type="text" name="name[]" value="{{ $list->name  }}">
                </td>
                <td>
                    <input type="text" name="rsNo[]}" value="{{ substr($list->birth, 0, 6)  }}">
                </td>
                <td>
                    <input type="text" name="gender[]" value="{{ Custom::rsno_to_gender($list->birth) }}">
                </td>
                <td>
                    <input type="text" name="tel[]" value="{{ $list->tel  }}">
                </td>
                <td class="t-left">
                    <textarea name="address[]" resize="none">{{ $list->address }}</textarea>
                </td>
                <td>
                    {{ $list->contract }}
                </td>

                <td>
                    <input type="text" name="regdate[]" value="{{ date("Y-m-d", strtotime($list->contract_start_date))  }}">
                </td>
                <td>
                    <input type="text" name="join_date[]" value="{{ date("Y-m-d", strtotime($list->contract_end_date)) != "1970-01-01" ? date("Y-m-d", strtotime($list->contract_end_date)) : ""  }}">
                </td>

                <td>
                    <input type="text" name="length[]" value="{{ $list->contract_date  }}">
                </td>
            </tr>
            @endforeach
            @if ($lists->isEmpty())
                <tr>
                    <td colspan="12" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
                </tr>
            @endif
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <article id="list_bottom">
    {!! pagination2(10, ceil($paging/15)) !!}
    </article> <!-- article list_bottom end -->

</section>

<style>
    textarea {
        outline: none;
    }
</style>


<script>

    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {
            $("input[name='to_date']").datepicker({
                minDate: new Date(dateText),
                dateFormat:"yyyy-mm",
                clearButton: false,
                autoClose: true,
            })
        }
    });


    $("input[name='to_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

        }
    })

    document.addEventListener("DOMContentLoaded", function () {
        $("input, textarea").attr("readonly", true);
    })


    $(".sub-menu__list ul li.on").removeClass("on");
    $(".sub-menu__list ul li[data-uri='/worker']").addClass("on");
</script>
@endsection
