<?php
class Statistic_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'statistic_widget',
            __('Danh Sách Thống kê', 'kqxs_arikk'),
            array('description' => __('Hiển thị danh sách trang thống kê', 'kqxs_arikk'),) // Mô tả
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }


        echo "<div class='region-widget d-md-block d-none'><div class='region-title'><span>Thống kê loto</span></div>";
        echo "<ul>";

            echo "<li><a href='".get_home_url()."/thong-ke-loto'><i class='fas fa-chevron-right'></i> Thống kê loto</a></li>";
            echo "<li><a href='".get_home_url()."/thong-ke-gan'><i class='fas fa-chevron-right'></i> Thống kê gan</a></li>";
            echo "<li><a href='".get_home_url()."/thong-ke-dau'><i class='fas fa-chevron-right'></i> Thống kê đầu</a></li>";
            echo "<li><a href='".get_home_url()."/thong-ke-duoi'><i class='fas fa-chevron-right'></i> Thống kê đuôi</a></li>";
            echo "<li><a href='".get_home_url()."/thong-ke-giai-dac-biet'><i class='fas fa-chevron-right'></i> Thống kê giải đặc biệt</a></li>";

        echo "</ul></div>";

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('Mới', 'text_domain');
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}
