<?php
/*
Template Name: Iframe Xổ thử kết quả xổ số
*/
?>
<?php get_header();
$provinces_today = get_province_for_today();
?>
<style>header{display: none;} footer{display: none;}</style>

<div class="container py-3">
    <div class="row">
       
        <div class="col-lg-12 col-md-12 col-12">
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