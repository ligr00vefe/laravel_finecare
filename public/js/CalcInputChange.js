
// 기타청구내역 시군구비 시간
var sigungube_time_total = $(".etc_charge_sigungube_time");
var sigungube_pay_total = $(".etc_charge_sigungube_pay");
var etc_time_total = $(".etc_charge_except_time");
var etc_pay_total = $(".etc_charge_except_pay");
var bojecn_total = $(".bojeon_total");
var jaboodam_total = $(".jaboodam_total");
var jaesoodang_total = $(".jaesoodang_total");
var bannap_add_total = $(".bannap_add_total");

// 공제 수기입력
var gunbo_total = $(".gunbo_total");
var yearly_total = $(".yearly_total");
var bad_income_total = $(".bad_income_total");
var etc_tax_1_total = $(".etc_tax_1_total");
var etc_tax_2_total = $(".etc_tax_2_total");
var workers_tax_total = $(".workers_tax_total");

// 차인지급액토탈
var tax_sub_total = $(".tax_sub_total");

// 바우처상 지급합계(토탈)
var total_voucher_pay =  $("#voucher_pay_total");

// 바우처상 지급합계 페이지로딩시 기본값
var total_voucher_pay_origin =  Number(removeComma(total_voucher_pay.val()));

// 법정제수당 또는 차액
var payment_diff_total = $("#payment_diff_total");


$("#list_contents .writing").on("click", function () {
    $(this).val(removeComma($(this).val()));
});

$("#list_contents .writing").on("blur", function () {

    $(this).val(Number($(this).val()).toLocaleString());
});


$(".etc_charge_pay, .except_charge_pay").on("keyup", function () {

    var prt = $(this).parents("tr");
    var voucher_pay_total = $(".voucher_pay_total", prt);

    var etc_charge_pay = Number($(".etc_charge_pay", prt).val());
    var except_time = Number($(".except_charge_pay", prt).val());
    var standard_pay = Number(removeComma($(".standard_pay", prt).val()));
    var payment_diff = $(".payment_diff", prt);


    var time_locale = (etc_charge_pay + except_time + total_voucher_pay_origin);

    var all_voucher_pay_total  = $(".voucher_pay_total");
    var all_voucher_pay_total_price = 0;

    voucher_pay_total.val(time_locale.toLocaleString());
    payment_diff.val((time_locale-standard_pay).toLocaleString());


    var payment_diff_all = $(".payment_diff");
    var diff_price = 0;

    $.each(all_voucher_pay_total, function (i,v) {
        all_voucher_pay_total_price += Number(removeComma($(v).val()));
    });

    $.each(payment_diff_all, function (i,v) {
        diff_price += Number(removeComma($(v).val()));
    });

    total_voucher_pay.val(all_voucher_pay_total_price.toLocaleString());
    payment_diff_total.val(diff_price.toLocaleString())

});


// 기타청구내역 시군구비시간
$(".etc_charge_time").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");
    var total_time = $(".etc_charge_total_time", prt);
    var except_time = Number($(".except_charge_time", prt).val());
    var etc_charge_time_all = $(".etc_charge_time");
    var all_time = 0;

    $.each(etc_charge_time_all, function (i, v) {
        all_time += Number($(v).val());
    });
    
    var time_locale = _value + except_time;
    
    // 시군구비시간+예외청구시간
    total_time.val(time_locale.toLocaleString());
    
    // 모든 시군구비시간
    sigungube_time_total.val(all_time.toLocaleString());

});

// 기타청구내역 시군구비총금액
$(".etc_charge_pay").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");
    var total = $(".etc_charge_total_pay", prt);
    var another = Number($(".except_charge_pay", prt).val());
    var target_all = $(".etc_charge_pay");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });
    var time_locale = _value + another;
    total.val(time_locale.toLocaleString());
    sigungube_pay_total.val(all.toLocaleString());
});


// 기타청구내역 예외청구시간
$(".except_charge_time").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");
    var total = $(".etc_charge_total_time", prt);
    var another = Number($(".etc_charge_time", prt).val());
    var target_all = $(".except_charge_time");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });
    var time_locale = _value + another;
    total.val(time_locale.toLocaleString());
    etc_time_total.val(all.toLocaleString());
});



// 기타청구내역 예외청구금액
$(".except_charge_pay").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");
    var total = $(".etc_charge_total_pay", prt);
    var another = Number($(".etc_charge_pay", prt).val());
    var target_all = $(".except_charge_pay");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });
    var time_locale = _value + another;
    total.val(time_locale.toLocaleString());
    etc_pay_total.val(all.toLocaleString());
});



// 보전수당
$(".bojeon").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");

    var target_all = $(".bojeon");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });

    bojecn_total.val(all.toLocaleString());
});


// 자부담급여
$(".jaboodam").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");

    var target_all = $(".jaboodam");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });

    jaboodam_total.val(all.toLocaleString());
});

// 법정제수당
$(".jaesoodang").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");

    var target_all = $(".jaesoodang");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });

    jaesoodang_total.val(all.toLocaleString());
});

// 반납추가
$(".bannap").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");

    var target_all = $(".bannap");
    var all = 0;
    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });

    bannap_add_total.val(all.toLocaleString());
});



// 세금 건보정산~기타공제2
$(".worker_tax").on("keyup", function () {
    var _value = Number($(this).val());
    var prt = $(this).parents("tr");
    var total = $(".worker_tax_total", prt);

    var tax_sub_pay = $(".tax_sub", prt);
    var standard_pay_total = removeComma($(".standard_pay_total", prt).val());

    var worker_tax = $(".worker_tax", prt);
    var tax = 0;
    $.each(worker_tax, function (i, v) {
        var intVal = 0;
        if ($(v).prop('tagName').toLowerCase() == "span") {
            intVal = Number(removeComma($(v).val()));
        } else {
            intVal = Number($(v).val());
        }
        tax += intVal;
    });

    var target = $(this).attr("class").split(/\s+/)[1];
    var target_all;
    var target_total;
    var all = 0;

    switch (target)
    {
        case "gunbo_tax":
            target_all = $(".gunbo_tax");
            target_total = gunbo_total;
            break;
        case "year_total_tax":
            target_all = $(".year_total_tax");
            target_total = yearly_total;
            break;
        case "bad_income_get":
            target_all = $(".bad_income_get");
            target_total = bad_income_total;
            break;
        case "etc_tax_1":
            target_all = $(".etc_tax_1");
            target_total = etc_tax_1_total;
            break;
        case "etc_tax_2":
            target_all = $(".etc_tax_2");
            target_total = etc_tax_2_total;
            break;
    }

    $.each(target_all, function (i, v) {
        all += Number($(v).val());
    });



    total.val(tax.toLocaleString());
    target_total.val(all.toLocaleString());

    var total_all = $(".worker_tax_total");
    var total_all_price = 0;
    $.each (total_all, function (i, v) {
        total_all_price += Number(removeComma($(v).val()));
    })

    workers_tax_total.val(total_all_price.toLocaleString());
    tax_sub_pay.val((standard_pay_total - tax).toLocaleString());

    var tax_sub_all = $(".tax_sub");
    var tax_sub_all_price = 0;
    $.each (tax_sub_all, function (i, v) {
        tax_sub_all_price += Number(removeComma($(v).val()));
    })

    tax_sub_total.val(tax_sub_all_price.toLocaleString());
});



$("input[name='save']").on("change", function () {
    var selected = $(this).val();
    if (selected == "voucher") {
        $(".company_tax").show();
        $(".worker_tax").show();
        $(".total_payment_voucher_select").show();
        $(".standard_tax").hide();
        $(".standard_total").hide();
    }
    else if (selected == "standard") {
        $(".company_tax").hide();
        $(".worker_tax").hide();
        $(".total_payment_voucher_select").hide();
        $(".standard_tax").show();
        $(".standard_total").show();
    }
});

// $("input[name='save']").on("change", function () {
//
//     var selected = $(this).val();
//     var payment = $(".selected_payment");
//
//     var tax_nation_selector = $("#paymentsSaveForm input[name='tax_nation_selector']").val();
//     var tax_health_selector = $("#paymentsSaveForm input[name='tax_health_selector']").val();
//     var tax_employ_selector = $("#paymentsSaveForm input[name='tax_employ_selector']").val();
//     var tax_industry_selector = $("#paymentsSaveForm input[name='tax_industry_selector']").val();
//     var tax_gabgeunse_selector = $("#paymentsSaveForm input[name='tax_gabgeunse_selector']").val();
//     var employ_tax_selector = $("#paymentsSaveForm input[name='employ_tax_selector']").val();
//     var employ_percentage_add = 0.8;
//
//     switch (employ_tax_selector)
//     {
//         case "basic" :
//             employ_percentage_add += 0;
//             break;
//         case "150under" :
//             employ_percentage_add += 0.25;
//             break;
//         case "150over" :
//             employ_percentage_add += 0.45;
//             break;
//         case "1000under" :
//             employ_percentage_add += 0.65;
//             break;
//         case "1000over" :
//             employ_percentage_add += 0.85;
//             break;
//     }
//
//     var nation_tax_percentage = 4.50 / 100;
//     var health_tax_percentage = 3.43 / 100;
//     var employ_tax_percentage = 0.80 / 100;
//     var employ_company_tax_percentage = employ_percentage_add;
//     var industry_tax_percentage = Number($("#paymentsSaveForm input[name='industry_tax_percentage']").val()) / 100;
//
//
//
//     $.each(payment, function(i, v) {
//
//         var provider_key = $("input[name='provider_key[]']").val();
//         var prt = $(v).parents("tr");
//
//         var tax_nation = Number(removeComma($("input[name='worker_nation[]']", prt).val()));
//         var tax_health = Number(removeComma($("input[name='worker_health[]']", prt).val()));
//         var tax_employ = Number(removeComma($("input[name='worker_employ[]']", prt).val()));
//         var tax_company_employ = Number(removeComma($("input[name='company_tax_employ[]']", prt).val()));
//         var tax_industry = Number(removeComma($("input[name='company_tax_industry[]']", prt).val()));
//         var tax_gabgeunse = Number(removeComma($("input[name='worker_gabgeunse[]']", prt).val()));
//         var tax_joominse = Number(removeComma($("input[name='worker_juminse[]']", prt).val()));
//
//         if (selected == "voucher") {
//
//             var pay_total = Number($("input[name='voucher_payment_total[]']", prt).val());
//
//             $(v).val(pay_total.toLocaleString());
//
//             if (tax_nation_selector == "percentage") {
//                 tax_nation = (pay_total * nation_tax_percentage);
//                 $("input[name='worker_nation[]']", prt).val(tax_nation.toLocaleString());
//                 $("input[name='company_tax_nation[]']", prt).val(tax_nation.toLocaleString());
//             }
//
//             if (tax_health_selector == "percentage") {
//                 tax_health = (pay_total * health_tax_percentage);
//                 $("input[name='worker_health[]']", prt).val(tax_health.toLocaleString());
//                 $("input[name='company_tax_health[]']", prt).val(tax_health.toLocaleString());
//             }
//
//             if (tax_employ_selector == "percentage") {
//                 tax_employ = (pay_total * employ_tax_percentage);
//                 tax_company_employ = (pay_total * employ_company_tax_percentage);
//                 $("input[name='worker_employ[]']", prt).val(tax_employ.toLocaleString());
//                 $("input[name='company_tax_employ[]']", prt).val(tax_company_employ.toLocaleString());
//             }
//
//             if (tax_industry_selector == "percentage") {
//                 tax_industry = (pay_total * industry_tax_percentage);
//                 $("input[name='company_tax_industry[]']", prt).val(tax_industry.toLocaleString());
//             }
//
//             if (tax_gabgeunse_selector == "percentage") {
//                 console.log(pay_total);
//                 tax_gabgeunse = get_gabgeunse(pay_total, provider_key);
//                 tax_joominse = Math.round(tax_gabgeunse * 0.1);
//                 $("input[name='worker_gabgeunse[]']", prt).val(tax_gabgeunse.toLocaleString());
//                 $("input[name='worker_juminse[]']", prt).val(tax_joominse.toLocaleString());
//             }
//
//             $("input[name='worker_tax_total[]']").val(tax_nation + tax_health + tax_employ + tax_gabgeunse + tax_joominse);
//             $("input[name='company_tax_total[]']").val(tax_nation + tax_health + tax_company_employ + tax_industry);
//
//
//         } else if (selected == "standard") {
//             $(v).val($("input[name='standard_total_pay[]']", prt).val());
//         }
//
//
//     });
//
// });