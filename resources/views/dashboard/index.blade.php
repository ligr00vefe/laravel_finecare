@extends("layouts/layout")

@section("title")
    대시보드 - 대시보드 조회
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("addon.dashboard_nav")

    {{--{{dd($questionLists)}}--}}

    <section id="dashboard-wrap" class="dashboard-wrapper">
        <div id="dashboard">
            <div>
                <div>
                    <div class="title-wrap"><h2>Quick menu</h2></div>
                    <div class="dashboard-icon-wrap">
                        <div>
                            <a href="/member/find">
                                <img src="{{__IMG__}}dashboard_icon_01.svg" alt="아이콘1">
                                <p>이용자 찾기</p>
                            </a>
                        </div>
                        <div>
                            <a href="/worker/find">
                                <img src="{{__IMG__}}dashboard_icon_02.svg" alt="아이콘2">
                                <p>활동지원사 찾기</p>
                            </a>
                        </div>
                        <div>
                            <a href="/service/list/exist">
                                <img src="{{__IMG__}}dashboard_icon_03.svg" alt="아이콘3">
                                <p>전자바우처 등록</p>
                            </a>
                        </div>
                        <div>
                            <a href="/service/list/exist">
                                <img src="{{__IMG__}}dashboard_icon_04.svg" alt="아이콘4">
                                <p>전자바우처 조회</p>
                            </a>
                        </div>
                        <div>
                            <a href="/salary/calc">
                                <img src="{{__IMG__}}dashboard_icon_05.svg" alt="아이콘5">
                                <p>급여계산</p>
                            </a>
                        </div>
                        <div>
                            <a href="/salary/paybook">
                                <img src="{{__IMG__}}dashboard_icon_06.svg" alt="아이콘6">
                                <p>급여대장</p>
                            </a>
                        </div>
                        <div>
                            <a href="/salary/payslip">
                                <img src="{{__IMG__}}dashboard_icon_07.svg" alt="아이콘7">
                                <p>급여명세서</p>
                            </a>
                        </div>
                        <div>
                            <a href="/counseling/users/all/1">
                                <img src="{{__IMG__}}dashboard_icon_08.svg" alt="아이콘8">
                                <p>상담</p>
                            </a>
                        </div>
                        <div>
                            <a href="/support/faq/list">
                                <img src="{{__IMG__}}dashboard_icon_09.svg" alt="아이콘9">
                                <p>자주묻는 질문</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="dashboard-board-wrap">
                    <div class="board-question dashboard-board">
                        <div class="title-wrap">
                            <h2>온라인 문의</h2>
                            <div class="more-button"><a href="support/qna/list">더보기</a></div>
                        </div>
                        <div>
                            <table>
                                @foreach($questionLists as $list)
                                    <tr>
                                        <td>{{$list->id}}</td>
                                        <td>{{$list->category}}</td>
                                        <td><a href="/support/qna/view/{{$list->id}}">{{$list->subject}}</a></td>
                                        <td>{{date("y-m-d", strtotime($list->created_at))}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="board-data dashboard-board">
                        <div class="title-wrap">
                            <h2>자료실</h2>
                            <div class="more-button"><a href="/support/lib/list">더보기</a></div>
                        </div>
                        <table>
                            @foreach($dataLists as $list)
                                <tr>
                                    <td>{{$list->id}}</td>
                                    <td><a href="#">{{$list->subject}}</a></td>
                                    <td>{{$list->created_at}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
