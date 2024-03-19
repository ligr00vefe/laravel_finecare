@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 게시판 관리
@endsection


@section("content")
    <section id="board-modify" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>게시판 관리</h3>
            </div>
        </div>
        <form>
            <div class="table-wrap">
                <table>
                    <tr class="sub-title-tr">
                        <th colspan="4"><h3>게시판 기본 설정</h3></th>
                    </tr>
                    <tr class="sub-subject-tr">
                        <th>게시판 ID</th>
                        <td><input type="text" value="onlineboard"><a class="board-link" href="#">게시판 바로가기</a></td>
                    </tr>
                    <tr class="sub-subject-tr border-bottom-none">
                        <th>게시판 제목</th>
                        <td><input class="board-subject-input" type="text" value="온라인문의"></td>
                    </tr>
                    <tr class="sub-title-tr">
                        <th colspan="4"><h3>게시판 권한 설정</h3></th>
                    </tr>
                    <tr class="sub-subject-tr">
                        <th>목록보기 권한</th>
                        <td><select><option>정회원(2)</option></select></td>
                        <th>글읽기 권한</th>
                        <td><select><option>정회원(2)</option></select></td>
                    </tr>
                    <tr class="sub-subject-tr border-bottom-none">
                        <th>글쓰기 권한</th>
                        <td><select><option>정회원(2)</option></select></td>
                        <th>글 답변 권한</th>
                        <td><select><option>최고관리자(9)</option></select></td>
                    </tr>
                    <tr class="sub-title-tr">
                        <th colspan="4"><h3>게시판 기능 설정</h3></th>
                    </tr>
                    <tr class="sub-subject-tr border-bottom-none">
                        <th>비밀글 사용</th>
                        <td><select><option>사용</option></select></td>
                        <th>페이지당 목록수</th>
                        <td><input type="text" value="15"></td>
                    </tr>
                    <tr class="sub-title-tr">
                        <th colspan="4"><h3>게시판 디자인/양식</h3></th>
                    </tr>
                    <tr class="sub-subject-tr border-bottom-none">
                        <th>스킨디렉토리</th>
                        <td><select><option>bri_qna</option></select></td>
                    </tr>
                </table>
            </div>
            <div class="form-button-wrap">
                <div class="form-button-div"><a class="form-button-link list-link" href="#">목록</a></div>
                <div class="form-button-div"><a class="form-button-link confirm-link" href="#">확인</a></div>
            </div>
        </form>
    </section>
@endsection
