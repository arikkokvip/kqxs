<?php
/*
Template Name: Xổ Số Theo Tỉnh
*/
?>

<?php get_header();
$post = get_post();
if ($post) {
    $tinh_slug = $post->post_name; // Lấy slug từ post object
}
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
                        while (have_posts()) : the_post();
                            the_title('<h1 class="entry-title">', '</h1>');
                            the_content();
                        endwhile; // End of the loop.
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
    });
</script>
<?php get_footer(); ?>