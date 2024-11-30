<?php

if ($results) {
?>
    
    <table class="table-lottery table table-striped">
        <caption>
            <h2>KQXS <?php echo $results[0]['provinceName'] . ' ngày ' . get_day_of_week_VN($results[0]['listXSTT'][0]['dayPrize']) . ' ' . $results[0]['listXSTT'][0]['dayPrize']; ?></h2>
        </caption>
        <tbody>
            <?php

            foreach ($results as $result) {
                uasort($result['listXSTT'], function ($a, $b) {
                    if ($a['prizeId'] == $b['prizeId']) {
                        return $a['prizeColumn'] <=> $b['prizeColumn'];
                    }
                    return $a['prizeId'] <=> $b['prizeId'];
                });
                $groupedResults = [];
                foreach ($result['listXSTT'] as $item) {
                    $groupedResults[$item['prizeId']][] = $item;
                }
                foreach ($groupedResults as $prizeId => $items) {
                    echo '<tr>';
                    echo '<td class="order">' . convert_number_to_prize_name($prizeId) . '</td>';
                    echo '<td class="results">';
                    echo '<div class="quantity-of-number" data-quantity="' . count($items) . '">';
                    foreach ($items as $item) {
                        echo '<span data-prize="' . $prizeId . '">' . $item['number'] . '</span>';
                    }
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
    <table class="table-lottery table table-striped">
        <caption>
            <h2>Lô tô <?php echo $results[0]['provinceName'] . ' ngày '  . get_day_of_week_VN($results[0]['listXSTT'][0]['dayPrize']) . ' ' . $results[0]['listXSTT'][0]['dayPrize']; ?></h2>
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
                    echo '<span class="loto-number"  data-prize="' . $item['prizeId'] . '">' . $item['loto'] . '</span>';
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
    </table>
<?php
} else {
    echo '<p>Không có dữ liệu kết quả xổ số.</p>';
}
