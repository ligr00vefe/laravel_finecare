<table class="member-list b-last-bottom">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            원부번호
        </th>
        <th>
            생년월일
        </th>
        <th>
            가입자명
        </th>
        <th>
            결정보험료
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $i => $list)
        <tr>
            <td>{{ ($i+1)+(($page-1) * 15) }}</td>
            <td>{{ $list->original_number }}</td>
            <td>{{ convert_birth($list->rsNo) }}</td>
            <td>{{ $list->name }}</td>
            <td>{{ $list->insurance_fee }}</td>
        </tr>
    @endforeach
    @if ($lists->isEmpty())
        <tr>
            <td colspan="5" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
        </tr>
    @endif
    </tbody>
</table>
