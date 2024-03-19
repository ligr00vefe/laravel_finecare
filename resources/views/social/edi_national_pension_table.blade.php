<table class="member-list b-last-bottom">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            성명
        </th>
        <th>
            생년월일
        </th>
        <th>
            기준소득월액
        </th>
        <th>
            월보험료(계)
        </th>
        <th>
            사용자 부담금
        </th>
        <th>
            본인기여금
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $i => $list)
        <tr>
            <td>{{ ($i+1) + (($page-1) * 15) }}</td>
            <td>{{ $list->name }}</td>
            <td>{{ convert_birth($list->rsNo) }}</td>
            <td>{{ number_format($list->monthly_base_income) }}</td>
            <td>{{ number_format($list->monthly_ins_price) }}</td>
            <td>{{ number_format($list->personal_charge) }}</td>
            <td>{{ number_format($list->personal_contribute_price) }}</td>
        </tr>
    @endforeach
    @if ($lists->isEmpty())
        <tr>
            <td colspan="7">데이터가 없습니다.</td>
        </tr>
    @endif
    </tbody>
</table>
