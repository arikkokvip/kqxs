<?php
/*
Template Name: IFrame Xổ Số Theo Tỉnh
*/

// Bao gồm file header để lấy các script và styles cần thiết
get_header();

$tinh_slug = $_GET['tinh'] ?? 'mien-bac';
$provinceId = get_province_id_by_name_no_sign($tinh_slug);
$date = $_GET['date'] ?? null;
?>
<style>
    header, footer {
        display: none;
    }
</style>

<div class="container py-3">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12">
            <?php get_template_part('template-parts/page/formdatetinh', 'section'); ?>
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
        function loadResults() {
            loadLotteryResultsProvince('<?php echo $date; ?>', '<?php echo $provinceId; ?>', $('#kqxstinh'));
        }

        // Gọi hàm loadResults lần đầu tiên khi trang được tải
        loadResults();

        // Kiểm tra thời gian hiện tại và cập nhật kết quả nếu trong khoảng thời gian chỉ định
        function checkTimeAndRunFunction() {
            const now = new Date();
            const currentHour = now.getHours();
            const currentMinutes = now.getMinutes();

            if ((currentHour > 16 || (currentHour === 16 && currentMinutes >= 0)) &&
                (currentHour < 18 || (currentHour === 18 && currentMinutes <= 30))) {
                loadResults();
                console.log('updated');
            }
        }

        // Thiết lập khoảng thời gian kiểm tra lại mỗi 5 giây
        setInterval(checkTimeAndRunFunction, 5000);
    });
</script>

<?php
// Bao gồm file footer để lấy các script và styles cần thiết
get_footer();
?>
