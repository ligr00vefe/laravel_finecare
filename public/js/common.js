
/* foreach polyfill */
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}


// 스트링 00:00 을 분으로 계산해서 리턴(인트)
function cal_time(time)
{
    if (typeof time == "undefined") return 0;
    var hour = parseInt(time.substring(0, 2))*60;
    var minute = parseInt(time.substring(3, 5));
    return hour+minute;
}

// 분을 스트링 00:00 형태로
function minute_to_hour(min)
{
    var hour = Math.floor(min / 60);
    var minute = min % 60;

    if (hour < 10) {
        hour = "0"+hour;
    }

    if (minute < 10) {
        minute = "0" + minute;
    }

    return hour + ":" + minute;
}

function route(url)
{
    location.href = url;
}


// 컴마제거
function removeComma(str)
{
    var n = parseInt(str.replace(/,/g,""));
    return n;
}


function get_gabgeunse(total_pay, key)
{
    return $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        url: '/ajax/gabgeunse',
        dataType: 'json',
        data: {
            total_pay: total_pay,
            provider_key: key
        }
    });
}
