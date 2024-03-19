
<div class="modal-wrapper">
    <div id="modal">
        <div class="modal-head">
            <p>
                <span class="provider_info">{{ $provider_key ?? "" }}</span>의 연차 사용현황
            </p>
            <button type="button" class="modal-close" id="modal_close">
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-search-form">
                <form action="" name="modalSearchForm">
                    <div class="search-year">
                        <p>년도검색</p>
                        <input type="hidden" name="provider_key" value=" {{ $provider_key ?? "" }}">
                        <input type="text" class="from_date" id="modal_year_search" autocomplete="off" name="year" readonly>
                        <label for="modal_year_search" class="from_date">
                            <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                        </label>
                        <button type="button" class="modal-action" data-type="search">
                            검색
                        </button>
                    </div>
                    <div class="off_add">
                        <p>연차사용일</p>
                        <input type="text" class="from_date" name="add" id="modal_off_add">
                        <label for="modal_off_add" class="from_date">
                            <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                        </label>
                        <button type="button" class="modal-action" data-type="add">
                            등록
                        </button>
                    </div>
                </form>


            </div>

            <div class="off-list">
                <table>
                    <thead>
                    <tr>
                        <th>
                            연차 사용일
                        </th>
                        <th>
                            비고
                        </th>
                    </tr>
                    </thead>

                    <tbody class="reload">
                        @include("recalculate.annual.reload.list")
                    </tbody>
                </table>

                <div class="pagination">
                    @include("recalculate.annual.reload.pagination")
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination ul {
        display: flex;
        justify-content: center;
    }

    .pagination ul li {
    }

    .pagination ul li a.on {
        background-color: #363636;
        color: white;
    }

    .pagination ul li a {
        padding: 0 5px;
        border-radius: 5px;
        border: 1px solid #8a8a8a;
    }
</style>

<script>

    $("#modal_year_search").datepicker({
        language: 'ko',
        dateFormat:"yyyy",
        view: 'years',
        minView: 'years',
        clearButton: false,
        autoClose: true,
    });



</script>
