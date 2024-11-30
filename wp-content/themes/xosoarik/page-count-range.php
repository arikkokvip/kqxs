<?php
/*
Template Name: Thống kê xổ số theo khoảng thời gian
*/
?>

<?php get_header();
$tinh_slug = $_GET['tinh'] ?? 'mien-bac';
$provinceId = get_province_id_by_name_no_sign($tinh_slug);
$tinh_name = get_province_name_by_name_no_sign($tinh_slug);
$range = $_GET['range'] ?? 30;
?>
<div class="container py-3">
    <div class="row">

        <div class="col-lg-3 col-md-3 col-12">
            <?php get_sidebar(); ?>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <h2>Thống kê xổ số <?php echo $tinh_name . ' - trong ' . $range . ' ngày'; ?></h2>
            <?php get_template_part('template-parts/list-kqxs-range/formdatetinh', 'section'); ?>
            <section id="kqxstinh">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
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
        loadCountLotteryByRangeDate(<?php echo $range; ?>, <?php echo $provinceId; ?>, $('#kqxstinh'));

    });
</script>
<?php get_footer(); ?>