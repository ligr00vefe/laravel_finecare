@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 회원관리
@endsection

<?php

$qstr = $_SERVER['QUERY_STRING'];
$cur_page = 0;

if ($qstr != "") {
    $cur_page = $_GET['page'] ?? 1;
    unset($_GET['page']);
    $qstr = "";
    foreach ($_GET as $key=>$val) {
        if ($qstr != "") $qstr .= "&";
        $qstr .= "{$key}={$val}";
    }
}


use App\Classes\User;
?>

@section("content")


    <section id="userManagement" class="wrapper">

        <div class="head over-hidden">
            <h3 class="dis-ib">회원관리</h3>
            <div class="float-right">
                <button type="button" class="btn-excel btn-01">
                    <img src="{{ __IMG__ }}/excel_download_btn.png" alt="엑셀 다운로드"> 다운로드
                </button>
                <button type="button" class="btn-top-gray btn-01"
                    onclick="DELETE_MULTI()"
                >선택삭제</button>
                {{--<button type="button" class="btn-top-asphalt btn-01">회원추가</button>--}}
                <a class="btn-top-asphalt btn-01" href="/admin/user/register">회원추가</a>
            </div>
        </div>

        <div class="contents">
            <div class="contents-head over-hidden m-bottom-10">
                <p class="p_10 fs-14 dis-ib">
                    총 회원 수 <span class="acc-orange">{{ $paging }}</span>명
                </p>
                <div class="search-form float-right">
                    <form action="">
                        <select name="filter" id="filter">
                            <option value="account_id">아이디</option>
                        </select>
                        <input type="text" name="keyword">
                        <button type="submit">
                            <img src="{{__IMG__}}/icon_search_gray.png" alt="검색하기">
                        </button>
                    </form>
                </div>
            </div> {{-- contents-head end --}}

            <table>
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="all_chk" id="all_chk">
                        <label for="all_chk"></label>
                    </th>
                    <th>
                        <b>아이디</b>
                    </th>
                    <th>업체명</th>
                    <th>대표전화</th>
                    <th>유효기간</th>
                    <th>상태</th>
                    <th>등록일</th>
                    <th>비고</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lists as $list)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{ $list->id }}" id="check_{{ $list->id }}"
                                   value="{{ $list->id }}">
                            <label for="check_{{ $list->id }}"></label>
                        </td>
                        <td>
                            {{ $list->account_id }}
                        </td>
                        <td>
                            {{ $list->company_name ?? "" }}
                        </td>
                        <td>
                            {{ $list->tel ?? "" }}
                        </td>
                        <td>
                            {{ User::get_expiration_date($list->id) }}
                        </td>
                        <td>
                            {{ $list->status ?? "" }}
                        </td>
                        <td>
                            {{ $list->regdate ?? "" }}
                        </td>
                        <td>
                            <a href="{{ route("admin.user.edit", [ "id" => $list->id ]) }}" class="anchor_btn btn-modify">수정</a>
                            <a href="#" onclick="POP_EXTEND({{$list->id}}, '{{ $list->company_name }}')" data-company-name="{{ $list->company_name }}" class="anchor_btn btn-modify">연장</a>
                            <a href="#" onclick="USER_DELETE({{ $list->id }}, 1)" class="anchor_btn btn-delete">삭제</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            데이터가 없습니다
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <article id="list_bottom">
            {!! pagination2(10, ceil($paging/15)) !!}
        </article>

    </section>



    <div class="modal-background"></div>
    <div class="modal">
        <div class="modal-head m-bottom-20">
            <div class="over-hidden">
                <div class="float-left">
                    <h3>유효기간 연장</h3>
                </div>
                <div class="float-right">
                    <button type="button" onclick="modalclose()">
                        <img src="{{__IMG__}}/icon_close.png" alt="닫기">
                    </button>
                </div>
            </div>
            <span>관리자가 직접 기간을 연장할 수 있습니다</span>
        </div>
        <div class="modal-contents">
            
            <div class="contents-table">
                <form action="/admin/user/goods" method="post">
                    @csrf
                    <input type="hidden" name="id" >
                    <table>
                        <tr>
                            <th>업체명</th>
                            <td class="company-name"></td>
                        </tr>
                        <tr>
                            <th>결제상품</th>
                            <td>
                                <select name="goods_id" id="goods">
                                    <option value="">선택하세요</option>
                                    @foreach ($goods as $good)
                                        <option value="{{ $good->id }}">{{ $good->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>결제방법</th>
                            <th>
                                <input type="text" name="payment_type">
                            </th>
                        </tr>
                        <tr>
                            <th>결제일자</th>
                            <th>
                                <input type="text" name="payment_date" class="payment_date" readonly>
                            </th>
                        </tr>
                    </table>

                    <div class="btn-wrap">
                        <button class="btn-black">확인</button>
                    </div>

                </form>
            </div>
            
        </div>
    </div>

    <style>

        .modal-background {
            top: 0;
            left: 0;
            position:absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,.5);
            z-index: 90;
            display: none;
        }

        .modal {
            width: 420px;
            border: 2px solid black;
            padding: 30px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            background-color: #ffffff;
            display: none;
        }

        .modal .modal-head h3 {
            font-size: 16px;
            color: #363636;
        }

        .modal .modal-head span {
            color: #959595;
            font-size: 14px;
        }

        .modal .modal-head button {
            background-color: transparent;
            border: none;
        }

        .modal .modal-contents table {
            border-top: 2px solid;
        }

        .modal .modal-contents table tr th {
            text-align: left;
            width: 35%;
        }

        .modal .modal-contents table tr td,
        .modal .modal-contents table tr th {
            border-bottom: 1px solid #b7b7b7;
            height: 50px;
        }

        .modal .modal-contents table tr td select {
            height: 35px;
        }
    </style>




    <script>




        document.addEventListener("readystatechange", function() {
            $("#admin_nav ul.ul_1depth > li:nth-child(2) > a").trigger("click");
            $("#admin_nav ul.ul_1depth  li  a").removeClass("on");
            $("#admin_nav ul.ul_1depth > li:nth-child(2) > ul.ul_2depth > li:first-child > a").addClass("on");
        });

        // 삭제
        function USER_DELETE(id, _confirm)
        {
            if (_confirm == 1) {
                if (!confirm("해당 회원을 삭제하시겠습니까?")) {
                    alert("취소하셨습니다.");
                    return false;
                }
            }

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'delete',
                dataType: 'html',
                url: '/admin/user/'+id,
                data: {
                    id: id
                },
                success: function(data) {
                    var _data = JSON.parse(data);
                    if (_confirm == 1) {
                        if (_data.code == 1) {
                            alert("성공적으로 삭제했습니다.");
                            location.reload();
                        } else {
                            alert("삭제에 실패했습니다. 다시 시도해 주세요.");
                        }
                    }
                }
            })
        }

        // 선택삭제
        function DELETE_MULTI()
        {
            if (!confirm("선택한 회원을 모두 삭제하시겠습니까? ")) {
                alert("취소하셨습니다.");
                return false;
            }

            var id = $("input[name^='check_']:checked");

            $.each(id, function(i, dom) {
                var user_id = $(dom).val();
                USER_DELETE(user_id, 2);
            });

            alert("삭제되었습니다");
            location.reload();
        }

        // 체크
        $("#all_chk").on("change", function() {
            var target = $(this);
            if (target.is(":checked")) {
                $("input[name^=check_]").prop("checked", true);
            } else {
                $("input[name^=check_]").prop("checked", false);
            }
        });

        // 기간 연장
        function POP_EXTEND(id, company_name)
        {
            event.preventDefault();
            $(".modal").show();
            $(".modal-background").show();
            $(".modal .company-name").text(company_name)
            $(".modal input[name='id']").val(id)
        }

        function modalclose()
        {
            $(".modal").hide();
            $(".modal-background").hide();
        }


        $(".payment_date").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm-dd",
            clearButton: false,
            autoClose: true,
        });



    </script>

@endsection