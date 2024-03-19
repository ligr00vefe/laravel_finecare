@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 회원등록
@endsection


<?php
$id = $id ?? null;
$level = $user->level ?? 0;
$license = $user->license ?? "";
$email = $user->email ?? "";
$address = $user->address ?? "";
?>


@section("content")

    <section id="userCreate" class="wrapper">

        <div class="head">
            <h3 class="dis-ib">회원등록</h3>
        </div>

        <div class="contents">

            <div class="register-form-wrapper">
                <form method="POST" action="{{ $id ? "/admin/user/{$id}" : "/admin/user" }}" onSubmit="return VALIDATION(this)">
                    @if($id)
                        @method("PUT")
                        <input type="hidden" name="id" value="{{ $id }}">
                    @endif
                    @csrf
                    <table>
                        <tr>
                            <th>
                                <label for="account_id" class="required_mark">아이디</label>
                            </th>
                            <td>
                                <input type="text" class="ad-input-middle" name="account_id" id="account_id"
                                       pattern="^[A-Za-z0-9+]{4,12}$" required
                                       value="{{ $user->account_id ?? "" }}"
                                       {{ $id ? "readonly" : "" }}
                                >
                                <p class="help">영문 대,소문자, 숫자로 이루어진 4~12글자의 아이디를 입력해주세요</p>

                            </td>
                            <th>
                                <label for="ip">아이피(IP)</label>
                            </th>
                            <td>
                                <input type="text" class="ad-input-middle" name="ip" id="ip" value="{{ $user->ip ?? "" }}">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="password" class="required_mark">비밀번호</label>
                            </th>
                            <td>
                                <input type="password" name="password" id="password" class="ad-input-middle" pattern="^.{6,20}$"
                                {{ $id ? "" : "required" }}
                                >
                                <p class="help">최소 6글자 이상 입력해주세요.</p>
                            </td>
                            <th>
                                <label for="password2" class="required_mark">비밀번호 확인</label>
                            </th>
                            <td>
                                <input type="password" name="password2" id="password2" class="ad-input-middle"
                                {{ $id ? "" : "required" }}
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="company_name" class="required_mark">업체명</label>
                            </th>
                            <td>
                                <input type="text" id="company_name" name="company_name" class="ad-input-large"
                                    value="{{ $user->company_name ?? "" }}"
                                >
                            </td>
                            <th>
                                <label for="level" class="required_mark">회원권한</label>
                            </th>
                            <td>
                                <select name="level" id="level" required>
                                    <option value="1" {{ $level == 1 ? "selected" : "" }}>미승인회원(1)</option>
                                    <option value="2" {{ $level == 2 ? "selected" : "" }}>정회원(2)</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="tel">대표전화</label>
                            </th>
                            <td>
                                <input type="text" id="tel" name="tel" class="ad-input-large" value="{{ $user->tel ?? "" }}">
                            </td>
                            <th>
                                <label for="fax">팩스번호</label>
                            </th>
                            <td>
                                <input type="text" id="fax" name="fax" class="ad-input-large" value="{{ $user->fax ?? "" }}">
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="name" class="required_mark">관리자명</label>
                            </th>
                            <td>
                                <input type="text" id="name" name="name" class="ad-input-middle" required
                                    value="{{ $user->name ?? "" }}"
                                >
                            </td>
                            <th>
                                <label for="phone">관리자 연락처</label>
                            </th>
                            <td>
                                <input type="text" id="phone" name="phone" class="ad-input-large"
                                    value="{{ $user->phone ?? "" }}"
                                >
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="license1">사업자등록번호</label>
                            </th>
                            <td>
                                <input type="hidden" name="license" value="{{ $license }}">
                                <input type="text" id="license1" name="license1" class="license"
                                    value="{{ $license
                                     ? substr($license, 0, 3)
                                     : ""
                                     }}"
                                >
                                -
                                <input type="text" id="license2" name="license2" class="license"
                                    value="{{$license
                                    ? substr($license, 3, 2)
                                    : ""
                                    }}"
                                >
                                -
                                <input type="text" id="license3" name="license3" class="license"
                                    value="{{ $license
                                    ? substr($license, 5)
                                    : ""
                                    }}"
                                >
                            </td>
                            <th>
                                <label for="email1" class="required_mark">이메일</label>
                            </th>
                            <td>
                                <input type="hidden" id="email" name="email" class="email"
                                    value="{{ $email }}"
                                >
                                <input type="text" id="email1" name="email1" required class="email"
                                    value="{{ $email
                                    ? explode("@", $email)[0]
                                    : ""
                                    }}"
                                >
                                @
                                <input type="text" id="email2" name="email2" required class="email"
                                    value="{{ $email
                                    ? explode("@", $email)[1]
                                    : ""
                                    }}"
                                >
                                <select name="email_selector" id="email_selector" class="email"
                                    onchange="EMAIL_SELECTOR_CHANGER(this.value)"
                                >
                                    <option value="">직접입력</option>
                                    <option value="naver.com">naver.com</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>주소</th>
                            <td class="height_auto address-wrap">
                                <div>
                                    <input type="hidden" name="address" value="{{ $address }}">
                                    <input type="text" name="addr1" id="addr1" class="ad-input-small"
                                        value="{{ $address ? explode("||", $address)[0] : "" }}"
                                    >
                                    <a href="#" class="ad-addr-search">주소 검색</a>
                                </div>
                                <div>
                                    <input type="text" name="addr2" id="addr2" class="ad-input-addr"
                                        value="{{ $address ? explode("||", $address)[1] : "" }}"
                                    >
                                </div>
                                <div>
                                    <input type="text" name="addr3" id="addr3" class="ad-input-addr"
                                        value="{{ $address ? explode("||", $address)[2] : "" }}"
                                    >
                                </div>
                            </td>
                        </tr>
                        
                        <tr>
                            <th>
                                <label for="memo">메모</label>
                            </th>
                            <td colspan="3" class="height_auto">
                                <textarea name="memo" id="memo" cols="30" rows="10" class="ad-memo">{{ $user->memo ?? "" }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="resign_date">탈퇴일자</label>
                            </th>
                            <td colspan="3">
                                <input type="text" name="resign_date" id="resign_date" class="ad-input-middle"
                                    placeholder="20xx-xx-xx"
                                   value="{{ $user->resign_date ?? "" }}"
                                >
                                <input type="checkbox" name="resign_date_now" id="resign_date_now">
                                <label for="resign_date_now">탈퇴일을 오늘로 지정</label>
                            </td>
                        </tr>

                    </table>
                    <div class="btn-wrap">
                        <button type="button" class="ad-btn-go-list"
                        onclick="route('{{ route("admin.user") }}') " >
                            목록</button>
                        <button type="submit" class="ad-btn-submit">확인</button>
                    </div>
                </form>
            </div>

        </div>

    </section>


    <script>

        function VALIDATION(f)
        {

            var id = "{{ $id }}" != "" ? true : false;

            f.email.value = f.email1.value + "@" + f.email2.value;
            f.address.value = f.addr1.value +"||"+ f.addr2.value +"||"+ f.addr3.value;
            f.license.value = f.license1.value + f.license2.value + f.license3.value;

            if (f.account_id.value == "" || !f.account_id.value)
            {
                alert("아이디를 확인해주세요.");
                f.account_id.focus();
                return false;
            }

            if (f.email1.value == "" || !f.email1.value)
            {
                alert("이메일을 확인해주세요.");
                f.email1.focus();
                return false;
            }

            if (f.email2.value == "" || !f.email2.value)
            {
                alert("이메일을 확인해주세요.");
                f.email2.focus();
                return false;
            }

            if (id && f.password.value != f.password2.value)
            {
                alert("비밀번호가 일치하지 않습니다.");
                f.password.focus();
                return false;
            }
            return true;
        }

        function EMAIL_SELECTOR_CHANGER(t)
        {
            $("#email2").val(t);
        }


        $("#resign_date_now").on("change", function() {

            if ($(this).is(":checked")) {
                var d = new Date();
                var date = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
                $("#resign_date").val(date).prop("readonly", true);
            } else {
                $("#resign_date").val("").prop("readonly", false);
            }

        });

        document.addEventListener("readystatechange", function() {
            $("#admin_nav ul.ul_1depth > li:nth-child(2) > a").trigger("click");
            $("#admin_nav ul.ul_1depth  li  a").removeClass("on");
            $("#admin_nav ul.ul_1depth > li:nth-child(2) > ul.ul_2depth > li:nth-child(2) > a").addClass("on");
        });

    </script>

@endsection