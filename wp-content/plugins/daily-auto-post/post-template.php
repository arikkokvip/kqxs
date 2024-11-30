<?php
/*
Template for Daily Post Scheduler
*/
if (!function_exists('number_to_prize')) {

    function number_to_prize($number)
    {
        switch ($number) {
            case 1:
                return 'Đặc biệt';
            case 2:
                return 'Giải nhất';
            case 3:
                return 'Giải nhì';
            case 4:
                return 'Giải ba';
            case 5:
                return 'Giải tư';
            case 6:
                return 'Giải năm';
            case 7:
                return 'Giải sáu';
            case 8:
                return 'Giải bảy';
            case 9:
                return 'Giải tám';
            default:
                return 'Không xác định';
        }
    }
}

if (!function_exists('get_lottery_result_template_plugin')) {

    function get_lottery_result_template_plugin($province_name, $date, $results, $resultHead, $resultEnd)
    {
        ob_start();
        if ($results) {
?>
            <div class="lottery-flex">
                <div class="lottery-left">
                    <div class="table-lottery-block">
                        <table id="rsMb" class="table-lottery table table-striped">
                            <caption>
                                <h2>KQXS <?php echo $province_name . ' ngày ' . $date ?></h2>
                            </caption>
                            <tbody>
                                <?php

                                uasort($results, function ($a, $b) {
                                    if ($a['prizeId'] == $b['prizeId']) {
                                        return $a['prizeColumn'] <=> $b['prizeColumn'];
                                    }
                                    return $a['prizeId'] <=> $b['prizeId'];
                                });
                                $groupedResults = [];
                                foreach ($results as $item) {
                                    $groupedResults[$item['prizeId']][] = $item;
                                }
                                foreach ($groupedResults as $prizeId => $items) {
                                    echo '<tr>';
                                    echo '<td class="order">' . number_to_prize($prizeId) . '</td>';
                                    echo '<td class="results">';
                                    echo '<div class="quantity-of-number" data-quantity="' . count($items) . '">';
                                    foreach ($items as $item) {
                                        echo '<span class="number-rs" data-prize="' . $prizeId . '" data-number="' . $item['number'] . '">' . $item['number'] . '</span>';
                                    }
                                    echo '</div>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>

                        </table>

                    </div>
                    <table class="table-lottery table table-striped">
                        <caption>
                            <h2>Lô tô <?php echo $province_name . ' ngày '  . $date ?></h2>
                        </caption>
                        <tbody>
                            <?php

                            uasort($results, function ($a, $b) {
                                return $a['loto'] <=> $b['loto'];
                            });

                            $count = 0;

                            echo '<tr>';
                            echo '<td class="results"><div class="quantity-of-number" data-quantity="9">';

                            foreach ($results as $item) {
                                echo '<span class="loto-number"  data-prize="' . $item['prizeId'] . '">' . $item['loto'] . '</span>';
                                $count++;

                                if ($count % 9 == 0 && $count < count($results)) {
                                    echo '</div></td></tr><tr><td class="results"><div class="quantity-of-number"  data-quantity="9">';
                                }
                            }

                            echo '</div></td></tr>';

                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="lottery-right">
                    <table class="table-lottery table-loto-head-end table table-striped">
                        <tbody>
                            <tr>
                                <td class="results">
                                    <div class="quantity-of-number specs" data-quantity="4"><span class="header wrap-text">Đầu</span><span class="header wrap-text">Lô tô</span><span class="header wrap-text">Đuôi</span><span class="header wrap-text">Lô tô</span></div>
                                </td>
                            </tr>
                            <?php
                            for ($i = 0; $i < 9; $i++) {
                                echo '<tr>';
                                echo '<td class="results">';
                                echo '<div class="quantity-of-number specs" data-quantity="4">';
                                echo '<span class="number wrap-text">' . intval($i + 1) . '</span>';
                                echo '<span class="number wrap-text">';
                                if (isset($resultHead[$i])) {
                                    foreach ($resultHead[$i] as $item) {
                                        echo $item['loto'] . '; ';
                                    }
                                } else {
                                    echo '-';
                                }
                                echo '</span>';
                                echo '<span class="number wrap-text">' . intval($i + 1)  . '</span>';
                                echo '<span class="number wrap-text">';
                                if (isset($resultEnd[$i])) {

                                    foreach ($resultEnd[$i] as $item) {
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
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php
        } else {
            echo '<p>Không có dữ liệu kết quả xổ số.</p>';
        }
        return ob_get_clean();
    }
}
?>