<?php
$current_url = add_query_arg(null, null);
$current_url = esc_url_raw($current_url);
$current_url = remove_query_arg(array_keys($_GET), $current_url);
$range = isset($_GET['range']) ? $_GET['range'] : 30;
$tinh_slug = $_GET['tinh'] ?? 'mien-bac';

?>
<div class="row mb-2">
    <div class="col-lg-12 col-md-12 col-12 text-center">
        <form action="" id="formfil">
            <?php echo do_shortcode('[select_province province="'.$tinh_slug.'"]'); ?>
            <select name="" class="btn btn-outline-secondary" name="range" id="range">
                <option value="30" <?php echo $range == 30 ? "selected" : ""; ?>>30 ngày</option>
                <option value="60" <?php echo $range == 60 ? "selected" : ""; ?>>60 ngày</option>
                <option value="90" <?php echo $range == 90 ? "selected" : ""; ?>>90 ngày</option>
                <option value="120" <?php echo $range == 120 ? "selected" : ""; ?>>120 ngày</option>
            </select>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#formfil').on('change', function() {
            window.location.href = '<?php echo rtrim($current_url, '/') . '?tinh='; ?>' + $('#province').val()+'&range='+$('#range').val();
        });

  
    });
</script>