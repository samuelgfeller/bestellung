<div class="content">
    <?php
    foreach (array_unique($years) as $year) {
        // The years should not be shown if there are no dates so it checks for all years if a date inside has the same year
        $showYear = false;
        foreach ($dates as $date) {
            if (date('Y', strtotime($date)) === $year) {
                $showYear = true;
            }
        }
        if ($showYear) {
            ?>
            <div class="clearfix">
                <h3 style="border-bottom: 1px solid lightgray"><?= $year ?></h3>
                <div class="dateBoxes">
                    <?php
                    $dateWrongOrder = [];
                    // The dates have to be displayed with the first month at the left so I iterate first on all dates in a specific year
                    // to reverse them later. It is not possible to do the array_reverse earlier because the newer year has to be at the top
                    foreach ($dates as $date) {
                        // Check if year of the date corresponds with the year which it actually gets iterated
                        if (date('Y', strtotime($date)) === $year) {
                            // Add the date to the array $dateA
                            $dateWrongOrder[] = $date;
                        }
                    }
                    // Order the date to display the first month first
                    $dateOrdered = array_reverse($dateWrongOrder);
                    foreach ($dateOrdered as $date) {
                        if (date('Y', strtotime($date)) === $year) { ?>
                            <a href="<?= $url ?>?datum=<?= $date ?>">
                                <div class="dateBox">
                                    <p class="dateText"><?php echo $date ?></p>
                                </div>
                            </a>
                        <?php }
                    } ?>
                </div>
            </div>
        <?php }
    } ?>
</div>

