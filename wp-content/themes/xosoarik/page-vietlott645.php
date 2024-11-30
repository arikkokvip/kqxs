<?php
/*
Template Name: Vietlott 645
*/
?>

<?php get_header();
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
        <?php get_template_part('template-parts/page/formDateVietlott', 'section'); ?>
            <section id="result">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>
            <section>
                <div class="row">
                    <div class="large-12 col">
                        <?php
                        if (have_posts()) :
                            while (have_posts()) : the_post();
                                the_content();
                            endwhile;
                        else :
                        endif;
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
        loadVietlott645('<?php echo $date; ?>', $('#result'));
    });
</script>
<?php get_footer(); ?>