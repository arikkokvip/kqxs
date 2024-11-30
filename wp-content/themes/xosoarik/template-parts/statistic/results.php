<?php if ($results) { ?>
    <div class="table-responsitory">
        <table class="table align-middle table-bordered table-statistic-result">
            <thead>
                <tr class="tr-active">
                    <th>Số chọn</th>
                    <th>Số lần</th>
                    <th>Ngày về</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results['group_number'] as $key => $group) {
                ?>
                    <tr>
                        <td class="col-xs-2 text-bold text-center color-reb font-18">
                            <strong style="color: #d33;"><?php $formatted_number = ($key < 10) ? "0$key" : $key;
                                    echo $formatted_number; ?></strong>
                        </td>
                        <td class="col-xs-3 text-center"><?php echo count($group) ?> lần</td>
                        <td class="col-xs-7">
                            <?php echo implode(', ', array_column($group, 'dayPrize')) ?>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo '<p>Không có dữ liệu kết quả xổ số.</p>';
}
?>