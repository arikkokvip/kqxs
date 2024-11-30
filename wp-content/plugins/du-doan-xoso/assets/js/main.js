document.addEventListener('DOMContentLoaded', function () {
    jQuery(document).ready(function ($) {

        function toggleBodyScroll(toggle) {
            if (toggle) {
                $('body').css('overflow', 'hidden');
                $('html').css('overflow', 'hidden');
				
            } else {
                $('body').css('overflow', '');
                $('html').css('overflow', '');
				
            }
        }
        const popupOverlay = document.querySelector('.popup-overlay');
        const popupClose = document.querySelector('.popup-close');
        const kiraButton = document.querySelector('.kira-btn-popup');

        kiraButton.addEventListener('click', function () {
            popupOverlay.style.display = 'flex';
            toggleBodyScroll(true);

        });

        popupClose.addEventListener('click', function () {
            popupOverlay.style.display = 'none';
            toggleBodyScroll(false);

        });

        popupOverlay.addEventListener('click', function (e) {
            if (e.target === popupOverlay) {
                popupOverlay.style.display = 'none';
                toggleBodyScroll(false);

            }
        });

        const popupTopOverlay = document.querySelector('.popup-top-overlay');
        const popupTopClose = document.querySelector('.popup-top-close');
        const kiraTopButton = document.querySelector('.kira-btn-top-popup');

        kiraTopButton.addEventListener('click', function () {
            fetchTopData();
            popupTopOverlay.style.display = 'flex';
            toggleBodyScroll(true);

        });

        popupTopClose.addEventListener('click', function () {
            popupTopOverlay.style.display = 'none';
            toggleBodyScroll(false);

        });

        popupTopOverlay.addEventListener('click', function (e) {
            if (e.target === popupTopOverlay) {
                popupTopOverlay.style.display = 'none';
                toggleBodyScroll(false);

            }
        });

        const popupStatOverlay = document.querySelector('.popup-stats-overlay');
        const popupStatClose = document.querySelector('.popup-stats-close');
        const kiraStatButton = document.querySelector('.kira-btn-stats-popup');

        kiraStatButton.addEventListener('click', function () {
            popupStatOverlay.style.display = 'flex';
            toggleBodyScroll(true);

        });

        popupStatClose.addEventListener('click', function () {
            popupStatOverlay.style.display = 'none';
            toggleBodyScroll(false);

        });

        popupStatOverlay.addEventListener('click', function (e) {
            if (e.target === popupStatOverlay) {
                popupStatOverlay.style.display = 'none';
                toggleBodyScroll(false);

            }
        });

        function fetchTopData() {
            var nonce = apiForm.nonce;
            var ajax_url = apiForm.ajax_url;

            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: {
                    action: 'get_top_stats',
                    nonce: nonce
                },
                success: function (response) {
                    if (response.success) {
                        var data = response.data;
                        updateTopStats('#top-this-week', data.this_week);
                        updateTopStats('#top-last-week', data.last_week);
                        updateTopStats('#top-this-month', data.this_month);
                        updateTopStats('#top-last-month', data.last_month);
                        updateTopStats('#top-year', data.year);
                        $('.tabcontent').first().show();
                        $('.tab a').first().addClass('active');
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function () {
                    alert('There was an error processing your request.');
                }
            });
        }

        // Function to update top stats
        function updateTopStats(selector, stats) {
            var container = $(selector);
            container.empty();
            stats.forEach(function (item, index) {
                var cssClass = '';
                if (index === 0) {
                    cssClass = 'top-first';
                } else if (index === 1) {
                    cssClass = 'top-second';
                } else if (index === 2) {
                    cssClass = 'top-third';
                }
                var maskedTeleId = maskTeleId(item.tele_id);

                container.append('<li class="' + cssClass + '"><span>' + (index + 1) + '. ' + maskedTeleId + ':</span><span> ' + item.point + ' điểm<span></li>');
            });
        }
        function maskTeleId(teleId) {
            var visibleLength = 3; // Số ký tự muốn hiển thị
            if (teleId.length <= visibleLength) {
                return teleId;
            }
            var maskedPart = teleId.slice(visibleLength).replace(/./g, '*');
            return teleId.slice(0, visibleLength) + maskedPart;
        }
    });

});
