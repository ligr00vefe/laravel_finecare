@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 온라인 문의
@endsection

<?php
use App\Classes\Input;
use App\Classes\Custom;
?>

@section("content")
    <section id="inquiry" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>온라인 문의 내역</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        {{--<li class="right_button_li">--}}
                            {{--<a href="#" class="select_modify_button">선택수정</a>--}}
                            {{--                            <a href="#" class="board_write_button">글쓰기</a>--}}
                        {{--</li>--}}
                    </ul>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="category_wrap">
                <div class="sort-tab">
                    <ul>
                        <li class="{{ Input::pageOn("", $category) }}">
                            <a href="/admin/board/inquiry">전체</a>
                        </li>
                        <li class="{{ Input::pageOn("서비스문의", $category) }}">
                            <a href="/admin/board/inquiry?category=서비스문의">서비스문의</a>
                        </li>
                        <li class="{{ Input::pageOn("오류사항", $category) }}">
                            <a href="/admin/board/inquiry?category=오류사항">오류사항</a>
                        </li>
                        <li class="{{ Input::pageOn("개선사항", $category) }}">
                            <a href="/admin/board/inquiry?category=개선사항">개선사항</a>
                        </li>
                        <li class="{{ Input::pageOn("불만사항", $category) }}">
                            <a href="/admin/board/inquiry?category=불만사항">불만사항</a>
                        </li>
                        <li class="{{ Input::pageOn("기타사항", $category) }}">
                            <a href="/admin/board/inquiry?category=기타사항">기타사항</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="total_board">
                <p>총 문의내역 <span> {{$paging}}</span> 개</p>
            </div>
            <div class="search">
                <form action="">
                    <select class="search_select" name="filter">
                        <option value="subject">제목</option>
                    </select>
                    <input type="text" class="search_input" name="keyword" />
                    <button class="search_button" style="border: none;"></button>
                </form>
            </div>
            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        {{--<th class="board_th_check_all">--}}
                            {{--<input type="checkbox" name="check_all" id="board_check_all">--}}
                            {{--<label for="board_check_all"></label>--}}
                        {{--</th>--}}
                        <th class="board_th_index01">No</th>
                        <th class="board_th_index02">구분</th>
                        <th class="board_th_index03">제목</th>
                        <th class="board_th_index04">작성자</th>
                        <th class="board_th_index05">작성일</th>
                        <th class="board_th_index06">답변</th>
                        <th class="board_th_index07">비고</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($lists as $list)
                    <tr class="board_tbody_tr">
                        {{--<td>--}}
                            {{--<input type="checkbox" name="id[]" id="board_id_--}}{{--{{$i}}--}}{{--">--}}
                            {{--<label for="board_id_--}}{{--{{$i}}--}}{{--"></label>--}}
                        {{--</td>--}}
                        <td>{{ $list->id }}</td>
                        <td>{{ $list->category }}</td>
                        <td>{{ $list->subject }}</td>
                        <td>{{ Custom::userid_to_companyname($list->user_id) }}</td>
                        <td>{{ $list->created_at }}</td>
                        <td>{{ $list->answerCheck == 1 ? "답변완료" : "답변 전" }}</td>
                        <td>
                            <a href="/admin/board/inquiry/modify/{{$list->id}}" class="board_reply_button">수정</a>
                            <a href="/admin/board/inquiry/view/{{ $list->id }}" class="board_show_button">보기</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

                {!! pagination2(10, ceil($paging/15)) !!}
            </div>
        </div>
    </section>
@endsection
