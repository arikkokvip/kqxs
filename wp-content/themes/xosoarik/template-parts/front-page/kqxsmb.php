<?php
// if (isset($_GET['yesterday']) && $_GET['yesterday'] != null) {
//     $prev_date = date('d-m-Y', strtotime('-' . $_GET['yesterday'] . ' day', strtotime(date('d-m-Y'))));
//     $results = get_lottery_results($prev_date, 'xsmb');
// } elseif (isset($_GET['date']) && $_GET['date'] != null) {
//     $results = get_lottery_results($_GET['date'], 'xsmb');
// } else {
//     $results = get_lottery_results(date('d-m-Y'), 'xsmb');
// }
if ($results) {
?>
    
    <div class="table-lottery-block">
        <table id="rsMb" class="table-lottery table table-striped">
            <caption>
                <h2>KQXS <?php echo $results[0]['provinceName'] . ' ngày ' . get_day_of_week_VN($results[0]['listXSTT'][0]['dayPrize']) . ' ' . $results[0]['listXSTT'][0]['dayPrize']; ?><h2>
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
                            if ($item["number"] != "") {
                                echo '<span class="number-rs" data-prize="' . $prizeId . '" data-number="' . $item['number'] . '">' . $item['number'] . '</span>';
                            } else {
                                echo '<span class="number-rs"><img width="15" height="15" src="https://i.gifer.com/ZKZg.gif"/></span>';
                            }
                        }
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>

        </table>
        <form id="numberLengthFormMb" class="formchangenumberlenght">
            <label for="sixmb"><input checked type="radio" id="sixmb" name="numberLength" value="6"> Đầy đủ</label>
            <label for="twomb"><input type="radio" id="twomb" name="numberLength" value="2"> 2 Số</label>
            <label for="threemb"><input type="radio" id="threemb" name="numberLength" value="3"> 3 Số</label>
        </form>
    </div>
    <table class="table-lottery table table-striped">
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
    </table>
<?php
} else {
    echo '<p>Không có dữ liệu kết quả xổ số.</p>';
}
?>
<script>
  $(document).ready(function() {
    $('#numberLengthFormMb input[type=radio][name=numberLength]').change(function() {
        var selectedLength = parseInt(this.value); // Chuyển đổi giá trị thành số nguyên
        $('#rsMb .number-rs').each(function() {
            var fullNumber = $(this).data('number').toString();
            console.log(fullNumber);
            $(this).show();
            // Chia chuỗi thành mảng các ký tự, đảo ngược mảng và nối lại thành chuỗi
            var displayNumber = fullNumber.split('').reverse().join('').substring(0, selectedLength);
            // Đảo ngược lại chuỗi đã được cắt
            displayNumber = displayNumber.split('').reverse().join('');
            $(this).text(displayNumber);
        });
    });
});

</script>