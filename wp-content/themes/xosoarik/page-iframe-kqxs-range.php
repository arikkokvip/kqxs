<?php
/*
Template Name: IFrame Xổ Số Theo Tỉnh theo khoảng thời gian
*/
?>
<?php get_header();

$tinh_slug = $_GET['tinh'] ?? 'mien-bac';
$provinceId = get_province_id_by_name_no_sign($tinh_slug);
$tinh_name = get_province_name_by_name_no_sign($tinh_slug);
$range = $_GET['range'] ?? 30;
?>
<style>header{display: none;} footer{display: none;}</style>

<div class="container py-3">
    <div class="row">
    
        <div class="col-lg-12 col-md-3 col-12">
            <h2>Kết quả xổ số <?php echo $tinh_name . ' - trong ' . $range . ' ngày'; ?></h2>
            <?php get_template_part('template-parts/list-kqxs-range/formdatetinh', 'section'); ?>
            <section id="kqxstinh">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>

        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        loadLotteryByRangeDate(<?php echo $range; ?>, <?php echo $provinceId; ?>, $('#kqxstinh'));

    });
</script>
<?php get_footer(); ?>