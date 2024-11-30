<?php
$current_url = add_query_arg(null, null);
$current_url = esc_url_raw($current_url);
$current_url = remove_query_arg(array_keys($_GET), $current_url);
?>
<div class="row mb-2">
    <div class="col-lg-12 col-md-12 col-12 text-center">
        <a class="btn mr-1 <?php if (!isset($_GET['date'])) {
                                echo "btn-danger";
                            } else {
                                echo "btn-outline-secondary";
                            } ?>" href="<?php echo $current_url; ?>">Mới nhất</a>
        <input type="hidden" id="selectedDate">
        <button id="datePickerButton" class="btn <?php if (isset($_GET['date']) && $_GET['date'] != null) {
                                                        echo "btn-danger";
                                                    } else {
                                                        echo "btn-outline-secondary";
                                                    } ?>"><?php if (isset($_GET['date']) && $_GET['date'] != null) {
                                                                echo $_GET['date'];
                                                            } else {
                                                                echo "Chọn ngày";
                                                            } ?></button>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#selectedDate').datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText, inst) {
                window.location.href = '<?php echo rtrim($current_url, '/') . '?date='; ?>' + dateText;
            }
        });

        $('#datePickerButton').click(function() {
            $('#selectedDate').datepicker("show");
        });
    });
</script>