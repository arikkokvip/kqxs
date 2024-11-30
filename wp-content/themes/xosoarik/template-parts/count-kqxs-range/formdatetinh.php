<?php
$current_url = add_query_arg(null, null);
$current_url = esc_url_raw($current_url);
$current_url = remove_query_arg(array_keys($_GET), $current_url);
?>
<div class="row mb-2">
    <div class="col-lg-12 col-md-12 col-12 text-center">
        <form action="" id="formfil">
            <?php echo do_shortcode('[select_province]'); ?>
            <select name="" class="btn btn-outline-secondary" name="range" id="range">
                <option value="30">30 ngày</option>
                <option value="60">60 ngày</option>
                <option value="90">90 ngày</option>
                <option value="120">120 ngày</option>
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