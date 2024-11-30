<?php
    foreach ($results as $key => $result){

        ?>
        <table class="table-lottery table table-striped">
        <caption>
            <h2>Ngày <?php echo $key; ?></h2>
        </caption>
        <tbody>
            <?php
            
            uasort($result, function ($a, $b) {
                if ($a['prizeId'] == $b['prizeId']) {
                    return $a['prizeColumn'] <=> $b['prizeColumn'];
                }
                return $a['prizeId'] <=> $b['prizeId'];
            });
            $groupedResults =  array();
            foreach ($result as $item) {
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
            ?>
        </tbody>
    </table>
    <?php
    }
?>