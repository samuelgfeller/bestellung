<!--@todo separate year entries-->
<div class="dateBoxes">
    <?php foreach ($dates as $date) { ?>
        <a href="/artikel?datum=<?php echo $date ?>">
            <div class="dateBox">
                <p class="dateText"><?php echo $date ?></p>
            </div>
        </a>
    <?php } ?>
</div>