<?php
/*
Template Name: Xổ Số Miền Trung
*/
?>

<?php get_header();
if (isset($_GET['yesterday']) && $_GET['yesterday'] != null) {
    $date = date('d-m-Y', strtotime('-' . $_GET['yesterday'] . ' day', strtotime(date('d-m-Y'))));
} elseif (isset($_GET['date']) && $_GET['date'] != null) {
    $date = $_GET['date'];
} else {
    $date = date('d-m-Y');
}
?>
<div class="container py-3">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-12">
            <?php get_sidebar(); ?>
        </div>
        <div class="col-lg-6 col-md-3 col-12">
            <?php get_template_part('template-parts/front-page/formdate', 'section'); ?>

            <section id="kqxsmt">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>
            <div class="page-content py-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
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
            </div>
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
        loadLotteryResults('<?php echo $date; ?>', 'xsmt', $('#kqxsmt'));

        function checkTimeAndRunFunction() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinutes = now.getMinutes();

            if ((currentHour > 16 || (currentHour === 16 && currentMinutes >= 0)) &&
                (currentHour < 18 || (currentHour === 18 && currentMinutes <= 30))) {
                loadLotteryResults('<?php echo $date; ?>', 'xsmt', $('#kqxsmt'));

                console.log('updated');
            }
        }

        setInterval(checkTimeAndRunFunction, 5000);
    });
</script>
<?php get_footer(); ?>