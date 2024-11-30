<?php

/**
 * xosoarik functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package xosoarik
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.5');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
require get_template_directory() . '/inc/widgets/class-province-widget.php';
require get_template_directory() . '/inc/widgets/class-pre-result-lottery-widget.php';
require get_template_directory() . '/inc/widgets/class-statistic-widget.php';

// Đăng ký Widget
function registe_widget_custom()
{
	register_widget('Province_Widget');
	register_widget('Pre_Result_Lottery_Widget');
	register_widget('Statistic_Widget');
}
add_action('widgets_init', 'registe_widget_custom');
function add_frontend_ajax_javascript_file()
{
	wp_enqueue_script('frontend-ajax', get_template_directory_uri() . '/js/frontend-ajax.js?ver=1.0.8', array('jquery'), null, true);
	wp_localize_script('frontend-ajax', 'frontendajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'add_frontend_ajax_javascript_file');

// Khai báo css
function theme_add_styles()
{
	wp_enqueue_style('widget-style', get_template_directory_uri() . '/assets/css/widget.css');
	wp_enqueue_style('table-style', get_template_directory_uri() . '/assets/css/table.css');
}

add_action('wp_enqueue_scripts', 'theme_add_styles');


function xosoarik_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on xosoarik, use a find and replace
		* to change 'xosoarik' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('xosoarik', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support('title-tag');

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'xosoarik'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'xosoarik_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'xosoarik_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function xosoarik_content_width()
{
	$GLOBALS['content_width'] = apply_filters('xosoarik_content_width', 640);
}
add_action('after_setup_theme', 'xosoarik_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function xosoarik_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'xosoarik'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'xosoarik'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'xosoarik_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function xosoarik_scripts()
{
	wp_enqueue_style('xosoarik-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('xosoarik-style', 'rtl', 'replace');

	wp_enqueue_script('xosoarik-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'xosoarik_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}
function find_page_by_title($page_title)
{
	$args = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'title' => $page_title,
		'posts_per_page' => 1,
	);

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			return get_post();
		}
	}

	return null;
}
function create_lottery_pages_and_set_menu()
{
	$menu_name = 'Main Menu';
	$location = 'primary'; // Tên vị trí menu mà bạn muốn gán
	$menu_exists = wp_get_nav_menu_object($menu_name);

	// Nếu menu chưa tồn tại, tạo mới
	if (!$menu_exists) {
		$menu_id = wp_create_nav_menu($menu_name);
	} else {
		// Nếu menu đã tồn tại, sử dụng ID của nó
		$menu_id = $menu_exists->term_id;
		// Xóa sạch các item trong menu hiện có
		wp_delete_nav_menu_items($menu_id);
	}

	$pages = [
		['title' => 'Trang Chủ', 'content' => '', 'homepage' => true],
		['title' => 'Xổ số Miền Bắc', 'content' => '', 'template' => 'page-xsmb.php'],
		['title' => 'Xổ số Miền Trung', 'content' => '', 'template' => 'page-xsmt.php'],
		['title' => 'Xổ số Miền Nam', 'content' => '', 'template' => 'page-xsmn.php'],
	];

	foreach ($pages as $page) {
		// Sử dụng WP_Query để tìm trang theo tiêu đề
		$query = new WP_Query(array(
			'post_type' => 'page',
			'post_status' => 'publish',
			'title' => $page['title'],
			'posts_per_page' => 1
		));

		if (!$query->have_posts()) {
			$page_id = wp_insert_post([
				'post_title' => $page['title'],
				'post_content' => $page['content'],
				'post_status' => 'publish',
				'post_type' => 'page',
			]);

			if (!empty($page['template'])) {
				update_post_meta($page_id, '_wp_page_template', $page['template']);
			}

			if (!empty($page['homepage'])) {
				update_option('page_on_front', $page_id);
				update_option('show_on_front', 'page');
			}
		} else {
			$posts = $query->get_posts();
			$page_id = $posts[0]->ID; // Lấy ID của trang nếu nó đã tồn tại
		}

		wp_update_nav_menu_item($menu_id, 0, array(
			'menu-item-title' => $page['title'],
			'menu-item-object' => 'page',
			'menu-item-object-id' => $page_id,
			'menu-item-type' => 'post_type',
			'menu-item-status' => 'publish',
		));

		wp_reset_postdata(); // Đặt lại query data
	}

	$locations = get_theme_mod('nav_menu_locations');
	$locations[$location] = $menu_id;
	set_theme_mod('nav_menu_locations', $locations);
}

add_action('after_switch_theme', 'create_lottery_pages_and_set_menu');


// Hàm hỗ trợ xóa tất cả menu items
function wp_delete_nav_menu_items($menu_id)
{
	$menu_items = wp_get_nav_menu_items($menu_id);
	foreach ($menu_items as $menu_item) {
		wp_delete_post($menu_item->ID, true);
	}
}



function insert_custom_data_into_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'provinces';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
		_id smallint(5) NOT NULL UNIQUE,
        name varchar(255) NOT NULL,
        region smallint(5) NOT NULL,
        nameNoSign varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	$data_to_insert = [
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


	foreach ($data_to_insert as $item) {
		$wpdb->insert(
			$table_name,
			array(
				'_id' => $item['_id'],
				'name' => $item['name'],
				'region' => $item['region'],
				'nameNoSign' => $item['nameNoSign']
			)
		);
	}
}

add_action('after_switch_theme', 'insert_custom_data_into_table');

function mytheme_custom_logo_setup()
{
	$defaults = array(
		'height'      => 100,
		'width'       => 400, // Chiều rộng mặc định
		'flex-height' => true, // Cho phép thay đổi chiều cao
		'flex-width'  => true, // Cho phép thay đổi chiều rộng
		'header-text' => array('site-title', 'site-description'),
	);
	add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'mytheme_custom_logo_setup');


//Call API
function get_lottery_results($date = null, $region = 'xsmb')
{
	if ($date === null) {
		$date = date('d-m-Y');
	}

	$api_url = 'https://api.xosoaladin.com/api/v1/kqxs/' . $region . '/' . $date;
	$response = wp_remote_get($api_url);

	if (is_wp_error($response)) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	if ($data && $data['isSuccessed'] && !empty($data['resultObj'])) {
		$results = $data['resultObj'];

		return $results;
	} else {
		$prev_date = date('d-m-Y', strtotime('-1 day', strtotime($date)));
		return get_lottery_results($prev_date, $region);
	}
}
function load_lottery_results()
{
	global $results;
	$date = $_POST['date'] ?? date('d-m-Y');
	$region = $_POST['region'] ?? 'xsmb';
	$results = get_lottery_results($date, $region);
	if ($results) {
		ob_start();
		switch ($region) {
			case 'xsmb':
				include(locate_template('template-parts/front-page/kqxsmb.php'));
				break;
			case 'xsmt':
				include(locate_template('template-parts/front-page/kqxsmt.php'));
				break;
			case 'xsmn':
				include(locate_template('template-parts/front-page/kqxsmn.php'));
				break;
		}
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_results', 'load_lottery_results');
add_action('wp_ajax_load_lottery_results', 'load_lottery_results');

function get_lottery_results_by_province($date = null, $provinceId = 1)
{
	if ($date) {
		$api_url = 'https://api.xosoaladin.com/api/v1/kqxs/xsTinh?provinceId=' . $provinceId . '&date=' . $date;
	} else {
		$api_url = 'https://api.xosoaladin.com/api/v1/kqxs/xsTinh?provinceId=' . $provinceId;
	}
	$response = wp_remote_get($api_url);

	if (is_wp_error($response)) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	return $data;
}
function load_lottery_results_province()
{
	global $results;
	$date = $_POST['date'] ?? date('d-m-Y');
	$provinceId = $_POST['provinceId'] ?? '1';

	$results = get_lottery_results_by_province($date, $provinceId);

	if ($results) {
		ob_start();
		include(locate_template('template-parts/kqxstinh.php'));

		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_results_province', 'load_lottery_results_province');
add_action('wp_ajax_load_lottery_results_province', 'load_lottery_results_province');
function dropdown_province()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'provinces';

	$provinces = $wpdb->get_results("SELECT * FROM $table_name");

	echo '<div class="dropdown d-inline-block dropdown-province">
				<button class="btn btn-outline-secondary dropdown-toggle" type="button" id="provinceList" data-bs-toggle="dropdown" aria-expanded="false">
				Tỉnh
			</button>
            <ul class="dropdown-menu" aria-labelledby="provinceList">';
	foreach ($provinces as $province) {

		echo "<li><a class='dropdown-item' href='" . get_home_url() . '/tinh' . '/' . $province->nameNoSign . "'> {$province->name}</a></li>";
	}

	echo "</ul>
        </div>";
}
function select_province($atts)
{
	global $wpdb;
	$atts = shortcode_atts(array(
        'province' => '', // Giá trị mặc định của tham số 'province'
    ), $atts, 'select_province');
	$province_select = $atts['province'];

	$table_name = $wpdb->prefix . 'provinces';

	$provinces = $wpdb->get_results("SELECT * FROM $table_name");

	echo '<select class="btn btn-outline-secondary" name="province" id="province">';
	foreach ($provinces as $province) {

		echo '<option value="'.$province->nameNoSign.'" ';
		if($province_select == $province->nameNoSign){
			echo 'selected';
		}
		echo ' >'.$province->name.'</option>';
	}

	echo "</select>";
}

add_shortcode('dropdown_province', 'dropdown_province');
add_shortcode('select_province', 'select_province');

// Get STT giải
function convert_number_to_prize_name($number)
{
	switch ($number) {
		case 1:
			return 'Đặc biệt';
		case 2:
			return 'Giải nhất';
		case 3:
			return 'Giải nhì';
		case 4:
			return 'Giải ba';
		case 5:
			return 'Giải tư';
		case 6:
			return 'Giải năm';
		case 7:
			return 'Giải sáu';
		case 8:
			return 'Giải bảy';
		case 9:
			return 'Giải tám';
		default:
			return 'Không xác định';
	}
}

function get_day_of_week_VN($date)
{
	$daysOfWeekVietnamese = array(
		1 => 'Thứ Hai',
		2 => 'Thứ Ba',
		3 => 'Thứ Tư',
		4 => 'Thứ Năm',
		5 => 'Thứ Sáu',
		6 => 'Thứ Bảy',
		7 => 'Chủ Nhật',
	);
	$timestamp = strtotime($date);
	$dayOfWeek = date('N', $timestamp);

	return $daysOfWeekVietnamese[$dayOfWeek];
}
//Thêm widget sidebar & footer
function register_widget_location()
{
	register_sidebar(
		array(
			'id'            => 'right_sidebar',
			'name'          => __('Sidebar phải', 'kqxs_arik'),
			'description'   => __('Sidebar phải.', 'kqxs_arik'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)

	);
	register_sidebar(
		array(
			'id'            => 'footer_widget_location',
			'name'          => __('Footer', 'kqxs_arik'),
			'description'   => __('Footer', 'kqxs_arik'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'register_widget_location');

//custom rule url tinh
// function custom_rewrite_rule()
// {
// 	add_rewrite_rule('^tinh/([^/]*)/?', 'index.php?tinh=$matches[1]', 'top');
// }
// add_action('init', 'custom_rewrite_rule', 10, 0);
// function flush_rewrite_rules_on_theme_activation() {
//     add_rewrite_rule('^tinh/([^/]*)/?', 'index.php?tinh=$matches[1]', 'top');
//     flush_rewrite_rules();
// }
// add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_activation');
// function custom_query_vars($vars)
// {
// 	$vars[] = 'tinh'; // Tên của query var mới
// 	return $vars;
// }
// add_filter('query_vars', 'custom_query_vars');
// function load_custom_template($template)
// {
// 	if (get_query_var('tinh') != '') {
// 		$new_template = locate_template(array('page-xstinh.php')); // xsmt-template.php là template bạn muốn sử dụng
// 		if ('' != $new_template) {
// 			return $new_template;
// 		}
// 	}
// 	return $template;
// }
// add_filter('template_include', 'load_custom_template');

function get_province_id_by_name_no_sign($nameNoSign)
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'provinces';

	$query = $wpdb->prepare("SELECT _id FROM $table_name WHERE nameNoSign = %s", $nameNoSign);

	$id = $wpdb->get_var($query);

	if (!empty($id)) {
		return $id;
	} else {
		return null;
	}
}
function get_province_name_by_name_no_sign($nameNoSign)
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'provinces';

	$query = $wpdb->prepare("SELECT name FROM $table_name WHERE nameNoSign = %s", $nameNoSign);

	$name = $wpdb->get_var($query);

	if (!empty($name)) {
		return $name;
	} else {
		return null;
	}
}
function sidebar_widget_province()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'provinces';

	$provinces = $wpdb->get_results("SELECT * FROM $table_name");

	echo '<div class="sidebar-widget"><p class="sidebar-widget-title">Theo tỉnh</p><ul>';
	foreach ($provinces as $province) {
		echo "<li class='sidebar-item'><a  href='" . get_home_url() . '/tinh' . '/' . $province->nameNoSign . "'><i class='fas fa-chevron-right'></i> {$province->name}</a></li>";
	}

	echo "</ul></div>";
}
add_shortcode('sidebar_widget_province', 'sidebar_widget_province');
function sidebar_widget_region()
{
	$regions = array('xo-so-mien-bac' => 'Xổ số Miền Bắc', 'xo-so-mien-trung' => ' Xổ số Miền Trung', 'xo-so-mien-nam' => ' Xổ số Miền Nam');
	echo '<div class="sidebar-widget"><p class="sidebar-widget-title">Theo miền</p><ul>';
	foreach ($regions as $slug => $region_name) {
		echo "<li class='sidebar-item'><a href='" . get_home_url() . '/' . $slug . "'><i class='fas fa-chevron-right'></i> {$region_name}</a></li>";
	}
	echo "</ul></div>";
}
add_shortcode('sidebar_widget_region', 'sidebar_widget_region');
function add_roboto_font()
{
	wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap&subset=vietnamese', false);
}
add_action('wp_enqueue_scripts', 'add_roboto_font');
function create_province_post_type()
{
	register_post_type(
		'province',
		array(
			'labels'      => array(
				'name'          => __('Tỉnh'),
				'singular_name' => __('Tỉnh'),
			),
			'public'      => true,
			'has_archive' => true,
			'rewrite'     => array('slug' => 'tinh'),
			'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
		)
	);
}
add_action('init', 'create_province_post_type');

// Đảm bảo flush rewrite rules khi kích hoạt theme
function custom_theme_activation()
{
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'custom_theme_activation');
function create_province_posts_from_db()
{
	global $wpdb; // Biến toàn cục cho việc truy xuất cơ sở dữ liệu

	// Lấy tất cả dữ liệu từ bảng provinces
	$provinces = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}provinces");

	foreach ($provinces as $province) {
		// Kiểm tra xem đã có bài viết với slug tương ứng chưa
		$existing_post = get_page_by_path($province->nameNoSign, OBJECT, 'province');
		if (null == $existing_post) {
			// Tạo một bài viết mới nếu chưa tồn tại
			$post_data = array(
				'post_title'    => wp_strip_all_tags($province->name),
				'post_content'  => '',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'province',
				'post_name'     => $province->nameNoSign
			);

			$post_id = wp_insert_post($post_data);
		}
	}
}

add_action('after_switch_theme', 'create_province_posts_from_db');
function flush_rewrite_rules_on_theme_activation()
{
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_activation');

function callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, $option)
{
	$url = 'https://api.xosoaladin.com/api/v1/statistic';

	$body = json_encode(array(
		'numberOfDate' => $numberOfDate,
		'lastDate' => $lastDate,
		'coupleOfNumber'  => $coupleOfNumber,
		'option' => $option,
		'groupBy' => '0-99'
	));
	$args = array(
		'method'      => 'POST',
		'timeout'     => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking'    => true,
		'headers'     => [
			'Content-Type' => 'application/json'
		],
		'body'        => $body,
		'cookies'     => array()
	);

	$response = wp_remote_post($url, $args);
	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
		return null;
	} else {
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		return $data;
	}
}

function load_lottery_statistic()
{
	global $results;
	$lastDate = $_POST['lastDate'] ?? date('d-m-Y');
	$numberOfDate = $_POST['numberOfDate'] ?? '30';
	$coupleOfNumber = $_POST['coupleOfNumber'] ?? '';
	$option = $_POST['option'] ?? 'lt';
	$results = callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, $option);
	// wp_send_json_success($results);

	if ($results) {
		$results['group_number'] = array();
		if ($coupleOfNumber == '') {
			$results['group_number'] = $results['statistic'];
		} else {
			foreach ($results['statistic'][0] as $item) {
				$loto_number = $item['loto'];
				$results['group_number'][$loto_number][] = $item;
			}
		}

		ob_start();
		include(locate_template('template-parts/statistic/results.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_statistic', 'load_lottery_statistic');
add_action('wp_ajax_load_lottery_statistic', 'load_lottery_statistic');
function load_lottery_gan_statistic()
{
	global $results;
	$lastDate = $_POST['lastDate'] ?? date('d-m-Y');
	$numberOfDate = $_POST['numberOfDate'] ?? '30';
	$coupleOfNumber = $_POST['coupleOfNumber'] ?? '';
	$results = callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, 'lt');


	if ($results) {
		$results['group_number'] = array();
		$grouped_data = [];
		if ($coupleOfNumber == '') {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					$grouped_data[$dayPrize][] = $item;
				}
			}

			foreach ($grouped_data as $dayPrize => &$data) {
				$prizeGroups = [];

				foreach ($data as $item) {
					$prizeId = $item['prizeId'];
					$loto = $item['loto'];

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = [];
					}

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = ['count' => 0, 'is_db' => false];
					}
					$prizeGroups[$loto]['count']++;
					if ($prizeId === 1) {
						$prizeGroups[$loto]['is_db'] = true;
					}
				}

				$data = $prizeGroups;
			}
		} else {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];
					$loto = $item['loto'];
					$prizeId = $item['prizeId'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					if (!isset($result[$dayPrize][$loto])) {
						$grouped_data[$dayPrize][$loto] = ['count' => 0, 'is_db' => false];
					}

					$grouped_data[$dayPrize][$loto]['count']++;

					if ($prizeId === 1) {
						$result[$dayPrize][$loto]['is_db'] = true;
					}
				}
			}
		}


		$results = $grouped_data;

		ob_start();
		include(locate_template('template-parts/statistic/gan.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_gan_statistic', 'load_lottery_gan_statistic');
add_action('wp_ajax_load_lottery_gan_statistic', 'load_lottery_gan_statistic');
function get_province_for_today()
{
	$current_day = strtolower(date('D')); // Lấy ngày hiện tại (T2, T3, ...)
	$province_schedule = array(
		'sun' => array('miền bắc', 'kon tum', 'khánh hòa', 'huế', 'tiền giang', 'kiên giang', 'đà lạt'),
		'mon' => array('miền bắc', 'huế', 'phú yên', 'đồng tháp', 'tp hcm', 'cà mau'),
		'tue' => array('miền bắc', 'dak lak', 'quảng nam', 'vũng tàu', 'bến tre', 'bạc liêu'),
		'wed' => array('miền bắc', 'khánh hòa', 'đà nẵng', 'cần thơ', 'sóc trăng', 'đồng nai'),
		'thu' => array('miền bắc', 'bình định', 'quảng bình', 'quảng trị', 'an giang', 'bình thuận', 'tây ninh'),
		'fri' => array('miền bắc', 'gia lai', 'ninh thuận', 'bình dương', 'vĩnh long', 'trà vinh'),
		'sat' => array('miền bắc', 'quảng ngãi', 'đà nẵng', 'dak nong', 'long an', 'hậu giang', 'tp hcm', 'bình phước')
	);

	$provinces = isset($province_schedule[$current_day]) ? $province_schedule[$current_day] : array();


	return $provinces;
}
function determine_region_by_province($province_name)
{
	$province_name = strtolower($province_name);
	$central_province = array(
		'huế', 'quảng bình', 'quảng trị', 'phú yên', 'dak lak', 'quảng nam', 'khánh hòa', 'đà nẵng', 'bia rượu', 'gia lai', 'ninh thuận', 'quảng ngãi', 'đà nẵng', 'dak nong', 'kon tum'
	);

	$southern_province = array(
		'đồng tháp', 'tp hcm', 'cà mau', 'vũng tàu', 'bến tre', 'bạc liêu', 'cần thơ', 'sóc trăng', 'đồng nai', 'an giang', 'bình thuận', 'tây ninh', 'bình dương', 'vĩnh long', 'trà vinh', 'long an', 'hậu giang', 'tp hcm', 'bình phước', 'tiền giang', 'kiên giang', 'đà lạt'
	);

	if (in_array($province_name, $central_province)) {
		return 2;
	} elseif (in_array($province_name, $southern_province)) {
		return 3;
	} else {
		return 1;
	}
}
function load_lottery_xothu()
{
	global $region;

	$province = $_POST['province'] ?? 'miền bắc';

	$region = determine_region_by_province($province);

	ob_start();
	if ($region == 1) {
		include(locate_template('template-parts/xo-thu/kqxsmb.php'));
	} elseif ($region == 2) {
		include(locate_template('template-parts/xo-thu/kqxsmt.php'));
	} else {
		include(locate_template('template-parts/xo-thu/kqxsmn.php'));
	}
	$content = ob_get_clean();
	wp_send_json_success($content);
}
add_action('wp_ajax_nopriv_load_lottery_xothu', 'load_lottery_xothu');
add_action('wp_ajax_load_lottery_xothu', 'load_lottery_xothu');
function load_lottery_statistic_db()
{
	global $results;
	$lastDate = $_POST['lastDate'] ?? date('d-m-Y');
	$numberOfDate = $_POST['numberOfDate'] ?? '30';
	$coupleOfNumber = $_POST['coupleOfNumber'] ?? '';
	$option = 'db';
	$results = callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, $option);
	// wp_send_json_success($results);

	if ($results) {
		$results['group_number'] = array();
		if ($coupleOfNumber == '') {
			$results['group_number'] = $results['statistic'];
		} else {
			foreach ($results['statistic'][0] as $item) {
				$loto_number = $item['loto'];
				$results['group_number'][$loto_number][] = $item;
			}
		}

		ob_start();
		include(locate_template('template-parts/statistic/results.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_statistic_db', 'load_lottery_statistic_db');
add_action('wp_ajax_load_lottery_statistic_db', 'load_lottery_statistic_db');
function load_lottery_dau_statistic()
{
	global $results;
	$lastDate = $_POST['lastDate'] ?? date('d-m-Y');
	$numberOfDate = $_POST['numberOfDate'] ?? '30';
	$coupleOfNumber = $_POST['coupleOfNumber'] ?? '';
	$results = callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, 'lt');


	if ($results) {
		$results['group_number'] = array();
		$grouped_data = [];
		if ($coupleOfNumber == '') {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					$grouped_data[$dayPrize][] = $item;
				}
			}

			foreach ($grouped_data as $dayPrize => &$data) {
				$prizeGroups = [];

				foreach ($data as $item) {
					$prizeId = $item['prizeId'];
					$loto = $item['firstNumber'];

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = [];
					}

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = ['count' => 0, 'is_db' => false];
					}
					$prizeGroups[$loto]['count']++;
					if ($prizeId === 1) {
						$prizeGroups[$loto]['is_db'] = true;
					}
				}

				$data = $prizeGroups;
			}
		} else {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];
					$loto = $item['firstNumber'];
					$prizeId = $item['prizeId'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					if (!isset($result[$dayPrize][$loto])) {
						$grouped_data[$dayPrize][$loto] = ['count' => 0, 'is_db' => false];
					}

					$grouped_data[$dayPrize][$loto]['count']++;

					if ($prizeId === 1) {
						$result[$dayPrize][$loto]['is_db'] = true;
					}
				}
			}
		}

		// wp_send_json_success($grouped_data);

		$results = $grouped_data;

		ob_start();
		include(locate_template('template-parts/statistic/dau.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_dau_statistic', 'load_lottery_dau_statistic');
add_action('wp_ajax_load_lottery_dau_statistic', 'load_lottery_dau_statistic');
function load_lottery_duoi_statistic()
{
	global $results;
	$lastDate = $_POST['lastDate'] ?? date('d-m-Y');
	$numberOfDate = $_POST['numberOfDate'] ?? '30';
	$coupleOfNumber = $_POST['coupleOfNumber'] ?? '';
	$results = callLotteryStatisticsAPI($numberOfDate, $lastDate, $coupleOfNumber, 'lt');


	if ($results) {
		$results['group_number'] = array();
		$grouped_data = [];
		if ($coupleOfNumber == '') {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					$grouped_data[$dayPrize][] = $item;
				}
			}

			foreach ($grouped_data as $dayPrize => &$data) {
				$prizeGroups = [];

				foreach ($data as $item) {
					$prizeId = $item['prizeId'];
					$loto = $item['lastNumber'];

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = [];
					}

					if (!isset($prizeGroups[$loto])) {
						$prizeGroups[$loto] = ['count' => 0, 'is_db' => false];
					}
					$prizeGroups[$loto]['count']++;
					if ($prizeId === 1) {
						$prizeGroups[$loto]['is_db'] = true;
					}
				}

				$data = $prizeGroups;
			}
		} else {
			$statistic = $results['statistic'];
			foreach ($statistic as $items) {
				foreach ($items as $item) {
					$dayPrize = $item['dayPrize'];
					$loto = $item['firstNumber'];
					$prizeId = $item['prizeId'];

					if (!isset($grouped_data[$dayPrize])) {
						$grouped_data[$dayPrize] = [];
					}

					if (!isset($result[$dayPrize][$loto])) {
						$grouped_data[$dayPrize][$loto] = ['count' => 0, 'is_db' => false];
					}

					$grouped_data[$dayPrize][$loto]['count']++;

					if ($prizeId === 1) {
						$result[$dayPrize][$loto]['is_db'] = true;
					}
				}
			}
		}

		// wp_send_json_success($grouped_data);

		$results = $grouped_data;

		ob_start();
		include(locate_template('template-parts/statistic/dau.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_duoi_statistic', 'load_lottery_duoi_statistic');
add_action('wp_ajax_load_lottery_duoi_statistic', 'load_lottery_duoi_statistic');

function get_vietlott_mega_645($date = null)
{
	$api_url = 'https://api.xosoaladin.com/api/v1/mega645/' . $date;

	$response = wp_remote_get($api_url);

	if (is_wp_error($response)) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	return $data;
}
function load_vietlott_mega_645()
{
	global $results;
	$date = $_POST['date'] ?? date('d-m-Y');

	$results = get_vietlott_mega_645($date);

	if ($results) {
		ob_start();
		include(locate_template('template-parts/vietlott/mega645.php'));

		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_vietlott_mega_645', 'load_vietlott_mega_645');
add_action('wp_ajax_load_vietlott_mega_645', 'load_vietlott_mega_645');


function get_lottery_by_range($range = 30, $provinceId = 1)
{

	$api_url = 'https://api.xosoaladin.com/api/v1/kqxs/getAllByProvice/' . $provinceId . '?days=' . $range;
	$response = wp_remote_get($api_url);

	if (is_wp_error($response)) {
		return false;
	}

	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	$results = $data['kqxs'];

	return $results;
}
function load_lottery_by_range()
{
	global $results;
	$range = $_POST['range'] ?? 30;
	$provinceId = $_POST['provinceId'] ?? 1;
	$results = get_lottery_by_range($range, $provinceId);

	if ($results) {
		ob_start();
		include(locate_template('template-parts/list-kqxs-range/index.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_lottery_by_range', 'load_lottery_by_range');
add_action('wp_ajax_load_lottery_by_range', 'load_lottery_by_range');
function load_count_lottery_by_range()
{
	global $results;
	$range = $_POST['range'] ?? 30;
	$provinceId = $_POST['provinceId'] ?? 1;
	$results = get_lottery_by_range($range, $provinceId);

	if ($results) {
		ob_start();
		include(locate_template('template-parts/count-kqxs-range/index.php'));
		$content = ob_get_clean();
		wp_send_json_success($content);
	} else {
		wp_send_json_error('No results found');
	}
}
add_action('wp_ajax_nopriv_load_count_lottery_by_range', 'load_count_lottery_by_range');
add_action('wp_ajax_load_count_lottery_by_range', 'load_count_lottery_by_range');
