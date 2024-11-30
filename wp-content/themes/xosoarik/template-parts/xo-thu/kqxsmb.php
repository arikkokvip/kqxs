<?php
// if (isset($_GET['yesterday']) && $_GET['yesterday'] != null) {
//     $prev_date = date('d-m-Y', strtotime('-' . $_GET['yesterday'] . ' day', strtotime(date('d-m-Y'))));
//     $results = get_lottery_results($prev_date, 'xsmb');
// } elseif (isset($_GET['date']) && $_GET['date'] != null) {
//     $results = get_lottery_results($_GET['date'], 'xsmb');
// } else {
//     $results = get_lottery_results(date('d-m-Y'), 'xsmb');
// }
?>

<div class="table-lottery-block">
    <table id="rsMb" class="table-lottery table table-striped">
        <caption>
            <h2>KQXS Miền Bắc ngày hôm nay</h2>
       
        </caption>
        <tbody>
            <tr>
                <td class="order">Đặc biệt</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="1"><span class="number-rs" data-prize="1" data-number="5"></span></div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải nhất</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="1"><span class="number-rs" data-prize="2" data-number="5"></span></div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải nhì</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="2"><span class="number-rs" data-prize="3" data-number="5"></span>
                        <span class="number-rs" data-prize="3" data-number="5"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải ba</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="6"><span class="number-rs" data-prize="4" data-number="5"></span>
                        <span class="number-rs" data-prize="4" data-number="5"></span><span class="number-rs" data-prize="4" data-number="5"></span>
                        <span class="number-rs" data-prize="4" data-number="5"></span><span class="number-rs" data-prize="4" data-number="5"></span>
                        <span class="number-rs" data-prize="4" data-number="5"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải tư</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="4"><span class="number-rs" data-prize="5" data-number="4"></span>
                        <span class="number-rs" data-prize="5" data-number="4"></span><span class="number-rs" data-prize="5" data-number="4"></span>
                        <span class="number-rs" data-prize="5" data-number="4"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải năm</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="6"><span class="number-rs" data-prize="6" data-number="4"></span>
                        <span class="number-rs" data-prize="6" data-number="4"></span><span class="number-rs" data-prize="6" data-number="4"></span>
                        <span class="number-rs" data-prize="6" data-number="4"></span><span class="number-rs" data-prize="6" data-number="4"></span>
                        <span class="number-rs" data-prize="6" data-number="4"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải sáu</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="3"><span class="number-rs" data-prize="7" data-number="3"></span>
                        <span class="number-rs" data-prize="7" data-number="3"></span><span class="number-rs" data-prize="7" data-number="3"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="order">Giải bảy</td>
                <td class="results">
                    <div class="quantity-of-number" data-quantity="4"><span class="number-rs" data-prize="8" data-number="2"></span>
                        <span class="number-rs" data-prize="8" data-number="2"></span>
                        <span class="number-rs" data-prize="8" data-number="2"></span>
                        <span class="number-rs" data-prize="8" data-number="2"></span>
                    </div>
                </td>
            </tr>
        </tbody>

    </table>
   
</div>
<!-- <table class="table-lottery table table-striped">
    <caption>
        <h2>Lô tô <?php echo $results[0]['provinceName'] . ' ngày '  . get_day_of_week_VN($results[0]['listXSTT'][0]['dayPrize']) . ' ' . $results[0]['listXSTT'][0]['dayPrize']; ?><h2>
    </caption>
    <tbody>
        <?php

        foreach ($results as $result) {
            uasort($result['listXSTT'], function ($a, $b) {
                return $a['loto'] <=> $b['loto'];
            });

            $count = 0;

            echo '<tr>';
            echo '<td class="results"><div class="quantity-of-number" data-quantity="9">';

            foreach ($result['listXSTT'] as $item) {
                if ($item["number"] != "") {
                    echo '<span class="loto-number"  data-prize="' . $item['prizeId'] . '">' . $item['loto'] . '</span>';
                } else {
                    echo '<span class="loto-number"><img width="15" height="15" src="https://i.gifer.com/ZKZg.gif"/></span>';
                }
                $count++;

                if ($count % 9 == 0 && $count < count($result['listXSTT'])) {
                    echo '</div></td></tr><tr><td class="results"><div class="quantity-of-number"  data-quantity="9">';
                }
            }

            echo '</div></td></tr>';
        }

        ?>
    </tbody>
</table>
<table class="table-lottery table-loto-head-end table table-striped">
    <tbody>
        <tr>
            <td class="results">
                <div class="quantity-of-number specs" data-quantity="4"><span class="header wrap-text">Đầu</span><span class="header wrap-text">Lô tô</span><span class="header wrap-text">Đuôi</span><span class="header wrap-text">Lô tô</span></div>
            </td>
        </tr>
        <?php
        foreach ($results as $result) {
            for ($i = 0; $i < 9; $i++) {
                echo '<tr>';
                echo '<td class="results">';
                echo '<div class="quantity-of-number specs" data-quantity="4">';
                echo '<span class="number wrap-text">' . $i + 1 . '</span>';
                echo '<span class="number wrap-text">';
                if (isset($result['resultHead'][$i])) {
                    foreach ($result['resultHead'][$i] as $item) {
                        echo $item['loto'] . '; ';
                    }
                } else {
                    echo '-';
                }
                echo '</span>';
                echo '<span class="number wrap-text">' . $i + 1 . '</span>';
                echo '<span class="number wrap-text">';
                if (isset($result['resultEnd'][$i])) {

                    foreach ($result['resultEnd'][$i] as $item) {
                        echo $item['loto'] . '; ';
                    }
                } else {
                    echo '-';
                }
                echo '</span>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table> -->

