@foreach ($offDays ?? [] as $off)
    <tr>
        <td>
            {{ $off->off_day }}
        </td>
        <td>
            <button type="button" class="off-delete" data-year="{{ (int) date("Y", strtotime($off->off_day)) }}" data-month="{{ (int) date("m", strtotime($off->off_day)) }}" data-id="{{ $off->id }}">
                삭제
            </button>
        </td>
    </tr>
@endforeach
