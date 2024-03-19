<table>
    <thead class="thead-origin">
    <tr class="table-top">
        <th>
            No
        </th>
        <th>
            이름
        </th>
        <th>
            생년월일
        </th>
        <th>
            성별
        </th>
        <th>
            전화번호
        </th>
        <th>
            주소
        </th>
        <th>
            상태
        </th>
        <th>
            입사일
        </th>
        <th>
            퇴사일
        </th>
        <th>
            근속연수
        </th>
    </tr>

    </thead>
    <tbody>
    @foreach ($lists as $list)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ $list->birth }}
            </td>
            <td>
                {{ \App\Classes\Custom::rsno_to_gender($list->birth) }}
            </td>
            <td>
                {{ $list->phone }}
            </td>
            <td>
                {{ $list->address }}
            </td>
            <td>
                {{ $list->contract }}
            </td>
            <td>
                {{ $list->contract_start_date }}
            </td>
            <td>
                {{ $list->contract_end_date }}
            </td>
            <td>
                {{ $list->contract_date }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

