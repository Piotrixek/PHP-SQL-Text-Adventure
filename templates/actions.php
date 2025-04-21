<?php
// expects $room var
?>
<div class="section actions">
    <h3>actions:</h3>
    <ul>
        <li><a href="game.php?action=look">look around again</a></li>
        <?php // check exits n make links ?>
        <?php if ($room['north_id']): ?>
            <li><a href="game.php?action=go&target=north">go north</a><?php
                // hint if requires item
                $nextroom = getroomdata(getdb(), $room['north_id']);
                if (!empty($nextroom['required_item'])) echo ' <small>(needs '.htmlspecialchars($nextroom['required_item']).'?)</small>';
            ?></li>
        <?php endif; ?>
        <?php if ($room['south_id']): ?>
            <li><a href="game.php?action=go&target=south">go south</a></li>
        <?php endif; ?>
        <?php if ($room['east_id']): ?>
            <li><a href="game.php?action=go&target=east">go east</a></li>
        <?php endif; ?>
        <?php if ($room['west_id']): ?>
            <li><a href="game.php?action=go&target=west">go west</a></li>
        <?php endif; ?>
    </ul>
</div>