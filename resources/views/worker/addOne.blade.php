@extends("layouts/layout")

@section("title")
    활동지원사 - 활동지원사 개별등록
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("worker.side_nav")

    @if (session()->get("addErr"))
        <script>
            alert("{{session()->get("addErr")}}");
        </script>
    @endif

    <section id="member_register">

        <div class="head-info">
            <h1>활동지원사 개별등록</h1>
        </div>

        <div class="input-wrap">

            <form action="/worker/register" method="post" name="register_form" onSubmit="return formValidation(this)">
                @csrf
                <table id="register-table">
                    <tbody>
                        <tr>
                            <th colspan="4" style="border-top: none;">
                                <h3>
                                    기본양식
                                </h3>
                            </th>
                        </tr>

                        <tr>
                            <th><label for="name">제공인력명</label></th>
                            <td><input type="text" name="name" id="name" value=""></td>

                            <th><label for="register_check">주민등록번호(필수)</label></th>
                            <td>
                                <input type="text" name="rsdnt_number_1" id="rsdnt_number_1" class="input-middle" maxlength="6" placeholder="(필수) 6자리"  value="" required> - <input type="text" name="rsdnt_number_2" id="rsdnt_number_2" class="input-middle" placeholder="(선택) 7자리" value="" maxlength="7">
                                <input type="hidden" name="rsNo">
                            </td>
                        </tr>
                        <script>
                            $("#rsdnt_number_1").on("keyup", function () {
                                if ($(this).val().length >= 6) {
                                    $("#rsdnt_number_2").focus();
                                }
                            })
                        </script>

                        <tr>
                            <th><label for="target_id">제공인력ID</label></th>
                            <td><input type="text" name="target_id" id="target_id" value=""></td>

                            <th><label for="target_payment_id">제공인력결제ID</label></th>
                            <td><input type="text" name="target_payment_id" id="target_payment_id" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="card_number">카드번호</label></th>
                            <td><input type="text" name="card_number" id="card_number" value=""></td>

                            <th><label for="agency_name">제공기관명</label></th>
                            <td><input type="text" name="agency_name" id="agency_name" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="business_number">사업자번호</label></th>
                            <td><input type="text" name="business_number" id="business_number" value=""></td>

                            <th><label for="sido">시도</label></th>
                            <td><input type="text" name="sido" id="sido" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="sigungu">시군구</label></th>
                            <td><input type="text" name="sigungu" id="sigungu" value=""></td>

                            <th><label for="business_division">사업구분</label></th>
                            <td><input type="text" name="business_division" id="business_division" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="business_types">사업유형</label></th>
                            <td><input type="text" name="business_types" id="business_types" value=""></td>

                            <th><label for="tel">제공인력연락처</label></th>
                            <td><input type="text" name="tel" id="tel" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="phone">제공인력핸드폰</label></th>
                            <td><input type="text" name="phone" id="phone" value=""></td>

                            <th><label for="address">제공인력주소</label></th>
                            <td><input type="text" name="address" id="address" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="etc">비고</label></th>
                            <td><input type="text" name="etc" id="etc" value=""></td>

                            <th><label for="contract">계약여부</label></th>
                            <td><input type="text" name="contract" id="contract" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="contract_start_date">계약일</label></th>
                            <td><input type="text" name="contract_start_date" id="contract_start_date" readonly autocomplete="off" value=""></td>

                            <th><label for="contract_end_date">종료일</label></th>
                            <td><input type="text" name="contract_end_date" id="contract_end_date" readonly autocomplete="off" value=""></td>
                        </tr>
                        <tr>
                            <th><label for="contract_date">계약기간</label></th>
                            <td colspan="3"><input type="text" name="contract_date" id="contract_date" value=""></td>
                        </tr>
                    </tbody>

                    <tbody>
                    <tr>
                        <th colspan="4" style="border-top: none;">
                            <h3 style="margin:50px 0 15px;">
                                추가양식1
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <th><label for="register_check">등록구분</label></th>
                        <td><input type="text" name="register_check" id="register_check" value=""></td>

                        <th><label for="business_division">사업구분</label></th>
                        <td><input type="text" name="business_division01" id="business_division01" value=""></td>

{{--                        <th><label for="name">제공인력명</label></th>--}}
{{--                        <td><input type="text" name="name01" id="name01" value=""></td>--}}
                    </tr>
                    <tr>
{{--                        <th><label for="birth">생년월일</label></th>--}}
{{--                        <td><input type="text" name="birth01" id="birth01" value=""></td>--}}


                    </tr>
                    <tr>
                        <th><label for="business_type">사업유형</label></th>
                        <td><input type="text" name="business_type" id="business_type" value=""></td>

                        <th><label for="payment_price">현재결제금액</label></th>
                        <td><input type="text" name="payment_price" id="payment_price" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="moment_payment_price">등록시점결제금액</label></th>
                        <td><input type="text" name="moment_payment_price" id="moment_payment_price" value=""></td>

                        <th><label for="work_time">실근무시간</label></th>
                        <td><input type="text" name="work_time" id="work_time" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="add_basic_pay">*기본급여(A)</label></th>
                        <td><input type="text" name="add_basic_pay" id="add_basic_pay" value=""></td>

                        <th><label for="add_week_pay">*주휴수당(B)</label></th>
                        <td><input type="text" name="add_week_pay" id="add_week_pay" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="add_year_pay">*연차수당(C)</label></th>
                        <td><input type="text" name="add_year_pay" id="add_year_pay" value=""></td>

                        <th><label for="etc_pay">*기타(D)</label></th>
                        <td><input type="text" name="etc_pay" id="etc_pay" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="ins_business_assign">*4대보험료 사업자 분담금</label></th>
                        <td><input type="text" name="ins_business_assign" id="ins_business_assign" value=""></td>

                        <th><label for="retire_plus_price">*퇴직충당금(D)</label></th>
                        <td><input type="text" name="retire_plus_price" id="retire_plus_price" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="monthly_payment">월급여(A+B+C+D)</label></th>
                        <td><input type="text" name="monthly_payment" id="monthly_payment" value=""></td>

                        <th><label for="work_time_day">*근무시간(일수)</label></th>
                        <td><input type="text" name="work_time_day" id="work_time_day" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="time_per_price">시간(일)단가</label></th>
                        <td><input type="text" name="time_per_price" id="time_per_price" value=""></td>

                        <th><label for="ins_check">4대보험미가입</label></th>
                        <td><input type="text" name="ins_check" id="ins_check" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="national_ins_check">국민연금</label></th>
                        <td><input type="text" name="national_ins_check" id="national_ins_check" value=""></td>

                        <th><label for="health_ins_check">건강보험</label></th>
                        <td><input type="text" name="health_ins_check" id="health_ins_check" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="employ_ins_check">고용보험</label></th>
                        <td><input type="text" name="employ_ins_check" id="employ_ins_check" value=""></td>

                        <th><label for="industry_ins_check">산재보험</label></th>
                        <td><input type="text" name="industry_ins_check" id="industry_ins_check" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="baesang_ins_check">배상보험</label></th>
                        <td><input type="text" name="baesang_ins_check" id="baesang_ins_check" value=""></td>

                        <th><label for="retire_added_check">퇴직적립</label></th>
                        <td><input type="text" name="retire_added_check" id="retire_added_check" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="qualification_status">자격상태</label></th>
                        <td colspan="3"><input type="text" name="qualification_status" id="qualification_status" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="business_division_code">사업구분코드</label></th>
                        <td><input type="text" name="business_division_code" id="business_division_code" value=""></td>

                        <th><label for="business_type_code">사업유형코드</label></th>
                        <td><input type="text" name="business_type_code" id="business_type_code" value=""></td>
                    </tr>
                    </tbody>

                    <tbody>
                    <tr>
                        <th colspan="4" style="border-top: none;">
                            <h3 style="margin:50px 0 15px;">
                                추가양식2
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <th><label for="regdate">접수일 (필수)</label></th>
                        <td colspan="3"><input type="text" name="regdate" id="regdate" value="" readonly autocomplete="off" required></td>
                    </tr>
                    <tr>
                        <th><label for="join_date">입사일</label></th>
                        <td><input type="text" name="join_date" id="join_date" value="" autocomplete="off" readonly></td>

                        <th><label for="resign_date">퇴사일</label></th>
                        <td><input type="text" name="resign_date" id="resign_date" value="" autocomplete="off" readonly></td>
                    </tr>
                    <tr>
                        <th><label for="phone">휴대전화</label></th>
                        <td><input type="text" name="phone02" id="phone02" value=""></td>

                        <th><label for="tel">유선전화</label></th>
                        <td><input type="text" name="tel02" id="tel02" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="address">주소</label></th>
                        <td><input type="text" name="address02" id="address02" value=""></td>

                        <th><label for="bank_name">*은행명</label></th>
                        <td><input type="text" name="bank_name" id="bank_name" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="bank_account_number">*급여계좌번호</label></th>
                        <td><input type="text" name="bank_account_number" id="bank_account_number" value=""></td>

                        <th><label for="depositary_stock">*예금주</label></th>
                        <td><input type="text" name="depositary_stock" id="depositary_stock" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="license_info">*활동보조인 자격정보</label></th>
                        <td><input type="text" name="license_info" id="license_info" value=""></td>

                        <th><label for="email">*이메일</label></th>
                        <td><input type="text" name="email" id="email" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="crime_check">범죄경력 조회여부</label></th>
                        <td><input type="text" name="crime_check" id="crime_check" value=""></td>

                        <th><label for="national_pension">국민연금 가입여부 (가입/미가입)</label></th>
                        <td><input type="text" name="national_pension" id="national_pension" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="national_pension_monthly">국민연금 보수월액</label></th>
                        <td><input type="text" name="national_pension_monthly" id="national_pension_monthly" value=""></td>

                        <th><label for="health_insurance">건강보험 가입여부 (가입/미가입)</label></th>
                        <td><input type="text" name="health_insurance" id="health_insurance" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="health_insurance_monthly">건강보험 보수월액</label></th>
                        <td><input type="text" name="health_insurance_monthly" id="health_insurance_monthly" value=""></td>

                        <th><label for="long_term_care_insurance_reduction">장기요양보험 경감여부</label></th>
                        <td><input type="text" name="long_term_care_insurance_reduction" id="long_term_care_insurance_reduction" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="employment_insurance">고용보험 가입여부 (가입/미가입)</label></th>
                        <td><input type="text" name="employment_insurance" id="employment_insurance" value=""></td>

                        <th><label for="employment_insurance_monthly">고용보험 보수월액</label></th>
                        <td><input type="text" name="employment_insurance_monthly" id="employment_insurance_monthly" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="employment_insurance_after_65age">고용보험 65세 이후 여부</label></th>
                        <td><input type="text" name="employment_insurance_after_65age" id="employment_insurance_after_65age" value=""></td>

                        <th><label for="industrial_accident_insurance">산재보험 가입여부 (가입/미가입)</label></th>
                        <td><input type="text" name="industrial_accident_insurance" id="industrial_accident_insurance" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="industrial_accident_insurance_monthly">산재보험 보수월액</label></th>
                        <td colspan="3"><input type="text" name="industrial_accident_insurance_monthly" id="industrial_accident_insurance_monthly" value=""></td>
                    </tr>
                    </tbody>

                  </table>{{-- #register-table end --}}

                <div class="btn-wrap m-top-20">
                    <button type="button" class="btn-gray">취소</button>
                    <button type="submit" class="btn-black">등록</button>
                </div>

            </form>

        </div> <!-- input-wrap end -->

    </section>


    <script>

        function formValidation(f)
        {
            // 주민번호
            f.rsNo.value = f.rsdnt_number_1.value + "-" + f.rsdnt_number_2.value;

            return true;
        }

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#regdate", "#regEndDate", "#join_date", "#resign_date",
            "#contract_start_date", "#contract_end_date",

        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm-dd",
                clearButton: false,
                autoClose: true,

            });

        });


        $(".sub-menu__list ul li.on").removeClass("on");
        $(".sub-menu__list ul li[data-uri='/worker']").addClass("on");


    </script>

@endsection
