@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 결제상품 관리
@endsection


@section("content")
    <section id="payment-product-write" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>결제상품 관리</h3>
            </div>
        </div>
        <div class="table-wrap">
            <table class="write-table">
                <tr class="write-table-tr write-subject-tr">
                    <th class="write-table-th table-title-th">상품명</th>
                    <td><input class="write-table-input" placeholder="숫자, 영어, 한글만 가능하며 특수문자 삽입은 불가능합니다" type="text" value=""/></td>
                </tr>
                <tr class="write-table-tr write-content-th">
                    <th class="write-table-th">금액</th>
                    <td>
                        <input class="write-table-input length-small" type="text" value=""/>
                        <span>원</span>
                    </td>
                </tr>
                <tr class="write-table-tr write-content-th">
                    <th class="write-table-th">사용여부</th>
                    <td>
                        <select class="write-table-select length-small">
                            <option>사용</option>
                            <option>미사용</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="form-button-wrap">
            <div class="form-button-div"><a class="list-link form-button-link" href="#">목록</a></div>
            <div class="form-button-div"><a class="confirm-link form-button-link" href="#">확인</a></div>
        </div>
    </section>
@endsection
