@extends("layouts/layout")

@section("title")
    사회보험 - 사회보험 가입정보 관리
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")


    <section id="member_wrap" class="social-insurance-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>사회보험 가입정보 관리 <span>활동지원사의 사회보험 가입 정보를 일괄로 수정합니다. 활동지원사 추가 양식1 업로드가 필요합니다.</span></h1>
                <div class="right-buttons">
                    <button class="btn-black-middle" id="btnSubmit">
                        선택수정
                    </button>
                    <a href="/worker/add/batch?tax=1" class="upload-btn">
                        양식업로드
                    </a>
                </div>
            </div>

        </article> <!-- article list_head end -->

        <style>
            .upload-btn {
                padding: 0 14px;
                height: 33px;
                font-size: 14px;
                display: inline-block;
                vertical-align: top;
                line-height: 33px;
                background-color: transparent;
                color: #eb8626 !important;
                border: 1px solid #eb8626;
            }
        </style>



        <article id="list_contents" style="max-height: none">
            <form action="{{ route('social.insurance.store') }}" method="post" name="insuranceEditForm" >
                @csrf
                <table class="member-list b-last-bottom">
                <colgroup>
                    <col width="2%">
                    <col width="4%">
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" id="check_all">
                        <label for="check_all"></label>
                    </th>
                    <th>No</th>
                    <th>이름</th>
                    <th>생년월일</th>
                    <th>입사일</th>
                    <th>퇴사일자</th>
                    <th>국민연금 가입</th>
                    <th>국민연금<br>보수월액</th>
                    <th>건강보험 가입</th>
                    <th>건강보험<br>보수월액</th>
                    <th>장기요양 경감</th>
                    <th>고용보험 가입</th>
                    <th>고용보험<br>보수월액</th>
                    <th>고용보험<br>65세 이후</th>
                    <th>산재보험 가입</th>
                    <th>산재보험<br>보수월액</th>
                    <th>비율계산적용</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($helpers as $i => $helper)
                    <tr>
                        <td>
                            <input type="hidden" name="target_key[{{$helper->id}}]" value="{{ $helper->target_key }}">
                            <input type="checkbox" name="check[]" id="check_{{$helper->id}}" value="{{ $helper->id }}" class="user_checked">
                            <label for="check_{{$helper->id}}"></label>
                        </td>
                        <td>
                            {{$i+1}}
                        </td>
                        <td>{{ $helper->name }}</td>
                        <td>{{ \App\Classes\Custom::rsno_to_birth($helper->birth) }}</td>
                        <td>{{ date("Y-m-d", strtotime($helper->contract_start_date)) }}</td>
                        <td>{{ \App\Classes\Custom::dateNullExcept($helper->contract_end_date) }}</td>
                        <td>
                            <select name="national_ins_check[{{$helper->id}}]" id="national_ins_check_{{$helper->id}}">
                                <option value="미가입" {{ $helper->national_ins_check == "미가입" ? "selected" : "" }}>미가입</option>
                                <option value="가입" {{ $helper->national_ins_check == "가입" ? "selected" : "" }}>가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="national_ins_bosu_price[{{$helper->id}}]" value="{{ $helper->national_ins_bosu_price ?? "" }}">
                        </td>
                        <td>
                            <select name="health_ins_check[{{$helper->id}}]" id="health_ins_check_{{$i}}">
                                <option value="미가입" {{ $helper->health_ins_check == "미가입" ? "selected" : "" }}>미가입</option>
                                <option value="가입" {{ $helper->health_ins_check == "가입" ? "selected" : "" }}>가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="health_ins_bosu_price[{{$helper->id}}]" value="{{ $helper->health_ins_bosu_price ?? "" }}">
                        </td>
                        <td>
                            <input type="checkbox" name="long_rest_subtract[{{$helper->id}}]" id="long_rest_subtract_{{$i}}" value="1"
                                    {{ \App\Classes\Input::checked($helper->long_rest_subtract ?? null, 1) }}>
                            <label for="long_rest_subtract_{{$i}}"></label>
                        </td>
                        <td>
                            <select name="employ_ins_check[{{$helper->id}}]" id="employ_ins_check_{{$i}}">
                                <option value="미가입" {{ $helper->employ_ins_check == "미가입" ? "selected" : "" }}>미가입</option>
                                <option value="가입" {{ $helper->employ_ins_check == "가입" ? "selected" : "" }}>가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="employ_ins_bosu_price[{{$helper->id}}]" value="{{ $helper->employ_ins_bosu_price }}">
                        </td>
                        <td>
                            <input type="checkbox" name="employ_65age_after[{{$helper->id}}]" id="employ_65age_after{{$i}}" value="1"
                                    {{ \App\Classes\Input::checked($helper->employ_65age_after ?? null, 1) }}
                            >
                            <label for="employ_65age_after{{$i}}"></label>
                        </td>
                        <td>
                            <select name="industry_ins_check[{{$helper->id}}]" id="industry_ins_check{{$i}}">
                                <option value="미가입" {{ $helper->industry_ins_check == "미가입" ? "selected" : "" }}>미가입</option>
                                <option value="가입" {{ $helper->industry_ins_check == "가입" ? "selected" : "" }}>가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="industry_ins_bosu_price[{{$helper->id}}]" value=" {{ $helper->industry_ins_bosu_price }}">
                        </td>
                        <td>
                            <input type="checkbox" name="percentage_apply[{{$helper->id}}]" value="1" id="percentage_apply_{{$helper->id}}" class="{{ $helper->percentage_apply ?? 23 }}"
                            {{ \App\Classes\Input::checked($helper->percentage_apply ?? null, 1) }}
                            >
                            <label for="percentage_apply_{{$helper->id}}"></label>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </form>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">

        </article> <!-- article list_bottom end -->

    </section>

    <script>
        document.getElementById("btnSubmit").onclick = function () {
            if ($(".user_checked:checked").length == 0) {
                alert("수정할 활동지원사를 선택해주세요.");
                return false;
            }

            document.insuranceEditForm.submit();
        };

        document.getElementById("check_all").onclick = function (e) {
            var checked = e.target.checked;
            var checkbox = document.querySelectorAll(".user_checked");

            Array.prototype.forEach.call(checkbox, function(i, v){
                i.checked = checked;
            });
        };
    </script>

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
