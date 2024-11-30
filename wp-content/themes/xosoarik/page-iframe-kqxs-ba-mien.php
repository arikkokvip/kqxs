<?php
/*
Template Name: IFrame Kết quả xổ số ba miền
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
<style>header{display: none;} footer{display: none;}</style>

<div class="container py-3">
    <div class="row">
     
        <div class="col-lg-12 col-md-12 col-12">
            <?php get_template_part('template-parts/front-page/formdate', 'section'); ?>
            <section id="kqxsmb" class="p-3 bg-white rounded mb-3">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>
            <section id="kqxsmt" class="p-3 bg-white rounded mb-3">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>
            <section id="kqxsmn" class="p-3 bg-white rounded mb-3">
                <div class="d-flex align-items-center justify-content-center" style="height:100px">
                    <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                </div>
            </section>

          
        </div>
        
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        loadLotteryResults('<?php echo $date; ?>', 'xsmb', $('#kqxsmb'));

        loadLotteryResults('<?php echo $date; ?>', 'xsmt', $('#kqxsmt'));
        loadLotteryResults('<?php echo $date; ?>', 'xsmn', $('#kqxsmn'));

        function checkTimeAndRunFunction() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinutes = now.getMinutes();

            if ((currentHour > 16 || (currentHour === 16 && currentMinutes >= 0)) &&
                (currentHour < 18 || (currentHour === 18 && currentMinutes <= 30))) {
                loadLotteryResults('<?php echo $date; ?>', 'xsmb', $('#kqxsmb'));

                loadLotteryResults('<?php echo $date; ?>', 'xsmt', $('#kqxsmt'));
                loadLotteryResults('<?php echo $date; ?>', 'xsmn', $('#kqxsmn'));
                console.log('updated');
            }
        }

        setInterval(checkTimeAndRunFunction, 5000);
    });
</script>
<?php get_footer(); ?>