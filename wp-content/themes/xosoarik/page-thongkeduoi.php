<?php
/*
Template Name: Thống kê đuôi
*/
?>
<?php get_header();
?>
<div class="container py-3">
    <div class="row">
    <div class="col-lg-3 col-md-3 col-12">
            <?php get_sidebar(); ?>
        </div>
        <div class="col-lg-6 col-md-6 col-12">

            <section>
                <div class="row">
                    <div class="large-12 col">
                        <div class="loto-statistic-table">
                            <div class="loto-statistic-table-header">
                                Thống kê đuôi
                            </div>
                            
                            <div class="form-fil-loto">
                                <form id="formStatistic" action="post">
                                    <input type="hidden" id="selectedDate" name="lastDate" value="<?php echo (date('d-m-Y')); ?>">
                                    <button id="datePickerButton" type="button" class="btn btn-outline-secondary"><?php echo date('d-m-Y'); ?></button>
                                    <select name="numberOfDate" id="" class="btn btn-outline-secondary">
                                        <option value="30">30 ngày</option>
                                        <option value="45">45 ngày</option>
                                        <option value="60">60 ngày</option>
                                        <option value="90">90 ngày</option>
                                        <option value="120">120 ngày</option>
                                    </select>
                                  
                                    <button class="btn btn-danger" type="submit">Thống kê</button>
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
    $(document).ready(function() {
        $('#selectedDate').datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText, inst) {
                $('#datePickerButton').text(dateText);
            }
        });

        $('#datePickerButton').click(function() {
            $('#selectedDate').datepicker("show");
        });
    });
</script>
<script>
   
    $('#formStatistic').on('submit', function(e) {
        e.preventDefault();
        $('#result').find('.result-content').empty();
        loadStatisticDuoi(
            $('select[name="numberOfDate"]').val(),
            $('input[name="lastDate"]').val(),
            $('input[name="coupleOfNumber"]').val(),
            $('#result')
        );
    });
</script>

<?php get_footer(); ?>