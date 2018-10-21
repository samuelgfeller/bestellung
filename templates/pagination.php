<?php

//ceil rounds up (5.1 = 6)
$total_pages = ceil($total_records / $record_per_page);
$start_loop = 1;

?>
<nav id="paginationNav" aria-label="Page navigation">
    <div class="pagination">
        <?php
        if ($page > 1) {
            echo "<a href='" . $url . "/1'><<</a>";
            echo "<a href='" . $url . "/" . ($page - 1) . "'><</a>";
        }
        for ($i = 1/*$start_loop*/; $i <= $total_pages; $i++) {
            $class = $i == $page ? 'active' : '';
            echo "<a class='".$class."' href='" . $url . "/" . $i . "'>" . $i . "</a>";
        }
        if ($page < $total_pages) {
            echo "<a href='" . $url . "/" . ($page + 1) . "'>></a>";
            echo "<a href='" . $url . "/" . $total_pages . "'>>></a>";
        } ?>

    </div>
</nav>
