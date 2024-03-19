@extends("layouts/layout")

@section("title")
    서비스내역 - 전자바우처 이용내역 검색
@endsection


@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("service.side_nav")

    <section id="member_wrap" class="service-voucher list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>전자바우처 이용내역 검색</h1>
                <div class="right-buttons">
                    <button type="button" class="btn-orange-wrap">
                        <img src="{{__IMG__}}/button_orange_plus.png" alt="항목별 활용방법 아이콘">
                        항목별 활용방법
                    </button>
                </div>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    @csrf
                    <table>
                        <thead>
                        <tr>
                            <th>기간</th>
                            <td>
                                <input type="text" name="from_date" id="from_date" class="input-datepicker" autocomplete="off">
                                <label for="from_date">
                                    <img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기">
                                </label>
                                <span> ~ </span>
                                <input type="text" name="to_date" id="to_date" class="input-datepicker" autocomplete="off">
                                <label for="to_date">
                                    <img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                검색조건
                            </th>
                            <td class="search-type__td">
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_1" value="총결제시간">
                                    <label for="search_type_1">
                                        <p>총결제시간</p>
                                    </label>
                                    <span>총 결제시간</span>
                                    <input type="text" name="total_time_1" id="total_time_1">
                                    <span>시간 이상 ~</span>
                                    <input type="text" name="total_time_2" id="total_time_2">
                                    <span>시간 미만</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_2" value="일일결제시간">
                                    <label for="search_type_2"><p>일일결제시간</p></label>
                                    <span>하루에</span>
                                    <input type="text" name="daily_total_time" id="daily_total_time">
                                    <span>시간 이상 결제</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_3" value="결제인원">
                                    <label for="search_type_3"><p>결제인원</p></label>
                                    <span>결제 인원이 ‘2인이상 결제’ 인경우</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_4" value="결제횟수">
                                    <label for="search_type_4"><p>결제횟수</p></label>
                                    <span>하루에</span>
                                    <input type="text" name="payment_count">
                                    <span>회 이상 결제</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_5" value="결제횟수2">
                                    <label for="search_type_5"><p>결제횟수</p></label>
                                    <input type="text" name="payment_count">
                                    <span>분내 연속결제 (대상자가 다른경우)</span>
                                    <input type="checkbox" name="same_people_check" id="same_people_check">
                                    <label for="same_people_check">같은 대상자도 포함</label>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_6" value="실근로시간">
                                    <label for="search_type_6"><p>실근로시간</p></label>
                                    <span>실근로시간이</span>
                                    <input type="text" name="wortime_1">
                                    <span>시간</span>
                                    <input type="text" name="wortime_2">
                                    <span>분 이상 ~ </span>
                                    <input type="text" name="wortime_3">
                                    <span>시간 </span>
                                    <input type="text" name="wortime_4">
                                    <span>분 미만</span>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_7" value="활동시간">
                                    <label for="search_type_7"><p>활동시간</p></label>
                                    <span>시작 시간이</span>
                                    <input type="text" name="activity_start_time_1">
                                    <span>시간 </span>
                                    <input type="text" name="activity_start_time_2">
                                    <span>분 이상 ~</span>
                                    <input type="text" name="activity_end_time_1">
                                    <span>시간</span>
                                    <input type="text" name="activity_end_time_2">
                                    <span>분 미만</span>

                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_8" value="활동시간">
                                    <label for="search_type_8"><p>활동시간</p></label>
                                    <span>학교 및 주단기 보호센터 이용시간 내 활동지원사 근무여부 확인</span>
                                    <div class="m-left-per-10">
                                        <p>이용자의 시설 이용 간련된 정보가 시스템에 등록되어 있지 않은 경우 적용할 조건을 입력하세요.(옵션)</p>
                                        <p>관련된 정보가 등록되어 있는 이용자에 한하여 검색을 할 경우에는 입력할 필요가 없습니다.</p>
                                        <div class="activity-desc-wrap">
                                            <p>-대상자(나이): <input type="text" name="target_age"> 세 (등록된 생년월일 기준으로 만 나이를 계산하여 비교)</p>
                                            <p>
                                                -요일: <input type="checkbox" name="week_selector" id="week_selector_1"><label for="week_selector_1">월</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_2"><label for="week_selector_2">화</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_3"><label for="week_selector_3">수</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_4"><label for="week_selector_4">목</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_5"><label for="week_selector_5">금</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_6"><label for="week_selector_6">토</label>
                                                <input type="checkbox" name="week_selector" id="week_selector_7"><label for="week_selector_7">일</label>
                                            </p>
                                            <p>
                                                -시간: 시작시간 <input type="text" name="start_time"> ~ 종료시간 <input type="text" name="end_time">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_9" value="결제유형">
                                    <label for="search_type_9"><p>결제유형</p></label>
                                    <span>결제유형이</span>
                                    <select name="payment_type" id="payment_type">
                                        <option value="">소급결제</option>
                                    </select>
                                    <span>이고, 승인시간이 활동내역 전 후 </span>
                                    <input type="text" name="confirm_time">
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_10" value="결제유형2">
                                    <label for="search_type_10"><p>결제유형</p></label>
                                    <span>소급결제의 승인일자가 </span>
                                    <input type="text" name="low_cost_confirm_date1">
                                    일 이후 ~
                                    <input type="text" name="low_cost_confirm_date2">
                                    일 이전
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_11" value="결제유형3">
                                    <label for="search_type_11"><p>결제유형</p></label>
                                    <span>소급결제의 승인시간이 </span>
                                    <input type="text" name="">
                                    이후 ~
                                    <input type="text" name="">
                                    이전
                                </div>
                                <div>
                                    <input type="checkbox" name="search_type[]" id="search_type_12" value="결제유형4">
                                    <label for="search_type_12"><p>결제유형</p></label>
                                    <span>소급결제의 사유가 다음과 같은경우(선택) </span>
                                    <div class="m-left-per-10 payment-type__checkbox-wrap">
                                        <div>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_1" value="서버 접속 실패(0104 오류 등)">
                                            <label for="payment_cause_1">서버 접속 실패(0104 오류 등)</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_2" value="단말기 업그레이드 등">
                                            <label for="payment_cause_2">단말기 업그레이드 등</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_3" value="대상자 과실(카드 미소자등)">
                                            <label for="payment_cause_3">대상자 과실(카드 미소자등)</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_4" value="대상자 과실(카드 미소지등)">
                                            <label for="payment_cause_4">대상자 과실(카드 미소지등)</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_5" value="제공인력 과실(단말기 조작미숙 등)">
                                            <label for="payment_cause_5">제공인력 과실(단말기 조작미숙 등)</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_6" value="방문간호와 활동보조 또는 방문목욕 증폭">
                                            <label for="payment_cause_6">방문간호와 활동보조 또는 방문목욕 증폭</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_7" value="카드 분실, 훼손 등">
                                            <label for="payment_cause_7">카드 분실, 훼손 등</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_8" value="이용자 또는 제공인력 카드 발급전">
                                            <label for="payment_cause_8">이용자 또는 제공인력 카드 발급전</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_9" value="긴급활동지원(카드발급전)">
                                            <label for="payment_cause_9">긴급활동지원(카드발급전)</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_10" value="단말기 분실, 고장 등">
                                            <label for="payment_cause_10">단말기 분실, 고장 등</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_11" value="2인이상 가족 동거">
                                            <label for="payment_cause_11">2인이상 가족 동거</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_12" value="2인이상 자립 생활 동거">
                                            <label for="payment_cause_12">2인이상 자립 생활 동거</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_13" value="통신음역지역">
                                            <label for="payment_cause_13">통신음역지역</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_14" value="제공인력 2인 서비스">
                                            <label for="payment_cause_14">제공인력 2인 서비스</label>
                                            <input type="checkbox" name="payment_cause[]" id="payment_cause_16" value="차량이용 서비스">
                                            <label for="payment_cause_16">차량이용 서비스</label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        </thead>
                    </table>
                    <div class="btn-wrap t-center">
                        <button class="btn-black">조회</button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents" class="m-top-30">

            <p>서비스내역 총 <b class="acc-orange">{{ $paging }}</b> 건</p>

            <table class="member-list b-last-bottom">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" value="1" id="check_all">
                        <label for="check_all"></label>
                    </th>
                    <th>
                        No
                    </th>
                    <th>
                        대상자명
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        등급
                    </th>
                    <th>
                        제공인력명
                    </th>
                    <th>
                        제공인력생년월일
                    </th>
                    <th>
                        시군구
                    </th>
                    <th>
                        사업유형ID
                    </th>
                    <th>
                        사업유형
                    </th>
                    <th>
                        서비스유형
                    </th>
                    <th>
                        승인일자
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($lists as $i => $list)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}" value="{{ $list->id }}">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>{{ ($i+1) + (15 * ($page - 1)) }}</td>
                        <td>{{ $list->target_name }}</td>
                        <td>{{ convert_birth($list->target_birth) }}</td>
                        <td>{{ $list->grade }}</td>
                        <td>{{ $list->provider_name }}</td>
                        <td>{{ convert_birth($list->provider_birth) ?? "" }}</td>
                        <td class="t-left">{{ $list->address }}</td>
                        <td>{{ $list->business_type_id }}</td>
                        <td>{{ $list->business_type }}</td>
                        <td>{{ $list->service_type }}</td>
                        <td>{{ date("Y-m-d", strtotime($list->confirm_date)) ?? "" }}</td>
                    </tr>
                @endforeach
                @if ($lists->isEmpty())
                    <tr>
                        <td colspan="12">데이터가 없습니다.</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination(10, ceil($paging/15)) !!}
        </article> <!-- article list_bottom end -->

    </section>

    <script>

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm",
                view: 'months',
                minView: 'months',
                clearButton: false,
                autoClose: true,
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소날짜 설정해주기
                    if (idx%2 == 0) {
                        $(datepicker_selector[idx+1]).datepicker({
                            minDate: new Date(dateText),
                            dateFormat:"yyyy-mm",
                            clearButton: false,
                            autoClose: true,
                        })
                    }

                }
            });

        });


    </script>

@endsection
