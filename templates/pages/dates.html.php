<!--@todo separate year entries-->
<div class="content">
    <?php
    foreach (array_unique($years) as $year){ ?>
    <div class="clearfix">
        <h3 style="border-bottom: 1px solid lightgray"><?= $year ?></h3>
        <div class="dateBoxes">
            <?php
            foreach ($dates as $date) {
            if (date('Y', strtotime($date)) === $year) { ?>
                <a href="/<?= $url ?>?datum=<?= $date ?>">
                    <div class="dateBox">
                        <p class="dateText"><?php echo $date ?></p>
                    </div>
                </a>
            <?php }
            } ?>
        </div>
    </div>
    <?php  } ?>
</div>

