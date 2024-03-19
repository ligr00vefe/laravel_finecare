@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - FAQ 관리
@endsection


@section("content")
    <section id="faq-modify" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>FAQ 관리</h3>
            </div>
        </div>
        <div class="table_wrap">
            <table class="board_table">
                    <tr class="sub-subject-tr">
                        <th>질문</th>
                        <td><input class="question-input" type="text"></td>
                    </tr>
                    <tr class="sub-subject-tr textarea-tr">
                        <th>답변</th>
                        <td><textarea class="question-textarea"></textarea></td>
                    </tr>
            </table>
        </div>
        <div class="form-button-wrap">
            <div class="form-button-div"><a class="list-link form-button-link" href="#">목록</a></div>
            <div class="form-button-div"><a class="confirm-link form-button-link" href="#">확인</a></div>
        </div>
    </section>
@endsection
