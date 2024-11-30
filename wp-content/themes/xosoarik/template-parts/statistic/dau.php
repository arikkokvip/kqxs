<?php if ($results) {
    $timestamp = strtotime($lastDate);
?>
    <div class="table-responsitory table-gan">
        <table class="table align-middle table-bordered table-hover table-statistic-result">
            <thead>
                <tr class="tr-active">
                    <th style="width: 20px;"></th>
                    <?php for ($i = 0; $i < 10; $i++) {
                        echo '<th>' . $i . '</th>';
                    } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 1; $i < $numberOfDate; $i++) {
                    $previousDate = date('d-m-Y', strtotime("-$i day", $timestamp));
                    $resultOfDate = $results[$previousDate];
                ?>
                    <tr>
                        <td>
                            <?php echo $previousDate . "\n"; ?>
                        </td>
                        <?php for ($j = 0; $j < 10; $j++) {
                            $data = $resultOfDate[$j];
                            $class = isset($data['count']) ? "count" : " ";
                            $class .=  ($data['is_db']) ? " " : "";
                            echo '<td style="background-color: #eee; color: #000">'.$data['count'].'</td>';
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