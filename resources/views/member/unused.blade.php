@extends("layouts/layout")

@section("title")
    이용자 - 이용자 명단
@endsection

<?php

?>



@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("member.side_nav")

    @if (session()->get("addMsg"))
        <script>
            alert("{{session()->get("addMsg")}}");
        </script>
    @endif

    <section id="member_wrap" class="list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>서비스 미이용현황</h1>
            </div>


            <div class="search-wrap">
                <form action="" method="get" name="member_list_search">
                    <div class="limit-wrap">
                        <input type="text" name="target_ym" autocomplete="off" readonly id="target_ym" value="{{ $_GET['target_ym'] ?? "" }}">
                        <label for="target_ym">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
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

        <article id="list_contents" style="overflow-x: auto;">
            <table class="member-list in-input table-2x-large">
                <colgroup>
                    <col width="2%">
                    <col width="3%">
                    <col width="3%">
                    <col width="5%">
                    <col width="3%">
                    <col width="4%">
                    <col width="4%">
                    <col width="5%">
                    <col width="6%">
                    <col width="6%">
                    <col width="6%">
                    <col width="6%">
                    <col width="auto">
                    <col width="5%">
                    <col width="7%">
                    <col width="7%">
                    <col width="7%"> <!-- 17개 -->
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="check_all" name="check_all" value="1">
                        <label for="check_all"></label>
                    </th>
                    <th>No</th>
                    <th>이름</th>
                    <th>생년월일</th>
                    <th>성별</th>
                    <th>주장애명</th>
                    <th>주장애등급</th>
                    <th>부장애명</th>
                    <th>수급여부</th>
                    <th>활동지원등급</th>
                    <th>활동지원등급유형</th>
                    <th>전화번호</th>
                    <th>주소</th>
                    <th>상태</th>
                    <th>접수일</th>
                    <th>계약시작일</th>
                    <th>계약종료일</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $i => $list)
                    <tr>
                        <td>
                            <input type="checkbox" id="check_{{$i}}" name="id[]" value="{{$list->id}}" disabled>
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>{{ (($page - 1) * 15) + $loop->iteration  }}</td>
                        <td>
                            <input type="text" name="{{ "name[{$i}]" }}" value="{{ $list->name  }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"birth[{$i}"}}" value="{{ $list->rsNo  }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"gender[{$i}]"}}" value="{{ \App\Classes\Custom::rsno_to_gender($list->rsNo) }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"disabled_type[{$i}]"}}" value="{{ $list->main_disable_name ?? ""  }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"disabled_grade[{$i}]"}}" value="{{ $list->main_disable_grade ?? ""  }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"sub_disabled_type[{$i}"}}" value="{{ $list->sub_disable_name ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"income_grade[{$i}]"}}" value="{{ $list->income_check ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"activity_support_grade[{$i}]"}}" value="{{ $list->activity_support_grade_new ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"activity_support_grade_type[{$i}]"}}" value="{{ $list->activity_support_grade_type ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"tel[{$i}]"}}" value="{{ $list->tel ?? "" }}" disabled>
                        </td>
                        <td class="t-left">
                            <textarea name="{{"addr[{$i}]"}}" resize="none" disabled>{{ $list->address ?? "" }}</textarea>
                        </td>
                        <td>
                            <input type="text" name="{{"status[{$i}]"}}" value="이용중" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"regdate[$i}]"}}" value="{{ $list->regdate ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"startDate[{$i}]"}}" value="{{ $list->contract_start_date ?? "" }}" disabled>
                        </td>
                        <td>
                            <input type="text" name="{{"endDate[{$i}]"}}" value="{{ $list->contract_end_date ?? "" }}" disabled>
                        </td>
                    </tr>
                @endforeach
                @if ($lists->isEmpty())
                    <tr>
                        <td colspan="17">데이터가 없습니다.</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination2(10, ceil($paging/15)) !!}
        </article> <!-- article list_bottom end -->

    </section>


    <script>
        $("input[name='target_ym']").datepicker({
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



    </script>
@endsection
