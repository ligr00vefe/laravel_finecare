@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 결제관리
@endsection


@section("content")
    <section id="payment" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>결제 관리</h3>
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
            <div class="check-select-box">
                <div class="sub-date-line">
                    <div class="sub-title"><p class="sub-subject">결제상품</p></div>
                    <ul class="product-ul pay-ul">
                        <li><input id="all-check-product" type="checkbox" value="all-product"><label for="all-check-product">전체</label></li>
                        <li><input id="one-month" type="checkbox" value="1"><label for="one-month">1개월 이용권</label></li>
                        <li><input id="three-month" type="checkbox" value="3"><label for="one-month">3개월 이용권</label></li>
                        <li><input id="six-month" type="checkbox" value="6"><label for="one-month">6개월 이용권</label></li>
                        <li><input id="twelve-month" type="checkbox" value="12"><label for="one-month">12개월 이용권</label></li>
                    </ul>
                </div>
                <div>
                    <div class="sub-title"><p class="sub-subject">결제수단</p></div>
                    <ul class="payment-ul pay-ul">
                        <li><input id="all-check-payment" type="checkbox" value="all-payment"><label for="all-check-payment">전체</label></li>
                        <li><input id="credit-card" type="checkbox" value="card"><label for="credit-card">신용카드</label></li>
                    </ul>
                </div>
                <div>
                    <div class="sub-title"><p class="sub-subject">결제일자</p></div>
                    <input class="sub-day-1" type="text">
                    <span class="day-spacer">~</span>
                    <input class="sub-day-2" type="text">
                    <ul class="day-ul pay-ul">
                        <li>오늘</li>
                        <li>어제</li>
                        <li>이번주</li>
                        <li>이번달</li>
                        <li>지난주</li>
                        <li>지난달</li>
                        <li>전체</li>
                        <li class="day-button">검색</li>
                    </ul>
                </div>
            </div>
            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        <th class="board_th_check_all">
                            <input type="checkbox" name="check_all" id="board_check_all">
                            <label for="board_check_all"></label>
                        </th>
                        <th class="board_th_index01">결제상품</th>
                        <th class="board_th_index02">업체명/회원ID</th>
                        <th class="board_th_index03">결제수단</th>
                        <th class="board_th_index04">금액</th>
                        <th class="board_th_index05">결제일</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--@foreach($lists as $key => $value)--}}
                    <tr class="board_tbody_tr">
                        <td>
                            <input type="checkbox" name="id[]" id="board_id_{{--{{$i}}--}}">
                            <label for="board_id_{{--{{$i}}--}}"></label>
                        </td>
                        <td>1개월 이용권</td>
                        <td>한국시 한국기관/1234567890</td>
                        <td>신용카드</td>
                        <td>600,000</td>
                        <td>2020-01-01 00:00:00</td>
                    </tr>
                    {{--@endforeach--}}
                    <tr class="board_result_tr">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>합계</td>
                        <td>9,000,000</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
