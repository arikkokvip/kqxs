<?php
class Pre_Result_Lottery_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'pre_result_lottery_widget',
            __('Danh Sách Kết quả xổ số hôm qua', 'kqxs_arikk'),
            array('description' => __('Hiển thị danh sách kết quả xổ số hôm qua', 'kqxs_arikk'),) // Mô tả
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }


        $regions = array('xo-so-mien-bac' => 'Xổ số Miền Bắc', 'xo-so-mien-trung' => ' Xổ số Miền Trung', 'xo-so-mien-nam' => ' Xổ số Miền Nam');
        echo "<div class='region-widget d-md-block d-none'><div class='region-title'><span>Kết quả xổ số hôm qua</span></div>";
        echo "<ul>";

        foreach ($regions as $slug => $region_name) {
            echo "<li><a href='".get_home_url().'/'.$slug."'><i class='fas fa-chevron-right'></i> {$region_name}</a></li>";
        }
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
