@extends("layouts/layout")

@section("title")
    활동지원사 계획표
@endsection

<?php
use App\Classes\Custom;
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">

    @include("worker.side_nav")
    <section id="member_wrap" class="list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>활동지원사 계획표</h1>
                <div class="action-wrap">

                </div>
            </div>


        </article> <!-- article list_head end -->

        <article id="list_contents">
            <h3>
                대상연월 {{ $_GET['target_ym'] }}
            </h3>
            <table class="member-list in-input">
                <thead>
                <tr>
                    <th colspan="3">인적사항</th>
                    <th colspan="{{ $last_day }}">근로일수</th>
                </tr>
                <tr>
                    <th>No</th>
                    <th class="tt01">이름</th>
                    <th class="tt01">생년월일</th>
                    @foreach (range(1, $last_day) as $i)
                        <th>{{ $i }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @forelse ($lists as $key => $list)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <p>{{ $list->worker_id }}</p>
                        </td>
                        <td>
                            <p>{{ Custom::regexOnlyNumber($list->worker_id) }}</p>
                        </td>
                        @foreach (range(1, $last_day) as $i)
                            <td>
                                <input type="checkbox" id="checked_{{$i}}" disabled {{ in_array($i, $list->dates_d) ? "checked" : "" }}>
                                <label for="checked_{{$i}}"></label>
                            </td>
                        @endforeach

                    </tr>
                @empty
                    <tr>
                        <td colspan="7"></td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <div class="btn-wrap" style="">
            <form action="/worker/timetable/upload" style="display:inline-block" method="post">
                @csrf
                @method("delete")
                <input type="hidden" name="target_ym" value="{{ $ym }}">
                <button>재업로드</button>
            </form>
            <a href="/worker/service/offer?calendar_type=activity_time&from_date={{ $ym }}">확정하기</a>
        </div>

    </section>

    <style>

        #member_wrap #list_contents {
            max-height: 800px;
            overflow-y: auto;
        }

        table {
            border-bottom: 1px solid #b7b7b7;
        }

        table tr th {
            width: 40px !important;
        }

        table tr th,
        table tr td {
            border: 1px solid #b7b7b7;
        }

        .list_wrapper input[type="checkbox"] + label {
            padding-left: 20px !important;
        }

        table .tt01 {
            min-width: 100px !important;
        }

        .btn-wrap {
            float: right;
            overflow: hidden;
            margin-top: 30px;
        }

        .btn-wrap button {
            background-color: #ffffff;
            color: #eb8626;
            border: 1px solid #eb8626;
            width: 90px;
            padding: 6px 0;
        }

        .btn-wrap a {
            display: inline-block;
            background-color: #0c0c0c;
            color: #ffffff;
            border: none;
            width: 90px;
            padding: 6px 0;
            font-size: 13.33px;
        }

    </style>


    <script>
        $("input[name='from_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function(dateText, inst) {
                $("input[name='to_date']").datepicker({
                    minDate: new Date(dateText),
                    dateFormat:"yyyy-mm",
                    clearButton: false,
                    autoClose: true,
                })
            }
        });


        $("input[name='to_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
            onSelect: function(dateText, inst) {

            }
        })
    </script>
@endsection
