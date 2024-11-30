<?php
/*
Plugin Name: Dự đoán xổ số
Description: Sử dụng shortcode [kira_render_form]
Version: 1.2
Author: Kira
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
// Enqueue media library
function kira_enqueue_media()
{
    wp_enqueue_media();
    wp_enqueue_script('kira-media-script', plugin_dir_url(__FILE__) . 'assets/js/media.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'kira_enqueue_media');

// Add settings menu
function kira_lottery_menu()
{
    add_menu_page(
        'Kira Lottery Settings',
        'Kira Lottery',
        'manage_options',
        'kira-lottery-settings',
        'kira_lottery_settings_page',
        'dashicons-admin-generic'
    );
}
add_action('admin_menu', 'kira_lottery_menu');

// Render settings page
function kira_lottery_settings_page()
{
?>
    <div class="wrap">
        <h1>Kira Lottery Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('kira_lottery_settings');
            do_settings_sections('kira-lottery-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// Register settings
function kira_lottery_settings_init()
{
    register_setting('kira_lottery_settings', 'kira_rule_content');
    register_setting('kira_lottery_settings', 'kira_banner_image');

    add_settings_section(
        'kira_lottery_section',
        'Lottery Settings',
        'kira_lottery_section_callback',
        'kira-lottery-settings'
    );

    add_settings_field(
        'kira_rule_content',
        'Rule Content',
        'kira_rule_content_callback',
        'kira-lottery-settings',
        'kira_lottery_section'
    );

    add_settings_field(
        'kira_banner_image',
        'Banner Image',
        'kira_banner_image_callback',
        'kira-lottery-settings',
        'kira_lottery_section'
    );
}
add_action('admin_init', 'kira_lottery_settings_init');

function kira_lottery_section_callback()
{
    echo 'Update the content and banner image for the lottery plugin.';
}

function kira_rule_content_callback()
{
    $content = get_option('kira_rule_content', '');
    wp_editor($content, 'kira_rule_content', array(
        'textarea_name' => 'kira_rule_content',
        'media_buttons' => false,
        'textarea_rows' => 10,
        'teeny' => true
    ));
}

function kira_banner_image_callback()
{
    $image = get_option('kira_banner_image', '');
    echo '<input type="hidden" id="kira_banner_image" name="kira_banner_image" value="' . esc_attr($image) . '" />';
    echo '<button type="button" class="button" id="kira_select_banner_image">Select Image</button>';
    echo '<div id="kira_banner_image_preview">';
    if ($image) {
        echo '<img src="' . esc_url($image) . '" style="max-width:200px;height:auto;" />';
    }
    echo '</div>';
}

// Handle file upload
function kira_handle_file_upload()
{
    if (!empty($_POST['kira_banner_image'])) {
        update_option('kira_banner_image', esc_url_raw($_POST['kira_banner_image']));
    }
}
add_action('admin_post_update', 'kira_handle_file_upload');

function get_display_date()
{
    // Thiết lập múi giờ UTC+7
    date_default_timezone_set('Asia/Bangkok');

    // Lấy thời gian hiện tại
    $current_time = new DateTime();

    // Tạo thời gian mốc 17:30 hôm nay
    $cutoff_time = new DateTime();
    $cutoff_time->setTime(17, 30);

    // So sánh thời gian hiện tại với 17:30
    if ($current_time < $cutoff_time) {
        // Nếu trước 17:30, hiển thị ngày hôm nay
        return $current_time->format('d-m-Y');
    } else {
        // Nếu sau 17:30, hiển thị ngày mai
        $current_time->modify('+1 day');
        return $current_time->format('d-m-Y');
    }
}

function get_week_dates()
{
    $monday = strtotime('monday this week');
    $sunday = strtotime('sunday this week');

    return array(
        'start' => date('Y-m-d', $monday),
        'end' => date('Y-m-d', $sunday)
    );
}
// Function to render form
function kira_render_form()
{
    $dates = get_week_dates();
    $start_date = $dates['start'];
    $end_date = $dates['end'];

    // Fetch data from external API
    $api_url = "https://lotterysoez.xyz/api/get-statistic?start=$start_date&end=$end_date&page=1&page_size=3";
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error('API request failed.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Invalid JSON response from API.');
    }
    // usort($data['data'], function ($a, $b) {
    //     return $b['count'] - $a['count'];
    // });
    $rule_content = get_option('kira_rule_content', 'RULE');
    $banner_image = get_option('kira_banner_image', plugin_dir_url(__FILE__) . 'assets/img/default-banner.jpg');

    ob_start();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <div class="kira-plugin">
        <div class="kira-banner">
            <img src="<?php echo esc_url($banner_image); ?>" alt="Dự đoán kết quả xổ số banner">
        </div>
        <div class="kira-rule">
            <blockquote>
                <?php echo wp_kses_post($rule_content); ?>
            </blockquote>
        </div>
        <div class="kira-btn-area">
            <button class="kira-btn-popup btn-3">Dự đoán ngay</button>
            <button class="kira-btn-top-popup btn-2">Bảng xếp hạng</button>
            <button class="kira-btn-stats-popup btn-4">Tra cứu</button>
        </div>
        <!-- Popup for kira-content -->
        <div class="popup-overlay">
            <div class="popup-content">
                <span class="popup-close">&times;</span>
                <div class="kira-content">

                    <form id="kira-form" class="kira-form" method="post">
                        <p class="text-center"><strong>ĐIỀN VÀO DỰ ĐOÁN NHÉ</strong></p>
                        <div class="kira-form-group">
                            <label for="tele_id">Telegram:</label>
                            <input type="text" id="tele_id" class="kira-inp" name="tele_id" placeholder="Điền Telegram username" required>
                        </div>
                        <div class="kira-form-group">
                            <label for="special_result">Đặc biệt:</label>
                            <input type="text" id="special_result" class="kira-inp" name="special_result" placeholder="Điền số có 2 chữ số" required>
                        </div>
                        <div class="kira-form-group">
                            <label for="medium_result_1">Loto 1:</label>
                            <input type="text" id="medium_result_1" class="kira-inp" name="medium_result_1" placeholder="Điền số có 2 chữ số" required>
                        </div>
                        <div class="kira-form-group">
                            <label for="medium_result_2">Loto 2:</label>
                            <input type="text" id="medium_result_2" class="kira-inp" name="medium_result_2" placeholder="Điền số có 2 chữ số" required>
                        </div>
                        <div class="kira-form-group">
                            <label for="medium_result_3">Loto 3:</label>
                            <input type="text" id="medium_result_3" class="kira-inp" name="medium_result_3" placeholder="Điền số có 2 chữ số" required>
                        </div>
                        <div id="loading-spinner" class="kira-loading" style="display: none;">
                            <img src="<?php echo plugin_dir_url(__FILE__) . '/assets/img/ball.gif'; ?>" alt="Loading gif">
                        </div>
                        <div id="form-message"></div>
                        <div class="kira-smt" id="kira-submit">
                            <button type="submit" class="kira-btn"><img width="35px" src="<?php echo plugin_dir_url(__FILE__) . '/assets/img/xoso.webp'; ?>" alt="icon xổ số">Gửi kết quả</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Popup for kira-top -->
        <div class="popup-top-overlay">
            <div class="popup-content">
                <span class="popup-top-close">&times;</span>
                <div class="kira-top">
                    <ul class="tab">
                        <li><a href="#this-week" class="tablinks active" onclick="openTab(event, 'this-week')">Tuần này</a></li>
                        <li><a href="#last-week" class="tablinks" onclick="openTab(event, 'last-week')">Tuần trước</a></li>
                        <li><a href="#this-month" class="tablinks" onclick="openTab(event, 'this-month')">Tháng này</a></li>
                        <li><a href="#last-month" class="tablinks" onclick="openTab(event, 'last-month')">Tháng trước</a></li>
                        <li><a href="#year" class="tablinks" onclick="openTab(event, 'year')">Năm</a></li>
                    </ul>
                    <div id="this-week" class="tabcontent">
                        <ul id="top-this-week"></ul>
                    </div>
                    <div id="last-week" class="tabcontent">
                        <ul id="top-last-week"></ul>
                    </div>
                    <div id="this-month" class="tabcontent">
                        <ul id="top-this-month"></ul>
                    </div>
                    <div id="last-month" class="tabcontent">
                        <ul id="top-last-month"></ul>
                    </div>
                    <div id="year" class="tabcontent">
                        <ul id="top-year"></ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Popup for kira-stats -->
        <div class="popup-stats-overlay">
            <div class="popup-content">
                <span class="popup-stats-close">&times;</span>
                <div class="kira-stats">
                    <form id="kira-stats-form" class="kira-form" method="post">
                        <p class="text-center"><strong>THỐNG KÊ</strong></p>
                        <div class="kira-form-group">
                            <label for="stats_tele_id">Telegram:</label>
                            <input type="text" id="stats_tele_id" class="kira-inp" name="stats_tele_id" placeholder="Điền Telegram username" required>
                        </div>
                        <div class="kira-smt">
                            <button id="kira-stat-btn" type="submit" class="kira-btn">Xem Thống Kê</button>
                        </div>
                    </form>
                    <div id="loading-spinner-stat" class="kira-loading -stat" style="display: none;">
                        <img src="<?php echo plugin_dir_url(__FILE__) . '/assets/img/ball.gif'; ?>" alt="Loading gif">
                    </div>
                    <div id="stats-result" style="display:none;">
                        <p><b>Kết Quả Thống Kê</b></p>
                        <div class="stat-area">
                            <p id="stats-this-week"></p>
                            <p id="stats-last-week"></p>
                            <p id="stats-this-month"></p>
                            <p id="stats-last-month"></p>
                            <p id="stats-year" class="text-center"></p>
                        </div>
                        <div>
                            <p><b>Lịch sử dự đoán gần đây</b></p>
                            <ul id="stat-history">
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
    return ob_get_clean();
}
add_shortcode('kira_render_form', 'kira_render_form');
// Handle AJAX request for top stats
function get_top_stats()
{
    check_ajax_referer('api_form_nonce', 'nonce');

    $api_url = "https://lotterysoez.xyz/api/rating/all";

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error('API request failed.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Invalid JSON response from API.');
    }

    wp_send_json_success($data);
}
add_action('wp_ajax_get_top_stats', 'get_top_stats');
add_action('wp_ajax_nopriv_get_top_stats', 'get_top_stats');

// Handle AJAX request for stats
function get_user_stats()
{
    check_ajax_referer('api_form_nonce', 'nonce');

    $tele_id = sanitize_text_field($_POST['tele_id']);
    $api_url = "https://lotterysoez.xyz/api/rating?tele_id=" . urlencode($tele_id);

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error('API request failed.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error('Invalid JSON response from API.');
    }

    wp_send_json_success($data);
}
add_action('wp_ajax_get_user_stats', 'get_user_stats');
add_action('wp_ajax_nopriv_get_user_stats', 'get_user_stats');

// Enqueue script
// Enqueue script
function enqueue_api_form_scripts()
{
    $ver = '1.0.1';
    wp_enqueue_style('kira-lottery-style', plugin_dir_url(__FILE__) . '/assets/css/style.css?ver=' . $ver);
    wp_enqueue_script('kira-ajax-script', plugin_dir_url(__FILE__) . '/assets/js/ajax.js?ver=' . $ver, array('jquery'), null, true);
    wp_enqueue_script('kira-main-script', plugin_dir_url(__FILE__) . '/assets/js/main.js?ver=' . $ver, array('jquery'), null, true);

    wp_localize_script('kira-main-script', 'apiForm', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('api_form_nonce')
    ));
    wp_localize_script('kira-ajax-script', 'apiForm', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('api_form_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_api_form_scripts');


// Handle AJAX request
function handle_api_form_submission()
{
    check_ajax_referer('api_form_nonce', 'nonce');

    $tele_id = sanitize_text_field($_POST['tele_id']);
    $special_result = sanitize_text_field($_POST['special_result']);
    $medium_result_1 = sanitize_text_field($_POST['medium_result_1']);
    $medium_result_2 = sanitize_text_field($_POST['medium_result_2']);
    $medium_result_3 = sanitize_text_field($_POST['medium_result_2']);

    if ((!preg_match('/^\d{2}$/', $special_result)) || !preg_match('/^\d{2}$/', $medium_result_1)
        || !preg_match('/^\d{2}$/', $medium_result_2) || !preg_match('/^\d{2}$/', $medium_result_3)
    ) {
        wp_send_json_error('Kết quả dự đoán là số có ' . $tele_id . ' chữ số');
    }
    $api_url = 'https://lotterysoez.xyz/api/predicted-results';
    $response = wp_remote_post($api_url, array(
        'body' => json_encode(array(
            'tele_id' => $tele_id,
            'special_result' => $special_result,
            'medium_result_1' => $medium_result_1,
            'medium_result_2' => $medium_result_2,
            'medium_result_3' => $medium_result_3,
            'site' => get_home_url()

        )),
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    ));
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (isset($data['error']) && $data['error']['status'] = 400) {
        wp_send_json_error($data['error']['details']);
    }
    if (is_wp_error($response)) {
        wp_send_json_error('Vui lòng thử lại');
    } else {
        wp_send_json_success('Đã gửi kết quả');
    }
}
add_action('wp_ajax_submit_api_form', 'handle_api_form_submission');
add_action('wp_ajax_nopriv_submit_api_form', 'handle_api_form_submission');
