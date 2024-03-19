@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 결제상품 관리
@endsection


@section("content")
    <section id="payment-product" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>결제상품 관리</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        <li class="right_button_li">
                            <form class="excel-form" action="">
                                <input type="hidden" name="board" value="">
                                <input type="hidden" name="category" value="">
                                <input type="hidden" name="keyword" value="">
                                <input type="hidden" name="start_date" value="">
                                <input type="hidden" name="end_date" value="">
                                <button class="excel_download_link" id="excel"></button>
                            </form>
                            <a href="#" class="select_modify_button">선택수정</a>
                            <a href="#" class="select_delete_button">선택삭제</a>
                            <a href="#" class="board_write_button">글쓰기</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="total_board">
                <p>총 문의내역 <span>1</span> 개</p>
            </div>
            <div class="search">
                <select class="search_select">
                    <option>게시판 ID</option>
                </select>
                <input type="text" class="search_input"/>
                <a href="#" class="search_button"></a>
            </div>
            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        <th class="board_th_check_all">
                            <input type="checkbox" name="check_all" id="board_check_all">
                            <label for="board_check_all"></label>
                        </th>
                        <th class="board_th_index01">결제상품명</th>
                        <th class="board_th_index02">금액</th>
                        <th class="board_th_index03">상태</th>
                        <th class="board_th_index04">비고</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--@foreach($lists as $key => $value)--}}
                    <tr class="board_tbody_tr">
                        <td>
                            <input type="checkbox" name="id[]" id="board_id_{{--{{$i}}--}}">
                            <label for="board_id_{{--{{$i}}--}}"></label>
                        </td>
                        <td><input type="text" value="12개월 이용권"></td>
                        <td><input type="text" value="600,000"></td>
                        <td>
                            <select>
                                <option>사용</option>
                                <option>미사용</option>
                            </select>
                        </td>
                        <td>
                            <a href="/admin/product/write" class="board_reply_button">수정</a>
                            <a href="" class="board_delete_button">삭제</a>
                        </td>
                    </tr>
                    {{--@endforeach--}}
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
