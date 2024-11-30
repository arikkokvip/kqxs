jQuery(document).ready(function ($) {
    $('#kira-form').on('submit', function (e) {
        e.preventDefault();
        $('#loading-spinner').css('display', 'flex');
        $('#kira-submit').css('display', 'none');
        var formData = {
            'action': 'submit_api_form',
            'nonce': apiForm.nonce,
            'tele_id': $('#tele_id').val(),
            'special_result': $('#special_result').val(),
            'medium_result_1': $('#medium_result_1').val(),
            'medium_result_2': $('#medium_result_2').val(),
            'medium_result_3': $('#medium_result_3').val()
        };

        $.ajax({
            type: 'POST',
            url: apiForm.ajax_url,
            data: formData,
            success: function (response) {
                $('#loading-spinner').css('display', 'none');
                $('#kira-submit').css('display', 'flex');
                if (response.success) {
                    $('#kira-form')[0].reset();
                    $('#form-message').html('<p class="kira-success">' + response.data + '</p>');
                } else {
                    $('#form-message').html('<p class="kira-fail">' + response.data + '</p>');
                }
            }
        });
    });
    $('#kira-stats-form').on('submit', function (e) {
        e.preventDefault();
        $('#loading-spinner-stat').css('display', 'flex');
        $('#kira-stat-btn').css('display', 'none');
        var tele_id = $('#stats_tele_id').val();
        var nonce = apiForm.nonce;
        var ajax_url = apiForm.ajax_url;

        $.ajax({
            url: ajax_url,
            type: 'POST',
            data: {
                action: 'get_user_stats',
                nonce: nonce,
                tele_id: tele_id
            },
            success: function (response) {
                $('#loading-spinner-stat').css('display', 'none');
                $('#kira-stat-btn').css('display', 'flex');
                if (response.success) {
                    var data = response.data.data;
                    console.log(data);
                    $('#stats-this-week').text('Tuần này: ' + data.this_week);
                    $('#stats-last-week').text('Tuần trước: ' + data.last_week);
                    $('#stats-this-month').text('Tháng này: ' + data.this_month);
                    $('#stats-last-month').text('Tháng trước: ' + data.last_month);
                    $('#stats-year').text('Cả năm: ' + data.year);
                    $('#stats-result').show();
                    var records = response.data.records;
                    var container = $('#stat-history');
                    container.empty();

                    records.forEach(function (record) {
                        var formattedDate = moment(record.date).format('D/M/YYYY');
                        var dateItem = $('<li></li>').css('font-weight', 'bold').append(formattedDate + ' - ');
                        var lotoResults = [];
                        record.predict_result.forEach(function(result, index) {
                            var key = Object.keys(result)[0];
                            var value = result[key];
                            var status = result.status;
            
                            if (key === 'special_result') {
                                var resultText = 'ĐB: ';
                                var resultValue = $('<span></span>').text(value)
                                    .css('color', status ? 'green' : 'red');
                                dateItem.append(resultText).append(resultValue);
                            } else if (key.startsWith('medium_result')) {
                                lotoResults.push({
                                    value: value,
                                    status: status
                                });
                            }
                        });
            
                        if (lotoResults.length > 0) {
                            dateItem.append(' | Loto: ');
                            lotoResults.forEach(function(result, index) {
                                var resultValue = $('<span></span>').text(result.value)
                                    .css('color', result.status ? 'green' : 'red');
                                dateItem.append(resultValue);
            
                                // Add a separator between results, but not after the last one
                                if (index < lotoResults.length - 1) {
                                    dateItem.append(', ');
                                }
                            });
                        }
            
                        container.append(dateItem);
                    });
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function () {
                $('#loading-spinner-stat').css('display', 'none');
                $('#kira-stat-btn').css('display', 'flex');
                alert('There was an error processing your request.');
            }
        });
    });
    // Handle tab switching
    window.openTab = function (evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Fetch top data from API

});

