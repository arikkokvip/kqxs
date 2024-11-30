<?php
/*
Template Name: Xổ thử kết quả xổ số
*/
?>
<?php get_header();
$provinces_today = get_province_for_today();
?>
<div class="container py-3">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-12">
            <?php get_sidebar(); ?>
        </div>
        <div class="col-lg-6 col-md-3 col-12">

            <section>
                <div class="row">
                    <div class="large-12 col">
                        <div class="loto-statistic-table">
                            <div class="loto-statistic-table-header">
                                Xổ thử KQXS
                            </div>
                            <div class="loto-statistic-table-body">

                            </div>
                            <div class="form-fil-loto">
                                <form id="formStatistic" action="post">
                                    <select name="province" id="" class="btn btn-outline-secondary">
                                        <?php
                                        foreach ($provinces_today as $province) {
                                            echo '<option value="' . $province . '">' . ucfirst($province) . '</option>'; // In hoa chữ cái đầu của tỉnh
                                        }
                                        ?>
                                    </select>
                                    <button class="btn btn-danger" type="submit">Xổ thử</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id="result" class="mt-3">
                <div class="loto-statistic-table">
                    <div class="loto-statistic-table-header">
                        Kết quả
                    </div>
                    <div class="loto-statistic-table-body">
                        <div id="loading" class=" align-items-center justify-content-center" style="height:100px;display: none;">
                            <span class="number-rs"><img width="50" height="50" src="https://i.gifer.com/ZKZg.gif" /></span>
                        </div>
                        <div class="result-content"></div>
                    </div>
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
   
</script>
<script>
  
    $('#formStatistic').on('submit', function(e) {
        e.preventDefault();
        $('#result #loading').css('display', 'flex');

        $('#result').find('.result-content').empty();
        loadXothu(
            $('select[name="province"]').val(),
            $('#result')
        );
    });
</script>

<?php get_footer(); ?>