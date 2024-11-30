<?php if ($results) {
    $timestamp = strtotime($lastDate);
?>
    <div class="table-responsitory table-gan">
        <table class="table align-middle table-bordered table-hover table-statistic-result">
            <thead>
                <tr class="tr-active">
                    <th style="width: 20px;"></th>
                    <?php for ($i = 0; $i < 100; $i++) {
                        $formatted_number = ($i < 10) ? "0$i" : $i;
                        echo '<th>' . $formatted_number . '</th>';
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $i < $numberOfDate; $i++) {
                    $previousDate = date('d-m-Y', strtotime("-$i day", $timestamp));
                    $resultOfDate = $results[$previousDate];
                ?>
                    <tr>
                        <td>
                            <?php echo $previousDate . "\n"; ?>
                        </td>
                        <?php for ($j = 0; $j < 100; $j++) {
                            $formatted_number = ($j < 10) ? "0$j" : $j;
                            $data = $resultOfDate[$formatted_number];
                            $class = isset($data['count']) ? "count" : " ";
                            $class .=  ($data['is_db']) ? " db" : "";
                            echo '<td class="'.$class.'">'.$data['count'].'</td>';
                        } ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>

        </table>
    </div>
<?php
} else {
    echo '<p>Không có dữ liệu kết quả xổ số.</p>';
}
?>