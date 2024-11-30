<?php

$numberCount = [];
$totalCount = 0;

foreach ($results as $date => $records) {
    foreach ($records as $record) {
        $number = $record['loto'];
        if ($number !== "") {
            if (!isset($numberCount[$number])) {
                $numberCount[$number] = 0;
            }
            $numberCount[$number]++;
            $totalCount++;
        }
    }
}

$percentage = [];
foreach ($numberCount as $number => $count) {
    $percentage[$number] = ($count / $totalCount) * 100;
}
?>
<table class="table-bordered table table-striped">
    <thead class="region-title">
        <tr>
            <td class="text-white py-2">Bộ số</td>
            <td class="text-white py-2">Lần về</td>
            <td class="text-white py-2">Tỉ lệ</td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($numberCount as $number => $count) {
            echo "<tr>";
            echo "<td>" . $number . "</td>";
            echo "<td>" . $count . "</td>";
            echo "<td><div class='d-flex gap-3 align-items-center'>" . number_format($percentage[$number], 2) . "%";
        ?>
            <div class="progress" style="width: 80%;">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage[$number]; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        <?php
            echo "</div></td>";
            echo "</tr>";
        }

        ?>
    </tbody>
</table>

<?php
