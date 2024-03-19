function DELETE_MULTIPLE(f)
{
    console.log($("input[name='id[]']:checked"));

    if (!confirm("선택한 게시글을 삭제하시겠습니까?")) {
        return false;
    }

    var _checked = $("input[name='id[]']:checked");
    var ids = "";

    $.each (_checked, function (i, v) {
        if (ids != "") ids += ",";
        ids += $(v).val();
    });

    if (ids == "") {
        alert("삭제할 게시글을 선택해주세요");
        return false;
    }

    f.id.value = ids;
}

window.onload = function() {
    $("#board_check_all").on("change", function () {

        if ($(this).is(":checked")) {
            $("input[name='id[]']").prop("checked", true);
        } else {
            $("input[name='id[]']").prop("checked", false);
        }

    });
};
