var ajaxurl = frontendajax.ajaxurl;

function loadLotteryResults(date, region, element) {
    $.ajax({
        url: ajaxurl, // ajaxurl là biến global do WordPress cung cấp khi dùng trong admin, cần định nghĩa nếu dùng ngoài admin
        type: 'POST',
        data: {
            'action': 'load_lottery_results', // Tên hàm xử lý yêu cầu trong WordPress
            'date': date, // Biến ngày, có thể lấy từ input hoặc một biến JavaScript khác
            'region': region // Biến khu vực
        },
        success: function (response) {
            if (response.success) {
                // Xử lý dữ liệu trả về nếu thành công
                element.empty().html(response.data);
            } else {
                // Xử lý nếu có lỗi
                element.html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.html('<p>Đã xảy ra lỗi trong quá trình tải dữ liệu.</p>');
        }
    });
}
function loadLotteryResultsProvince(date, provinceId, element) {
    $.ajax({
        url: ajaxurl, // ajaxurl là biến global do WordPress cung cấp khi dùng trong admin, cần định nghĩa nếu dùng ngoài admin
        type: 'POST',
        data: {
            'action': 'load_lottery_results_province', // Tên hàm xử lý yêu cầu trong WordPress
            'date': date, // Biến ngày, có thể lấy từ input hoặc một biến JavaScript khác
            'provinceId': provinceId // Biến khu vực
        },
        success: function (response) {
            if (response.success) {
                element.empty().html(response.data);
                console.log(response.data);
            } else {
                element.html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.html('<p>Đã xảy ra lỗi trong quá trình tải dữ liệu.</p>');
        }
    });
}
function loadStatisticLoto(numberOfDate, lastDate, coupleOfNumber, option, element) {
    element.find('#loading').css('display', 'flex');
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_statistic',
            'numberOfDate': numberOfDate,
            'lastDate': lastDate,
            'coupleOfNumber': coupleOfNumber,
            'option': option,
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);
                element.find('.result-content').empty().html(response.data);

            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function loadStatisticGan(numberOfDate, lastDate, coupleOfNumber, element) {
    element.find('#loading').css('display', 'flex');
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_gan_statistic',
            'numberOfDate': numberOfDate,
            'lastDate': lastDate,
            'coupleOfNumber': coupleOfNumber,
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);
                element.find('.result-content').empty().html(response.data);

            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function loadXothu(province, element) {
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_xothu',
            'province': province
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);

                element.find('.result-content').empty().html(response.data);
                var numbers = $('.number-rs').html('<img width="15" height="15" src="https://i.gifer.com/ZKZg.gif"/>');
                showRandomNumbers(numbers);
            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function showRandomNumbers(numbers) {
    var delay = 500; // Độ trễ giữa các số (milisecond)
    var startIndex = numbers.length - 1; // Bắt đầu từ giải 7
    var i = startIndex;
    var interval = setInterval(function () {
        var number = $(numbers[i]);
        var prize = number.data('prize');
        var quantity = number.data('number');
        var randomNumber = generateRandomNumber(quantity);
        number.text(randomNumber);
        i--;
        if (i < 0) clearInterval(interval); // Dừng khi đã hiển thị hết tất cả các số
    }, delay);
}
function generateRandomNumber(quantity) {
    var min = Math.pow(10, quantity - 1); // Số nhỏ nhất có thể tạo
    var max = Math.pow(10, quantity) - 1; // Số lớn nhất có thể tạo
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
function loadStatisticLotoDB(numberOfDate, lastDate, coupleOfNumber, element) {
    element.find('#loading').css('display', 'flex');
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_statistic_db',
            'numberOfDate': numberOfDate,
            'lastDate': lastDate,
            'coupleOfNumber': coupleOfNumber,
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);
                element.find('.result-content').empty().html(response.data);

            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function loadStatisticDau(numberOfDate, lastDate, coupleOfNumber, element) {
    element.find('#loading').css('display', 'flex');
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_dau_statistic',
            'numberOfDate': numberOfDate,
            'lastDate': lastDate,
            'coupleOfNumber': coupleOfNumber,
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);
                element.find('.result-content').empty().html(response.data);

            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function loadStatisticDuoi(numberOfDate, lastDate, coupleOfNumber, element) {
    element.find('#loading').css('display', 'flex');
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            'action': 'load_lottery_duoi_statistic',
            'numberOfDate': numberOfDate,
            'lastDate': lastDate,
            'coupleOfNumber': coupleOfNumber,
        },
        success: function (response) {
            element.find('#loading').css('display', 'none');
            if (response.success) {
                console.log(response.data);
                element.find('.result-content').empty().html(response.data);

            } else {
                element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.find('#loading').css('display', 'none');
            element.find('.result-content').empty().html('<p>Không thể tải kết quả.</p>');
        }
    });
}
function loadVietlott645(date, element) {
    $.ajax({
        url: ajaxurl, // ajaxurl là biến global do WordPress cung cấp khi dùng trong admin, cần định nghĩa nếu dùng ngoài admin
        type: 'POST',
        data: {
            'action': 'load_vietlott_mega_645', // Tên hàm xử lý yêu cầu trong WordPress
            'date': date, // Biến ngày, có thể lấy từ input hoặc một biến JavaScript khác
        },
        success: function (response) {

            if (response.success) {
                element.empty().html(response.data);
                console.log(response.data);
            } else {
                element.html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.html('<p>Đã xảy ra lỗi trong quá trình tải dữ liệu.</p>');
        }
    });
}
///xo so tinh khoang time

function loadLotteryByRangeDate(range, provinceId, element) {
    $.ajax({
        url: ajaxurl, // ajaxurl là biến global do WordPress cung cấp khi dùng trong admin, cần định nghĩa nếu dùng ngoài admin
        type: 'POST',
        data: {
            'action': 'load_lottery_by_range', // Tên hàm xử lý yêu cầu trong WordPress
            'range': range, // Biến ngày, có thể lấy từ input hoặc một biến JavaScript khác
            'provinceId': provinceId // Biến khu vực
        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                // Xử lý dữ liệu trả về nếu thành công
                element.empty().html(response.data);
            } else {
                // Xử lý nếu có lỗi
                element.html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.html('<p>Đã xảy ra lỗi trong quá trình tải dữ liệu.</p>');
        }
    });
}

function loadCountLotteryByRangeDate(range, provinceId, element) {
    $.ajax({
        url: ajaxurl, // ajaxurl là biến global do WordPress cung cấp khi dùng trong admin, cần định nghĩa nếu dùng ngoài admin
        type: 'POST',
        data: {
            'action': 'load_count_lottery_by_range', // Tên hàm xử lý yêu cầu trong WordPress
            'range': range, // Biến ngày, có thể lấy từ input hoặc một biến JavaScript khác
            'provinceId': provinceId // Biến khu vực
        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                // Xử lý dữ liệu trả về nếu thành công
                element.empty().html(response.data);
            } else {
                // Xử lý nếu có lỗi
                element.html('<p>Không thể tải kết quả.</p>');
            }
        },
        error: function () {
            element.html('<p>Đã xảy ra lỗi trong quá trình tải dữ liệu.</p>');
        }
    });
}