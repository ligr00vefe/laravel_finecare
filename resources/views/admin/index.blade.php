@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 대시보드
@endsection


@section("content")


    <section id="dashboard" class="wrapper">

        <div class="head">
            <h3>대시보드</h3>
        </div>

        <div class="contents">

            <div class="contents-top m-bottom-20">
                <ul>
                    <li>
                        <div class="box">
                            <div class="box-info">
                                <p>총 회원 수</p>

                                <h1>{{ $count }}<span>명</span></h1>
                                <small>
                                    {{date("Y-m-d")}} 시작일 기준
                                </small>
                            </div>
                            <div class="box-img">
                                <img src="/storage/img/icon_dashboard_user_total.png" alt="총 이용자 수">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <div class="box-info">
                                <p>신규 회원 수</p>

                                <h1>{{ $new }}<span>명</span></h1>
                                <small>
                                    {{date("Y-m-d")}} 시작일 기준
                                </small>
                            </div>
                            <div class="box-img">
                                <img src="/storage/img/icon_new_user.png" alt="총 이용자 수">
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="box">
                            <div class="box-info">
                                <p>가입 현황 통계</p>

                                {{--<h1>4<span>명</span></h1>--}}
                                <br><br><br>
                                <small>
                                    _
                                </small>
                            </div>
                            <div class="box-img">
                                <img src="/storage/img/icon_new_user.png" alt="총 이용자 수">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="contents-body m-bottom-20">
                <div class="body-in">
                    <div class="menu-head">
                        <div class="float-left">
                            회원관리
                        </div>
                        <div class="float-right">
                            <a href="/admin/user">
                                더보기
                                <img src="/storage/img/icon_plus_white.png" alt="더보기">
                            </a>
                        </div>
                    </div>

                    <div class="body-contents">
                        <table class="stripe1">
                            <colgroup>
                                <col width="15%">
                                <col width="auto">
                                <col width="10%">
                                <col width="15%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>아이디</th>
                                <th>업체명</th>
                                <th>대표전화</th>
                                <th>유효기간</th>
                                <th>상태</th>
                                <th>등록일</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td>
                                    {{ $user->account_id }}
                                </td>
                                <td class="t-left">{{ $user->company_name }}</td>
                                <td>{{ $user->tel }}</td>
                                <td>{{ $user->payments_info->end_date ?? "" }}</td>
                                <td>
                                    {{ isset($user->payments_info->end_date) ? "서비스중" : "종료" }}
                                </td>
                                <td>{{ $user->created_at }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="6"></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="contents-footer">
                <ul>

                    <li>

                        <div class="menu-head over-hidden">
                            <div class="float-left">
                                결제내역
                            </div>
                            <div class="float-right">
                                <a href="/admin/payment">
                                    더보기
                                    <img src="/storage/img/icon_plus_white.png" alt="더보기">
                                </a>
                            </div>
                        </div>

                        <div class="menu-body">
                            <table class="stripe1">
                                <thead>
                                <tr>
                                    <th>결제상품</th>
                                    <th>업체명/회원ID</th>
                                    <th>결제일</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($payments as $payment)
                                <tr>
                                    <td>
                                        <p class="acc-orange">
                                            {{ $payment->goods_info->name ?? "" }}
                                        </p>
                                    </td>
                                    <td class="t-left">
                                        {{ $payment->user_info->company_name }} / {{ $payment->account_id }}
                                    </td>
                                    <td>
                                        {{ $payment->payment_date }}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">데이터가 없습니다</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </li>
                    <li>

                        <div class="menu-head over-hidden">
                            <div class="float-left">
                                온라인 문의
                            </div>
                            <div class="float-right">
                                <a href="/admin/board/inquiry">
                                    더보기
                                    <img src="/storage/img/icon_plus_white.png" alt="더보기">
                                </a>
                            </div>
                        </div>

                        <div class="menu-body">
                            <table class="stripe1">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>구분</th>
                                    <th>제목</th>
                                    <th>작성일</th>
                                    <th>답변</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($qnas as $qna)
                                <tr>
                                    <td>
                                        {{ $qna->id }}
                                    </td>
                                    <td>
                                        {{ $qna->category }}
                                    </td>
                                    <td class="t-left">
                                        {{ $qna->subject }}
                                    </td>
                                    <td>
                                        {{ date("Y-m-d", strtotime($qna->created_at)) }}
                                    </td>
                                    <td>
                                        <p class="acc-orange">
                                            {{ $qna->answerCheck == 1 ? "답변완료" : "답변예정" }}
                                        </p>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"></td>
                                    </tr>
                                @endforelse
                                <tr>
                                    <td>
                                        15
                                    </td>
                                    <td>
                                        서비스문의
                                    </td>
                                    <td class="t-left">
                                        회계관리 메뉴는 어디있나요?
                                    </td>
                                    <td>
                                        20-01-01
                                    </td>
                                    <td>
                                        <p class="acc-orange">답변예정</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        15
                                    </td>
                                    <td>
                                        서비스문의
                                    </td>
                                    <td class="t-left">
                                        회계관리 메뉴는 어디있나요?
                                    </td>
                                    <td>
                                        20-01-01
                                    </td>
                                    <td>
                                        <p class="acc-orange">답변예정</p>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </li>

                </ul>
            </div>

        </div>

    </section>

@endsection