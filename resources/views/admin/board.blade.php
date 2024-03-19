@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 게시판 관리
@endsection


@section("content")
    <section id="board" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>게시판 관리</h3>
                <div class="right-buttons">
                    {{--<ul class="right_button_ul">--}}
                        {{--<li class="right_button_li">--}}
                            {{--<a href="#" class="select_modify_button">선택수정</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="total_board">
                <p>총 문의내역 <span> {{ $paging }}</span> 개</p>
            </div>
            <div class="search">
                <select class="search_select">
                    <option value="subject">제목</option>
                </select>
                <input type="text" class="search_input"/>
                <a href="#" class="search_button"></a>
            </div>
            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        {{--<th class="board_th_check_all">--}}
                            {{--<input type="checkbox" name="check_all" id="board_check_all">--}}
                            {{--<label for="board_check_all"></label>--}}
                        {{--</th>--}}
                        <th class="board_th_index01">게시판명</th>
                        <th class="board_th_index02">제목</th>
                        <th class="board_th_index03">작성자</th>
                        <th class="board_th_index04">날짜</th>
                        <th class="board_th_index05">비고</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $key => $list)
                    <tr class="board_tbody_tr">
                        {{--<td>--}}
                            {{--<input type="checkbox" name="id[]" id="board_id_--}}{{--{{$i}}--}}{{--">--}}
                            {{--<label for="board_id_--}}{{--{{$i}}--}}{{--"></label>--}}
                        {{--</td>--}}
                        <td>
                            {{ $list->table }}
                        </td>
                        <td>
                             {{ $list->subject }}
                        </td>
                        <td>
                            {{ $list->user_id }}
                        </td>
                        <td>
                            {{ $list->created_at }}
                        </td>
                        <td>
                            <a href="/admin/board/modify" class="board_reply_button">수정</a>
                            <a href="{{ $list->url }}/{{ $list->id }}" class="board_show_button">보기</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
