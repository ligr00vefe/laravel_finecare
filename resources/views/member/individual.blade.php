@extends("layouts/layout")

@section("title")
    이용자 개별등록
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("member.side_nav")


    @if (session()->get("addErr"))
        <script>
            alert("{{session()->get("addErr")}}");
        </script>
    @endif

    <section id="member_register">

        <div class="head-info">
            <h1>이용자 개별등록</h1>
        </div>

        <div class="input-wrap">

            <form action="/member/register" method="post" name="register_form" onSubmit="return formValidation(this)">
                @csrf
                <table id="register-table">
                    <tbody>
                    <tr>
                        <th colspan="4" style="border-top: none;">
                            <h3>
                                이용자 기본정보
                            </h3>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <label for="name" class="required_mark">이름</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="name" id="name" required>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="rsdnt_number_1" class="required_mark">주민등록번호</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="rsdnt_number_1" id="rsdnt_number_1" class="input-middle" placeholder="(필수) 6자리" required maxlength="6"> - <input type="text" name="rsdnt_number_2" id="rsdnt_number_2" class="input-middle" placeholder="(선택) 7자리">
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
                        <th>
                            <label for="target_id">대상자ID</label>
                        </th>
                        <td>
                            <input type="text" name="target_id">
                        </td>
                        <th>
                            <label for="business_type">사업유형</label>
                        </th>
                        <td>
                            <input type="text" name="business_type" id="business_type">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="gradename">등급명</label>
                        </th>
                        <td>
                            <input type="text" name="grade" id="grade">
                        </td>
                        <th>
                            <label for="sigungu_name">대상자시군구명</label>
                        </th>
                        <td>
                            <input type="text" name="sigungu_name" id="sigungu_name">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="service_sigungu_name">서비스제공시군구명</label>
                        </th>
                        <td>
                            <input type="text" name="service_sigungu_name" id="service_sigungu_name">
                        </td>
                        <th>
                            <label for="tel">연락처</label>
                        </th>
                        <td>
                            <input type="text" name="tel" id="tel">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="phone">휴대전화</label>
                        </th>
                        <td>
                            <input type="text" name="phone" id="phone">
                        </td>
                        <th>
                            <label for="contract_status">계약상태</label>
                        </th>
                        <td>
                            <input type="text" name="contract_status" id="contract_status">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="contract_start_date">계약시작일</label>
                        </th>
                        <td>
                            <input type="text" name="contract_start_date" id="contract_start_date">
                        </td>
                        <th>
                            <label for="contract_end_date">계약종료일</label>
                        </th>
                        <td>
                            <input type="text" name="contract_end_date" id="contract_end_date">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="contract_resign_reason">계약해지사유</label>
                        </th>
                        <td>
                            <input type="text" name="contract_resign_reason" id="contract_resign_reason">
                        </td>
                        <th>
                            <label for="service_status">서비스상태</label>
                        </th>
                        <td>
                            <input type="text" name="service_status" id="service_status">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="service_start_date">서비스시작일자</label>
                        </th>
                        <td>
                            <input type="text" name="service_start_date" id="service_start_date">
                        </td>
                        <th>
                            <label for="service_end_date">서비스종료일자</label>
                        </th>
                        <td>
                            <input type="text" name="service_end_date" id="service_end_date">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="service_resign_date">서비스해지일자</label>
                        </th>
                        <td>
                            <input type="text" name="service_resign_date" id="service_resign_date">
                        </td>
                        <th>
                            <label for="target_helper">지정제공인력</label>
                        </th>
                        <td>
                            <input type="text" name="target_helper" id="target_helper">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="help_price_total">지원금합계</label>
                        </th>
                        <td>
                            <input type="text" name="help_price_total" id="help_price_total">
                        </td>
                        <th>
                            <label for="government_help_price">정부지원금</label>
                        </th>
                        <td>
                            <input type="text" name="government_help_price" id="government_help_price">
                        </td>
                    </tr>



                    <tr>
                        <th>
                            <label for="deductible">본인부담금</label>
                        </th>
                        <td>
                            <input type="text" name="deductible" id="deductible">
                        </td>
                        <th>
                            <label for="childbirth_date">자녀출산일자</label>
                        </th>
                        <td>
                            <input type="text" name="childbirth_date" id="childbirth_date">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="leave_hospital_date">퇴원일자</label>
                        </th>
                        <td>
                            <input type="text" name="leave_hospital_date" id="leave_hospital_date">
                        </td>
                        <th>
                            <label for="zip_code">제공자우편번호</label>
                        </th>
                        <td>
                            <input type="text" name="zip_code" id="zip_code">
                        </td>
                    </tr>


                    <tr>
                        <th>
                            <label for="service_address">서비스제공지</label>
                        </th>
                        <td>
                            <input type="text" name="service_address" id="service_address">
                        </td>
                        <th>
                            <label for="address">대상자주소</label>
                        </th>
                        <td>
                            <input type="text" name="address" id="address">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="address_detail">대상자상세주소</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="address_detail" id="address_detail">
                        </td>
                    </tr>

                    <tr>
                        <th colspan="4" style="border-top: none;">
                            <h3>
                                이용자 추가정보
                            </h3>
                        </th>
                    </tr>

                    <tr>
                        <th>
                            <label for="regdate">접수일</label>
                        </th>
                        <td>
                            <input type="text" name="regdate" id="regdate">
                        </td>
                        <th>
                            <label for="email">이메일</label>
                        </th>
                        <td>
                            <input type="text" name="email" id="email">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="company">직장이름</label>
                        </th>
                        <td>
                            <input type="text" name="company" id="company">
                        </td>
                        <th>
                            <label for="bogun_time">보건복지부 판정시간</label>
                        </th>
                        <td>
                            <input type="text" name="bogun_time" id="bogun_time">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="jijache_time">지자체 추가 판정시간</label>
                        </th>
                        <td>
                            <input type="text" name="jijache_time" id="jijache_time">
                        </td>
                        <th>
                            <label for="etc_time">기타 판정시간</label>
                        </th>
                        <td>
                            <input type="text" name="etc_time" id="etc_time">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="other_experience">타기관 이용경험</label>
                        </th>
                        <td>
                            <input type="text" name="other_experience" id="other_experience">
                        </td>
                        <th>
                            <label for="income_check">수급여부</label>
                        </th>
                        <td>
                            <input type="text" name="income_check" id="income_check">
                        </td>
                    </tr>


                    <tr>
                        <th>
                            <label for="activity_grade">활동지원등급(신규)</label>
                        </th>
                        <td>
                            <input type="text" name="activity_grade" id="activity_grade">
                        </td>
                        <th>
                            <label for="activity_grade_old">활동지원등급(기존)</label>
                        </th>
                        <td>
                            <input type="text" name="activity_grade_old" id="activity_grade_old">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="activity_grade_type">활동지원등급유형</label>
                        </th>
                        <td>
                            <input type="text" name="activity_grade_type" id="activity_grade_type">
                        </td>
                        <th>
                            <label for="income_decision_date">수급결정시기</label>
                        </th>
                        <td>
                            <input type="text" name="income_decision_date" id="income_decision_date">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="self_charge_price">본인부담금</label>
                        </th>
                        <td>
                            <input type="text" name="self_charge_price" id="self_charge_price">
                        </td>
                        <th>
                            <label for="main_disable_name">주장애명</label>
                        </th>
                        <td>
                            <input type="text" name="main_disable_name" id="main_disable_name">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="main_disable_level">주장애정도</label>
                        </th>
                        <td>
                            <input type="text" name="main_disable_level" id="main_disable_level">
                        </td>
                        <th>
                            <label for="main_disable_grade">주장애등급</label>
                        </th>
                        <td>
                            <input type="text" name="main_disable_grade" id="main_disable_grade">
                        </td>
                    </tr>


                    <tr>
                        <th>
                            <label for="sub_disable_name">부장애명</label>
                        </th>
                        <td>
                            <input type="text" name="sub_disable_name" id="sub_disable_name">
                        </td>
                        <th>
                            <label for="sub_disable_level">부장애정도</label>
                        </th>
                        <td>
                            <input type="text" name="sub_disable_level" id="sub_disable_level">
                        </td>
                    </tr>


                    <tr>
                        <th>
                            <label for="sub_disable_grade">부장애등급</label>
                        </th>
                        <td>
                            <input type="text" name="sub_disable_grade" id="sub_disable_grade">
                        </td>
                        <th>
                            <label for="disease_name">보유질환명</label>
                        </th>
                        <td>
                            <input type="text" name="disease_name" id="disease_name">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="drug_info">투약정보</label>
                        </th>
                        <td>
                            <input type="text" name="drug_info" id="drug_info">
                        </td>
                        <th>
                            <label for="wasang_check">와상장애여부</label>
                        </th>
                        <td>
                            <input type="text" name="wasang_check" id="wasang_check">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="marriage_check">결혼여부</label>
                        </th>
                        <td>
                            <input type="text" name="marriage_check" id="marriage_check">
                        </td>
                        <th>
                            <label for="family_info">가족사항</label>
                        </th>
                        <td>
                            <input type="text" name="family_info" id="family_info">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="protector_name">보호자명</label>
                        </th>
                        <td>
                            <input type="text" name="protector_name" id="protector_name">
                        </td>
                        <th>
                            <label for="protector_relation">보호자관계</label>
                        </th>
                        <td>
                            <input type="text" name="protector_relation" id="protector_relation">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="protector_phone">보호자휴대전화번호</label>
                        </th>
                        <td>
                            <input type="text" name="protector_phone" id="protector_phone">
                        </td>
                        <th>
                            <label for="protector_tel">보호자자택전화번호</label>
                        </th>
                        <td>
                            <input type="text" name="protector_tel" id="protector_tel">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="protector_address">보호자주소</label>
                        </th>
                        <td>
                            <input type="text" name="protector_address" id="protector_address">
                        </td>
                        <th>
                            <label for="etc">특이사항</label>
                        </th>
                        <td>
                            <input type="text" name="etc" id="etc">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="comment">종합소견</label>
                        </th>
                        <td colspan="3" style="border-bottom: 1px solid #b7b7b7;">
                            <input type="text" name="comment" id="comment">
                        </td>
                    </tr>

                    </tbody>
                </table>

                <div class="brn-wrap" style="text-align: center; margin: 15px 0;">
                    <button style="background-color: #EB8626; border: none; padding: 5px 15px; color: #ececec; font-size: 16px;">생성</button>
                </div>

            </form>

        </div> <!-- input-wrap end -->

    </section>


    <script>

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#regdate", "#regEndDate", "#contract_start_date", "#contract_end_date",
            "#service_start_date", "#service_end_date", "#service_resign_date", "",
            "#childbirth_date", "",
            "#leave_hospital_date", "",


        ];

        var onlytimepicker = [
             "#school_start_time", "#school_end_time", "#short_term_care_center_start_time", "#short_term_care_center_end_time"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm-dd",
                clearButton: false,
                autoClose: true,
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소날짜 설정해주기
                    if (idx%2 == 0) {

                        if (datepicker_selector[idx+1] == "") return;

                        $(datepicker_selector[idx+1]).datepicker({
                            minDate: new Date(dateText),
                            dateFormat:"yyyy-mm-dd",
                            clearButton: false,
                            autoClose: true,
                        })
                    }

                }
            });

        });

        $.each(onlytimepicker, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                timepicker: true,
                onlyTimepicker: true,
                timeFormat: "hh:ii",
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소시간 설정해주기
                    if (idx%2 == 0) {
                        $(onlytimepicker[idx+1]).datepicker({
                            minHours: dateText
                        })
                    }

                }
            });

        });

        $(".sub-menu__list ul li.on").removeClass("on");
        $(".sub-menu__list ul li a[href='/member/list']").parent("li").addClass("on");

        function formValidation(f)
        {
            // 주민번호
            f.rsNo.value = f.rsdnt_number_1.value + "-" + f.rsdnt_number_2.value;
            return true;
        }


    </script>

@endsection
