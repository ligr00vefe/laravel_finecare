<table>
    <thead class="thead-origin">
    <tr class="table-top">
        <th>
            No
        </th>
        <th>
            이용자 KEY
        </th>
        <th>
            이름
        </th>
        <th>
            주민번호
        </th>
        <th>
            대상자 ID
        </th>
        <th>
            사업유형
        </th>
        <th>
            등급
        </th>
        <th>
            시/군/구 명
        </th>
        <th>
            서비스 시/군/구 명
        </th>
        <th>
            계약상태
        </th>
        <th>
            계약시작일
        </th>
        <th>
            계약종료일
        </th>
        <th>
            계약종료사유
        </th>
        <th>
            서비스상태
        </th>
        <th>
            서비스 시작일
        </th>
        <th>
            서비스 종료일
        </th>
        <th>
            서비스 만료일
        </th>
        <th>
            지정제공인력
        </th>
        <th>
            지원금합계
        </th>
        <th>
            정부지원금
        </th>
        <th>
            본인부담금
        </th>
        <th>
            자녀출산일자
        </th>
        <th>
            퇴원일자
        </th>
        <th>
            제공자 우편번호
        </th>
        <th>
            서비스제공지
        </th>
        <th>
            대상자주소
        </th>
        <th>
            대상자상세주소
        </th>
        <th>
            이용자 관리번호
        </th>
        <th>
            접수일
        </th>
        <th>
            휴대전화번호
        </th>
        <th>
            자택전화번호
        </th>
        <th>
            이메일
        </th>
        <th>
            직장
        </th>
        <th>
            보건복지부 판정시간
        </th>
        <th>
            지자체 추가 판정시간
        </th>
        <th>
            기타 판정시간
        </th>
        <th>
            타기관 이용경험
        </th>
        <th>
            수급여부
        </th>
        <th>
            활동지원등급(신규)
        </th>
        <th>
            활동지원등급(기존)
        </th>
        <th>
            활동지원등급유형
        </th>
        <th>
            수급결정시기
        </th>
        <th>
            본인부담금
        </th>
        <th>
            주장애명
        </th>
        <th>
            주장애정도
        </th>
        <th>
            주장애등급
        </th>
        <th>
            부장애명
        </th>
        <th>
            부장애정도
        </th>
        <th>
            부장애등급
        </th>
        <th>
            보유질환명
        </th>
        <th>
            투약정보
        </th>
        <th>
            와상장애여부
        </th>
        <th>
            결혼여부
        </th>
        <th>
            가족사항
        </th>
        <th>
            보호자명
        </th>
        <th>
            보호자관계
        </th>
        <th>
            보호자휴대전화번호
        </th>
        <th>
            보호자자택전화번호
        </th>
        <th>
            보호자주소
        </th>
        <th>
            특이사항
        </th>
        <th>
            종합소견
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
                {{ $list->provider_key }}
            </td>
            <td>
                {{ regexp("한글영어", $list->provider_key) }}
            </td>
            <td>
                {{ regexp("숫자", $list->provider_key) }}
            </td>
            <td>
                {{ $list->target_id }}
            </td>
            <td>
                {{ $list->business_type }}
            </td>
            <td>
                {{ $list->grade }}
            </td>
            <td>
                {{ $list->sigungu_name }}
            </td>
            <td>
                {{ $list->service_sigungu_name }}
            </td>
            <td>
                {{ $list->contract_status }}
            </td>
            <td>
                {{ $list->contract_start_date }}
            </td>
            <td>
                {{ $list->contract_end_date }}
            </td>
            <td>
                {{ $list->contract_resign_reason }}
            </td>
            <td>
                {{ $list->service_status }}
            </td>
            <td>
                {{ $list->service_start_date }}
            </td>
            <td>
                {{ $list->service_end_date }}
            </td>
            <td>
                {{ $list->service_resign_date }}
            </td>
            <td>
                {{ $list->target_helper }}
            </td>
            <td>
                {{ $list->help_price_total }}
            </td>
            <td>
                {{ $list->government_help_price }}
            </td>
            <td>
                {{ $list->deductible }}
            </td>
            <td>
                {{ $list->childbirth_date }}
            </td>
            <td>
                {{ $list->leave_hospital_date }}
            </td>
            <td>
                {{ $list->zip_code }}
            </td>
            <td>
                {{ $list->service_address }}
            </td>
            <td>
                {{ $list->address }}
            </td>
            <td>
                {{ $list->address_detail }}
            </td>
            <td>
                {{ $list->client_number }}
            </td>
            <td>
                {{ $list->regdate }}
            </td>
            <td>
                {{ $list->phone }}
            </td>
            <td>
                {{ $list->tel }}
            </td>
            <td>
                {{ $list->email }}
            </td>
            <td>
                {{ $list->company }}
            </td>
            <td>
                {{ $list->bogun_time }}
            </td>
            <td>
                {{ $list->jijache_time }}
            </td>
            <td>
                {{ $list->etc_time }}
            </td>
            <td>
                {{ $list->other_experience }}
            </td>
            <td>
                {{ $list->income_check }}
            </td>
            <td>
                {{ $list->activity_grade }}
            </td>
            <td>
                {{ $list->activity_grade_old }}
            </td>
            <td>
                {{ $list->activity_grade_type }}
            </td>
            <td>
                {{ $list->income_decision_date }}
            </td>
            <td>
                {{ $list->self_charge_price }}
            </td>
            <td>
                {{ $list->main_disable_name }}
            </td>
            <td>
                {{ $list->main_disable_level }}
            </td>
            <td>
                {{ $list->main_disable_grade }}
            </td>
            <td>
                {{ $list->sub_disable_name }}
            </td>
            <td>
                {{ $list->sub_disable_level }}
            </td>
            <td>
                {{ $list->sub_disable_grade }}
            </td>
            <td>
                {{ $list->disease_name }}
            </td>
            <td>
                {{ $list->drug_info }}
            </td>
            <td>
                {{ $list->wasang_check }}
            </td>
            <td>
                {{ $list->marriage_check }}
            </td>
            <td>
                {{ $list->family_info }}
            </td>
            <td>
                {{ $list->protector_name }}
            </td>
            <td>
                {{ $list->protector_relation }}
            </td>
            <td>
                {{ $list->protector_phone }}
            </td>
            <td>
                {{ $list->protector_tel }}
            </td>
            <td>
                {{ $list->protector_address }}
            </td>
            <td>
                {{ $list->etc }}
            </td>
            <td>
                {{ $list->comment }}
            </td>
            <td>
                {{ $list->created_at }}
            </td>
            <td>
                {{ $list->updated_at }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

