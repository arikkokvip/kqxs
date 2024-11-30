<?php // Front-end của widget
class Province_Widget extends WP_Widget {

// Khởi tạo widget
function __construct() {
    parent::__construct(
        'province_widget', // ID của widget
        __('Danh Sách Tỉnh Theo Miền', 'kqxs_arikk'), // Tiêu đề widget
        array( 'description' => __( 'Hiển thị danh sách các tỉnh theo miền', 'kqxs_arikk' ), ) // Mô tả
    );
}

public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
        echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
    }

    // Nội dung widget: Hiển thị danh sách các tỉnh theo miền
    global $wpdb;
    $table_name = $wpdb->prefix . 'provinces';

    $regions = array(1 => 'Xổ số Miền Bắc', 2 => ' Xổ số Miền Trung', 3 => ' Xổ số Miền Nam');

    foreach ($regions as $region_id => $region_name) {
        echo "<div class='region-widget d-md-block d-none'><div class='region-title'><span>{$region_name}</span></div>";
        $provinces = $wpdb->get_results( "SELECT * FROM $table_name WHERE region = $region_id" );
        echo "<ul>";
        foreach ( $provinces as $province ) {
            echo "<li><a href='".get_home_url().'/tinh'.'/'.$province->nameNoSign."'><i class='fas fa-chevron-right'></i> {$province->name}</a></li>";
        }
        echo "</ul></div>";
    }

    echo $args['after_widget'];
}


// Backend widget form
public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Mới', 'text_domain' );
    // Form widget trong admin
    // Đây là phần bạn có thể thêm các trường cấu hình cho widget, ví dụ như tiêu đề
}

// Cập nhật dữ liệu từ form vào database
public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    return $instance;
}
}
