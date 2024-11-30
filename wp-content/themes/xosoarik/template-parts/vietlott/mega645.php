<?php
if ($results) {
?>
    <div class="loto-statistic-table">
        <div class="loto-statistic-table-header">
            Kết quả Viettlot Mega 64/5 ngày <?php echo $date; ?>
        </div>
        <div class="loto-statistic-table-body">
            <div class="jacpot-price">
                <span><?php echo $results['resultObj']['jackpot']; ?> đ</span>
            </div>
            <div class="jacpot-date">
                <p><span>Kỳ: <?php echo $results['resultObj']['idKy']; ?></span> - <span>Ngày: <?php echo $results['resultObj']['dayPrize']; ?></span></p>
            </div>
            <div class="jackpot-number">
                <div class="numbers">
                    <span><?php echo $results['resultObj']['number1']; ?></span>
                    <span><?php echo $results['resultObj']['number2']; ?></span>
                    <span><?php echo $results['resultObj']['number3']; ?></span>
                    <span><?php echo $results['resultObj']['number4']; ?></span>
                    <span><?php echo $results['resultObj']['number5']; ?></span>
                    <span><?php echo $results['resultObj']['number6']; ?></span>
                </div>
                <p class="text-center"><small>Các con số dự thưởng phải trùng với số kết quả nhưng không cần theo đúng thứ tự</small></p>
            </div>
            <div class="jackpot-detail">
                <table class="table table-bordered">
                    <thead style="background-color:#d33; color: #fff;">
                        <tr>
                            <th class="col-xs-2">Giải thưởng</th>
                            <th class="col-xs-4">Trùng khớp</th>
                            <th class="text-right col-xs-2">Số lượng giải</th>
                            <th class="text-right col-xs-4">Giá trị giải (đ)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="text-red">Jackpot</span></td>
                            <td class="circle-no">
                                <i></i><i></i><i></i><i></i><i></i><i></i>
                            </td>
                            <td class="text-right"><?php echo $results['resultObj']['jackpotWinner']; ?></td>
                            <td class="text-right"><?php echo $results['resultObj']['jackpot']; ?></td>
                        </tr>
                        <tr>
                            <td>Giải 1</td>
                            <td class="circle-no">
                                <i></i><i></i><i></i><i></i><i></i>
                            </td>
                            <td class="text-right"><?php echo $results['resultObj']['match5Winner']; ?></td>
                            <td class="text-right"><?php echo $results['resultObj']['match5']; ?></td>
                        </tr>
                        <tr>
                            <td>Giải 2</td>
                            <td class="circle-no">
                                <i></i><i></i><i></i><i></i>
                            </td>
                            <td class="text-right"><?php echo $results['resultObj']['match4Winner']; ?></td>
                            <td class="text-right"><?php echo $results['resultObj']['match4']; ?></td>
                        </tr>
                        <tr>
                            <td>Giải 3</td>
                            <td class="circle-no">
                                <i></i><i></i><i></i>
                            </td>
                            <td class="text-right"><?php echo $results['resultObj']['match3']; ?></td>
                            <td class="text-right"><?php echo $results['resultObj']['match3Winner']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } else {
    echo "<p>Không có kết quả</p>";
}
?>