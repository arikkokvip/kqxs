<?php
/*
Template Name: Thống kê giải đặc biệt
*/
?>
<?php get_header();
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
                                Thống kê giải đặc biệt
                            </div>
                            <div class="loto-statistic-table-body">
                                <div class="statistic-options">
                                    <span class="active">Tự chọn</span>
                                    <span>Số chẵn</span>
                                    <span>Số lẻ</span>
                                    <span>Đầu chẵn</span>
                                    <span>Đầu lẻ</span>
                                </div>
                                <div class="number-select">
                                    <?php
                                    for ($i = 0; $i < 100; $i++) {
                                        $formatted_number = ($i < 10) ? "0$i" : $i;
                                        echo "<span class='number-loto' data-value='$formatted_number'>$formatted_number</span>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-fil-loto">
                                <form id="formStatistic" action="post">
                                    <input type="hidden" name="coupleOfNumber">
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
    $(document).ready(function() {
        $(".number-loto").click(function() {
            $(this).toggleClass('active');
            updateSelectedNumbers();
        });

        $(".statistic-options span").click(function() {
            var type = $(this).text();
            $(".number-loto").removeClass('active');
            $(".statistic-options span").removeClass('active');
            $(this).addClass('active');
            if (type === "Tự chọn") {
                $(".number-loto").removeClass('active');
            } else if (type === "Số chẵn") {
                $(".number-loto").each(function() {
                    var number = parseInt($(this).data('value'));
                    if (number % 2 === 0) {
                        $(this).addClass('active');
                    }
                });
            } else if (type === "Số lẻ") {
                $(".number-loto").each(function() {
                    var number = parseInt($(this).data('value'));
                    if (number % 2 !== 0) {
                        $(this).addClass('active');
                    }
                });
            } else if (type === "Đầu chẵn") {
                $(".number-loto").each(function() {
                    var number = $(this).data('value').toString(); // Chuyển đổi thành chuỗi
                    var firstDigit = parseInt(number.charAt(0));
                    if (firstDigit % 2 === 0) {
                        $(this).addClass('active');
                    }
                });
            } else if (type === "Đầu lẻ") {
                $(".number-loto").each(function() {
                    var number = $(this).data('value').toString(); // Chuyển đổi thành chuỗi
                    var firstDigit = parseInt(number.charAt(0)); // Lấy ký tự đầu tiên
                    if (firstDigit % 2 != 0) {
                        $(this).addClass('active');
                    }
                });
            }else  {
                $(".number-loto").each(function() {
                    $(".number-loto").removeClass('active');
                });
            }

            updateSelectedNumbers();
        });

        function updateSelectedNumbers() {
            var selectedNumbers = [];

            $(".number-loto.active").each(function() {
                selectedNumbers.push($(this).data('value'));
            });

            $("input[name='coupleOfNumber']").val(selectedNumbers.join(','));
        }
    });
    $('#formStatistic').on('submit', function(e) {
        e.preventDefault();
        $('#result').find('.result-content').empty();
        loadStatisticLotoDB(
            $('select[name="numberOfDate"]').val(),
            $('input[name="lastDate"]').val(),
            $('input[name="coupleOfNumber"]').val(),
            $('#result')
        );
    });
</script>

<?php get_footer(); ?>