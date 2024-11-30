<?php
/*
Template Name: Xổ Số Theo Tỉnh
*/
?>

<?php get_header();
$tinh_slug = get_query_var('tinh');
$provinceId = get_province_id_by_name_no_sign($tinh_slug);
if (isset($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = null;
}

?>
<div class="container py-3">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-12">
            <?php get_sidebar(); ?>
        </div>
        <div class="col-lg-6 col-md-3 col-12">
            <?php get_template_part('template-parts/page/formdatetinh', 'section'); ?>
            <section id="kqxstinh">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>
            <section>
                <div class="row">
                    <div class="large-12 col">
                        <?php
                        if ($tinh_slug) {
                            $args = array(
                                'post_type' => 'page',
                                'name' => $tinh_slug
                            );
                            $query = new WP_Query($args);

                            if ($query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();
                                    // Hiển thị nội dung của bài viết
                                    the_title();
                                    the_content();
                                }
                            } else {
                                echo '';
                            }
                            wp_reset_postdata();
                        }
                        ?>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-lg-3 col-md-3 col-12">
            <?php
            if (is_active_sidebar('right_sidebar')) {
                dynamic_sidebar('right_sidebar');
            }
            ?>
        </div>
    </div>

</div>
<script>
    jQuery(document).ready(function($) {
        loadLotteryResultsProvince('<?php echo $date; ?>', '<?php echo $provinceId; ?>', $('#kqxstinh'));

        function checkTimeAndRunFunction() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinutes = now.getMinutes();

            if ((currentHour > 16 || (currentHour === 16 && currentMinutes >= 0)) &&
                (currentHour < 18 || (currentHour === 18 && currentMinutes <= 30))) {
                loadLotteryResultsProvince('<?php echo $date; ?>', '<?php echo $provinceId; ?>', $('#kqxstinh'));

                console.log('updated');
            }
        }

        setInterval(checkTimeAndRunFunction, 5000);
    });
</script>
<?php get_footer(); ?>