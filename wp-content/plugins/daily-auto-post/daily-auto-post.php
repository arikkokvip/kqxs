<?php
/*
Plugin Name: Okvip Daily Post Lottery 
Description: Okvip Lottery Tự động đăng bài hàng ngày vào 18 giờ.
Version: 1.1.4
Author: Arikk
*/

register_activation_hook(__FILE__, 'dps_activate');
function dps_activate()
{
    // Tính toán thời gian 18h UTC+7 trong múi giờ UTC+0
    $timestamp = strtotime('today 18:00:00') - (7 * 3600);

    if (!wp_next_scheduled('dps_daily_post_event')) {
        wp_schedule_event($timestamp, 'daily', 'dps_daily_post_event');
    }
}
// function dps_activate()
// {
//     if (!wp_next_scheduled('dps_daily_post_event')) {
//         wp_schedule_event(time(), 'every_minute', 'dps_daily_post_event');
//     }
// }

register_deactivation_hook(__FILE__, 'dps_deactivate');
function dps_deactivate()
{
    wp_clear_scheduled_hook('dps_daily_post_event');
}
add_action('dps_daily_post_event', 'dps_create_daily_posts');

function dps_create_daily_posts()
{
    $current_date = date('d-m-Y');
    // $current_date = date('25-06-2024');

    $api_urls = array(
        "https://api.xosoaladin.com/api/v1/kqxs/xsmt/$current_date", // Miền Trung
        "https://api.xosoaladin.com/api/v1/kqxs/xsmb/$current_date", // Miền Bắc
        "https://api.xosoaladin.com/api/v1/kqxs/xsmn/$current_date"  // Miền Nam
    );
    $settings = get_option('dps_settings', array());


    // $category_name = 'Kết quả hàng ngày';
    // $category_id = get_cat_ID($category_name);

    // if ($category_id == 0) {
    //     $category_id = wp_create_category($category_name);
    // }

    foreach ($api_urls as $api_url) {
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            continue;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data['isSuccessed']) {
            continue;
        }
        // var_dump($settings);

        foreach ($data['resultObj'] as $province) {
            $province_name_no_sign = $province['provinceNameNoSign'];
            if (isset($settings[$province_name_no_sign]) && isset($settings[$province_name_no_sign]['enabled']) && $settings[$province_name_no_sign]['enabled']) {
                var_dump($settings[$province_name_no_sign]);
                $province_name = $province['provinceName'];
                $results = $province['listXSTT'];
                $resultHead = $province['resultHead'];
                $resultEnd = $province['resultEnd'];
                $results = $province['listXSTT'];
                if ($settings[$province_name_no_sign]['category']) {
                    $category_name = $settings[$province_name_no_sign]['category'];
                } else {
                    $category_name = "Kết quả hàng ngày";
                }

                // Kiểm tra xem category đã tồn tại chưa
                $category_id = get_cat_ID($category_name);
                if ($category_id == 0) {
                    $category = wp_insert_term($category_name, 'category');
                    if (!is_wp_error($category)) {
                        $category_id = $category['term_id'];
                    } else {
                        error_log('Failed to create category: ' . $category->get_error_message());
                        continue;
                    }
                }
                $post_title = "Kết quả xổ số $province_name ngày $current_date";
                $query = new WP_Query(array(
                    'post_type'   => 'post',
                    'post_status' => 'publish',
                    'title'       => $post_title,
                    'fields'      => 'ids'
                ));

                if ($query->have_posts()) {
                    continue;
                }
                ob_start();
                include plugin_dir_path(__FILE__) . 'post-template.php';
                $post_content = get_lottery_result_template_plugin($province_name, $current_date, $results, $resultHead, $resultEnd);
                ob_end_clean();
                // Lấy ảnh từ gallery
                $gallery = isset($settings[$province_name_no_sign]['gallery']) ? explode(', ', $settings[$province_name_no_sign]['gallery']) : array();
                $default_image = isset($settings[$province_name_no_sign]['image']) ? $settings[$province_name_no_sign]['image'] : 'path/to/default/image.jpg';
                $gallery_position = isset($settings[$province_name_no_sign]['gallery_position']) ? intval($settings[$province_name_no_sign]['gallery_position']) : 0;

                if (!empty($gallery)) {
                    $featured_image = $gallery[$gallery_position % count($gallery)];
                    $gallery_position = ($gallery_position + 1) % count($gallery);
                    $settings[$province_name_no_sign]['gallery_position'] = $gallery_position;
                    update_option('dps_settings', $settings);
                } else {
                    $featured_image = $default_image;
                }
                $post_data = array(
                    'post_title'    => $post_title,
                    'post_content'  => $post_content,
                    'post_status'   => 'publish',
                    'post_author'   => 1,
                    'post_category' => array($category_id),
                );

                $post_id = wp_insert_post($post_data);

                // Đặt tiêu đề và mô tả SEO
                if ($post_id) {
                    $image_id = attachment_url_to_postid($featured_image);
                    set_post_thumbnail($post_id, $image_id);
                    
                    update_post_meta($post_id, 'rank_math_title', $post_title);
                    $seo_description = "Kết quả xổ số $province_name ngày $current_date. Xem kết quả chi tiết tại " . esc_url(home_url());
                    update_post_meta($post_id, 'rank_math_description', $seo_description);

                    $seo_keywords = "kết quả xổ số, $province_name, xổ số $province_name, $current_date";
                    update_post_meta($post_id, 'rank_math_focus_keyword', $seo_keywords);

                    $seo_focus_keyword = "ket qua xo so $province_name ngay $current_date";
                    update_post_meta($post_id, 'rank_math_focus_keyword', $seo_focus_keyword);
                }
            }
        }
    }
}

add_action('wp_enqueue_scripts', 'dps_enqueue_styles');
function dps_enqueue_styles()
{
    wp_enqueue_style('dps-styles', plugin_dir_url(__FILE__) . 'daily-post-scheduler.css?ver=1.0.2');
}
function dps_admin_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script('dps-admin-script', plugin_dir_url(__FILE__) . 'dps-admin.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'dps_admin_scripts');

add_action('admin_menu', 'dps_add_admin_menu');
add_action('admin_init', 'dps_settings_init');

function dps_add_admin_menu()
{
    add_options_page('Daily Post Scheduler', 'Daily Post Scheduler', 'manage_options', 'daily_post_scheduler', 'dps_options_page');
}

function dps_settings_init()
{
    register_setting('pluginPage', 'dps_settings');

    add_settings_section(
        'dps_pluginPage_section',
        __('Chọn các tỉnh để đăng bài và tên chuyên mục', 'wordpress'),
        'dps_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'dps_provinces_categories',
        __('Các tỉnh và chuyên mục', 'wordpress'),
        'dps_provinces_categories_render',
        'pluginPage',
        'dps_pluginPage_section'
    );
}

function dps_provinces_categories_render()
{
    // dps_create_daily_posts();
    $options = get_option('dps_settings', array());
    $provinces = [
        ['_id' => 1, 'name' => 'Miền Bắc', 'region' => 1, 'nameNoSign' => 'mien-bac'],
        ['_id' => 33, 'name' => 'TP. HCM', 'region' => 3, 'nameNoSign' => 'hcm'],
        ['_id' => 16, 'name' => 'An Giang', 'region' => 3, 'nameNoSign' => 'an-giang'],
        ['_id' => 19, 'name' => 'Bình Dương', 'region' => 3, 'nameNoSign' => 'binh-duong'],
        ['_id' => 17, 'name' => 'Bạc Liêu', 'region' => 3, 'nameNoSign' => 'bac-lieu'],
        ['_id' => 20, 'name' => 'Bình Phước', 'region' => 3, 'nameNoSign' => 'binh-phuoc'],
        ['_id' => 18, 'name' => 'Bến Tre', 'region' => 3, 'nameNoSign' => 'ben-tre'],
        ['_id' => 21, 'name' => 'Bình Thuận', 'region' => 3, 'nameNoSign' => 'binh-thuan'],
        ['_id' => 22, 'name' => 'Cà Mau', 'region' => 3, 'nameNoSign' => 'ca-mau'],
        ['_id' => 23, 'name' => 'Cần Thơ', 'region' => 3, 'nameNoSign' => 'can-tho'],
        ['_id' => 37, 'name' => 'Đà Lạt', 'region' => 3, 'nameNoSign' => 'da-lat'],
        ['_id' => 25, 'name' => 'Đồng Nai', 'region' => 3, 'nameNoSign' => 'dong-nai'],
        ['_id' => 26, 'name' => 'Đồng Tháp', 'region' => 3, 'nameNoSign' => 'dong-thap'],
        ['_id' => 27, 'name' => 'Hậu Giang', 'region' => 3, 'nameNoSign' => 'hau-giang'],
        ['_id' => 28, 'name' => 'Kiên Giang', 'region' => 3, 'nameNoSign' => 'kien-giang'],
        ['_id' => 29, 'name' => 'Long An', 'region' => 3, 'nameNoSign' => 'long-an'],
        ['_id' => 30, 'name' => 'Sóc Trăng', 'region' => 3, 'nameNoSign' => 'soc-trang'],
        ['_id' => 32, 'name' => 'Tiền Giang', 'region' => 3, 'nameNoSign' => 'tien-giang'],
        ['_id' => 31, 'name' => 'Tây Ninh', 'region' => 3, 'nameNoSign' => 'tay-ninh'],
        ['_id' => 34, 'name' => 'Trà Vinh', 'region' => 3, 'nameNoSign' => 'tra-vinh'],
        ['_id' => 35, 'name' => 'Vĩnh Long', 'region' => 3, 'nameNoSign' => 'vinh-long'],
        ['_id' => 36, 'name' => 'Vũng Tàu', 'region' => 3, 'nameNoSign' => 'vung-tau'],
        ['_id' => 2, 'name' => 'Bình Định', 'region' => 2, 'nameNoSign' => 'binh-dinh'],
        ['_id' => 4, 'name' => 'Đắk Lắk', 'region' => 2, 'nameNoSign' => 'dac-lac'],
        ['_id' => 3, 'name' => 'Đà Nẵng', 'region' => 2, 'nameNoSign' => 'da-nang'],
        ['_id' => 5, 'name' => 'Đắk Nông', 'region' => 2, 'nameNoSign' => 'dac-nong'],
        ['_id' => 6, 'name' => 'Gia Lai', 'region' => 2, 'nameNoSign' => 'gia-lai'],
        ['_id' => 8, 'name' => 'Khánh Hòa', 'region' => 2, 'nameNoSign' => 'khanh-hoa'],
        ['_id' => 9, 'name' => 'Kon Tum', 'region' => 2, 'nameNoSign' => 'kon-tum'],
        ['_id' => 10, 'name' => 'Ninh Thuận', 'region' => 2, 'nameNoSign' => 'ninh-thuan'],
        ['_id' => 11, 'name' => 'Phú Yên', 'region' => 2, 'nameNoSign' => 'phu-yen'],
        ['_id' => 12, 'name' => 'Quảng Bình', 'region' => 2, 'nameNoSign' => 'quang-binh'],
        ['_id' => 14, 'name' => 'Quảng Ngãi', 'region' => 2, 'nameNoSign' => 'quang-ngai'],
        ['_id' => 13, 'name' => 'Quảng Nam', 'region' => 2, 'nameNoSign' => 'quang-nam'],
        ['_id' => 15, 'name' => 'Quảng Trị', 'region' => 2, 'nameNoSign' => 'quang-tri'],
        ['_id' => 7, 'name' => 'Thừa Thiên Huế', 'region' => 2, 'nameNoSign' => 'hue'],
    ];
    $regions = [
        1 => 'Miền Bắc',
        2 => 'Miền Trung',
        3 => 'Miền Nam'
    ];

    foreach ($regions as $region_id => $region_name) {
        echo "<h3>$region_name</h3>";
        echo '<table>';
        echo '<tr><th>' . __('Tỉnh', 'wordpress') . '</th><th>' . __('Tên chuyên mục', 'wordpress') . '</th></tr>';
        foreach ($provinces as $province) {
            if ($province['region'] == $region_id) {
                $province_name_no_sign = $province['nameNoSign'];
                $province_name = $province['name'];
                $checked = isset($options[$province_name_no_sign]['enabled']) ? 'checked' : '';
                $category_value = isset($options[$province_name_no_sign]['category']) ? esc_attr($options[$province_name_no_sign]['category']) : '';
                $image_value = isset($options[$province_name_no_sign]['image']) ? esc_attr($options[$province_name_no_sign]['image']) : '';
                $gallery_value = isset($options[$province_name_no_sign]['gallery']) ? esc_attr($options[$province_name_no_sign]['gallery']) : '';
                echo '<tr>';
                echo '<td><label><input type="checkbox" name="dps_settings[' . $province_name_no_sign . '][enabled]" value="1" ' . $checked . ' /> ' . $province_name . '</label></td>';
                echo '<td><input type="text" name="dps_settings[' . $province_name_no_sign . '][category]" value="' . $category_value . '" placeholder="' . __('Tên chuyên mục', 'wordpress') . '" /></td>';
                echo '<td>
                        <input type="text" name="dps_settings[' . $province_name_no_sign . '][image]" value="' . $image_value . '" placeholder="' . __('Chọn ảnh', 'wordpress') . '" />
                        <button class="upload_image_button button">' . __('Chọn ảnh', 'wordpress') . '</button>
                      </td>';
                echo '<td>
                        <input type="text" name="dps_settings[' . $province_name_no_sign . '][gallery]" value="' . $gallery_value . '" placeholder="' . __('Chọn gallery', 'wordpress') . '" />
                        <button class="upload_gallery_button button">' . __('Chọn gallery', 'wordpress') . '</button>
                      </td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }
}


function dps_settings_section_callback()
{
    echo __('Chọn các tỉnh mà bạn muốn đăng bài tự động và nhập tên chuyên mục tương ứng.', 'wordpress');
}

function dps_options_page()
{
?>
    <form action='options.php' method='post'>
        <h2>Daily Post Scheduler</h2>
        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>
    </form>
<?php
}
?>