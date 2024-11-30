<?php
/*
Plugin Name: Import Posts from API
Description: Tự động lấy dữ liệu từ API và tạo bài viết trong WordPress.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Thêm menu vào trang quản trị
add_action('admin_menu', 'import_posts_menu');
function import_posts_menu()
{
    add_menu_page(
        'Import Posts from API',
        'Import Posts',
        'manage_options',
        'import-posts',
        'import_posts_page',
        'dashicons-update',
        6
    );
}

function import_posts_page()
{
?>
    <div class="wrap">
        <h1>Import Posts from API</h1>
        <button id="start-import" class="button button-primary">Start Import</button>
        <div id="import-status"></div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var pageIndex = 1;
            $('#start-import').click(function() {
                $('#import-status').html('Importing...');
                importPosts();
            });

            function importPosts() {
                $.ajax({
                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        action: 'import_posts_from_api',
                        pageIndex: pageIndex,
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#import-status').append('<p>Page ' + pageIndex + ' imported.</p>');
                            if (response.data.has_more) {
                                pageIndex++;
                                importPosts();
                            } else {
                                $('#import-status').append('<p>All posts imported successfully.</p>');
                            }
                        } else {
                            $('#import-status').append('<p>Error: ' + response.data.message + '</p>');
                        }
                    },
                    error: function(response) {
                        $('#import-status').append('<p>Error: ' + response.statusText + '</p>');
                    }
                });
            }
        });
    </script>
<?php
}

// Hàm để xử lý AJAX request
add_action('wp_ajax_import_posts_from_api', 'import_posts_from_api');
function import_posts_from_api()
{
    $pageIndex = isset($_POST['pageIndex']) ? intval($_POST['pageIndex']) : 1;
    $api_url = "https://apils.okvipcdn.com/api/post/getPagingV2?pageIndex=$pageIndex&pageSize=3&search=";
    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        wp_send_json_error(array('message' => 'Failed to fetch API.'));
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['data']) && !empty($data['data'])) {
        foreach ($data['data'] as $post_data) {
            import_single_post($post_data);
        }

        wp_send_json_success(array('has_more' => true));
    } else {
        wp_send_json_success(array('has_more' => false));
    }
}

function import_single_post($post_data)
{
    // Kiểm tra xem bài viết đã tồn tại dựa trên ID từ API
    $existing_post_id = get_posts(array(
        'meta_key' => 'api_post_id',
        'meta_value' => $post_data['_id'],
        'post_type' => 'post',
        'post_status' => 'any',
        'fields' => 'ids',
        'numberposts' => 1,
    ));

    if (!empty($existing_post_id)) {
        return; // Bài viết đã tồn tại, không tạo lại
    }

    // Kiểm tra và tạo chuyên mục nếu chưa tồn tại
    $category = get_category_by_slug($post_data['category']['categorySlug']);
    if (!$category) {
        $category = wp_insert_term(
            $post_data['category']['categoryName'],
            'category',
            array(
                'slug' => $post_data['category']['categorySlug']
            )
        );
        if (is_wp_error($category)) {
            return;
        }
        $category_id = $category['term_id'];
    } else {
        $category_id = $category->term_id;
    }

    // Lấy nội dung bài viết và thay thế URL ảnh
    $content = $post_data['content'];
    $content = replace_image_and_links_in_content($content);

    // Tạo bài viết mới
    $post_id = wp_insert_post(array(
        'post_title' => $post_data['title'],
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'post',
        'post_category' => array($category_id),
    ));

    if (is_wp_error($post_id)) {
        return;
    }

    // Lưu ảnh đại diện
    if (!empty($post_data['thumb'])) {
        $image_url = 'https://apils.okvipcdn.com/' . $post_data['thumb'];
        $image_id = import_image_from_url($image_url);
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
        }
    }

    // Lưu meta data để đánh dấu bài viết đã được import
    update_post_meta($post_id, 'api_post_id', $post_data['_id']);

    // SEO data for RankMath
    update_post_meta($post_id, 'rank_math_focus_keyword', $post_data['seo_keyfocus']);
    update_post_meta($post_id, 'rank_math_title', $post_data['title']);
    update_post_meta($post_id, 'rank_math_description', $post_data['description']);
}
function replace_image_and_links_in_content($content) {
    // Tìm và thay thế URL ảnh trong nội dung
    preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $image_url) {
            $image_id = import_image_from_url($image_url);
            if (!is_wp_error($image_id)) {
                $image_src = wp_get_attachment_url($image_id);
                $content = str_replace($image_url, $image_src, $content);
            }
        }
    }

    // Tìm và thay thế liên kết ngoài thành liên kết của website
    preg_match_all('/<a[^>]+href="([^">]+)"/i', $content, $matches);

    if (!empty($matches[1])) {
        $home_url = home_url();
        foreach ($matches[1] as $external_url) {
            // Thay thế base URL của liên kết ngoài bằng URL của website
            $new_url = str_replace('https://luongson.cam', $home_url, $external_url);
            $content = str_replace($external_url, $new_url, $content);
        }
    }

    return $content;
}

// function replace_image_urls_in_content($content)
// {
//     // Tìm tất cả URL ảnh trong nội dung
//     preg_match_all('/<img[^>]+src="([^">]+)"/i', $content, $matches);

//     if (!empty($matches[1])) {
//         foreach ($matches[1] as $image_url) {
//             $image_id = import_image_from_url($image_url);
//             if (!is_wp_error($image_id)) {
//                 $image_src = wp_get_attachment_url($image_id);
//                 $content = str_replace($image_url, $image_src, $content);
//             }
//         }
//     }

//     return $content;
// }

function import_image_from_url($image_url)
{
    $image_data = wp_remote_get($image_url);

    if (is_wp_error($image_data)) {
        return new WP_Error('image_import_error', __('Failed to retrieve image.', 'import-posts'));
    }

    $image_data = wp_remote_retrieve_body($image_data);
    $filename = basename($image_url);

    // Tải lên hình ảnh vào WordPress
    $upload_file = wp_upload_bits($filename, null, $image_data);
    if ($upload_file['error']) {
        return new WP_Error('upload_error', $upload_file['error']);
    }

    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit',
    );

    $attachment_id = wp_insert_attachment($attachment, $upload_file['file']);

    if (!is_wp_error($attachment_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
    }

    return $attachment_id;
}
?>