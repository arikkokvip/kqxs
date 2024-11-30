<?php
// if (isset($_GET["yesterday"]) && $_GET["yesterday"] != null) {
//     $prev_date = date(
//         "d-m-Y",
//         strtotime("-" . $_GET["yesterday"] . " day", strtotime(date("d-m-Y")))
//     );
//     $results = get_lottery_results($prev_date, "xsmn");
// } elseif (isset($_GET["date"]) && $_GET["date"] != null) {
//     $results = get_lottery_results($_GET["date"], "xsmn");
// } else {
//     $results = get_lottery_results(date("d-m-Y"), "xsmn");
// }
if ($results) { ?>
    <div class="table-lottery-block">
        <table id="rsMn" class="table-lottery table table-striped">
            <caption>
                <h2>KQXS <?php echo " Miền Nam ngày " .
                                get_day_of_week_VN($results[0]["listXSTT"][0]["dayPrize"]) .
                                " " .
                                $results[0]["listXSTT"][0]["dayPrize"]; ?><h2>
            </caption>
            <tbody>
                <?php
                $groupedResults = [];

                for ($i = 0; $i < count($results); $i++) {
                    usort($results[$i]["listXSTT"], function ($a, $b) {
                        if ($a["prizeId"] == $b["prizeId"]) {
                            return $a["prizeColumn"] <=> $b["prizeColumn"];
                        }
                        return $a["prizeId"] <=> $b["prizeId"];
                    });
                    foreach ($results[$i]["listXSTT"] as $item) {
                        $groupedResults[$i][$item["prizeId"]][] = $item;
                    }
                }
                echo "<tr>";
                echo '<td class="order">Giải</td>';
                echo '<td class="results">';
                echo '<div class="quantity-of-number" data-quantity="' .
                    count($results) .
                    '">';
                for ($temp = 0; $temp < count($results); $temp++) {
                    echo "<span>" . $results[$temp]["provinceName"] . "</span>";
                }
                echo "</div></td></tr>";

                $i = 9;
                while ($i > 0) {
                    $i--;
                    echo "<tr>";
                    echo '<td class="order">' .
                        convert_number_to_prize_name($i + 1) .
                        "</td>";
                    echo '<td class="results">';
                    echo '<div class="quantity-of-number" data-quantity="' .
                        count($groupedResults) .
                        '">';
                    for ($j = 0; $j < count($groupedResults); $j++) {
                        echo '<span class="result-group" data-prize="' .
                            $i +
                            1 .
                            '" >';
                        foreach ($groupedResults[$j][$i + 1] as $item) {
                            if ($item["number"] != "") {
                                echo '<span class="number-rs" data-number="' .
                                    $item["number"] .
                                    '">' .
                                    $item["number"] .
                                    "</span>";
                            } else {
                                echo '<span class="number-rs"><img width="15" height="15" src="https://i.gifer.com/ZKZg.gif"/></span>';
                            }
                        }
                        echo "</span>";
                    }

                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <form id="numberLengthFormMn" class="formchangenumberlenght">
            <label for="sixmn"><input checked type="radio" id="sixmn" name="numberLength" value="6"> Đầy đủ</label>
            <label for="twomn"><input type="radio" id="twomn" name="numberLength" value="2"> 2 Số</label>
            <label for="threemn"><input type="radio" id="threemn" name="numberLength" value="3"> 3 Số</label>
        </form>
    </div>
    <ul class="nav nav-tabs nab-select-province" id="pills-tab" role="tablist">
        <?php for ($i = 0; $i < count($results); $i++) {
            echo '<li class="nav-item" role="presentation">';
            echo '<button class="nav-link ';
            if ($i == 0) {
                echo "active";
            }
            echo '" data-bs-toggle="pill" data-bs-target="#' .
                $results[$i]["provinceNameNoSign"] .
                '" type="button" role="tab">' .
                $results[$i]["provinceName"] .
                "</button>";
            echo "</li>";
        } ?>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <?php for ($i = 0; $i < count($results); $i++) { ?>
            <div class="tab-pane fade <?php if ($i == 0) {
                                            echo "show active";
                                        } ?>" id="<?php echo $results[$i]["provinceNameNoSign"]; ?>" role="tabpanel">
                <table class="table-lottery table table-striped">
                    <caption>
                        <h2>Lô tô <?php echo $results[$i]["provinceName"] .
                                        " ngày " .
                                        get_day_of_week_VN(
                                            $results[0]["listXSTT"][0]["dayPrize"]
                                        ) .
                                        " " .
                                        $results[0]["listXSTT"][0]["dayPrize"]; ?><h2>
                    </caption>
                    <tbody>
                        <?php
                        uasort($results[$i]["listXSTT"], function ($a, $b) {
                            if ($a["loto"] == $b["loto"]) {
                                return $b["prizeId"] <=> $a["prizeId"];
                            }
                            return $a["loto"] <=> $b["loto"];
                        });
                        $count = 0;
                        echo "<tr>";
                        echo '<td class="results"><div class="quantity-of-number" data-quantity="9">';

                        foreach ($results[$i]["listXSTT"] as $item) {

                            if ($item["loto"] != "") {
                                echo '<span class="loto-number"  data-prize="' .
                                    $item["prizeId"] .
                                    '">' .
                                    $item["loto"] .
                                    "</span>";
                            } else {
                                echo '<span class="loto-number"><img width="15" height="15" src="https://i.gifer.com/ZKZg.gif"/></span>';
                            }
                            $count++;

                            if (
                                $count % 9 == 0 &&
                                $count < count($results[$i]["listXSTT"])
                            ) {
                                echo '</div></td></tr><tr><td class="results"><div class="quantity-of-number"  data-quantity="9">';
                            }
                        }

                        echo "</div></td></tr>";
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
                        <?php for ($j = 0; $j < 9; $j++) {
                            echo "<tr>";
                            echo '<td class="results">';
                            echo '<div class="quantity-of-number specs" data-quantity="4">';
                            echo '<span class="number wrap-text">' .
                                $j +
                                1 .
                                "</span>";
                            echo '<span class="number wrap-text">';
                            if (isset($results[$i]["resultHead"][$j])) {
                                foreach ($results[$i]["resultHead"][$j]
                                    as $item) {
                                    echo $item["loto"] . "; ";
                                }
                            } else {
                                echo "-";
                            }
                            echo "</span>";
                            echo '<span class="number wrap-text">' .
                                $j +
                                1 .
                                "</span>";
                            echo '<span class="number wrap-text">';
                            if (isset($results[$i]["resultEnd"][$j])) {
                                foreach ($results[$i]["resultEnd"][$j]
                                    as $item) {
                                    echo $item["loto"] . "; ";
                                }
                            } else {
                                echo "-";
                            }
                            echo "</span>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>


<?php } else {
    echo "<p>Không có dữ liệu kết quả xổ số.</p>";
}
?>
<script>
   $(document).ready(function() {
        $('#numberLengthFormMn input[type=radio][name=numberLength]').change(function() {
            var selectedLength = parseInt(this.value); // Chuyển đổi giá trị thành số nguyên
            $('#rsMn .number-rs').each(function() {
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