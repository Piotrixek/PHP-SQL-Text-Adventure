<?php
// expects $room $items $npcs vars
?>
<div class="section room-info">
    <div class="room-name"><?php echo htmlspecialchars($room['name']); ?></div>
    <div class="description"><?php echo nl2br(htmlspecialchars($room['description'])); ?></div>

    <?php // show items in room ?>
    <?php if (!empty($items)): ?>
        <h3>u see items:</h3>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['name']); ?>
                    <?php if (!empty($item['description'])): ?>
                         <small>- <?php echo htmlspecialchars($item['description']); ?></small>
                    <?php endif; ?>
                    <?php if ($item['takeable']): ?>
                        <a href="game.php?action=take&target=<?php echo urlencode($item['name']); ?>" class="action-link">[take]</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

     <?php // show npcs in room ?>
    <?php if (!empty($npcs)): ?>
        <h3>u see creatures:</h3>
        <ul>
            <?php foreach ($npcs as $npc): ?>
                <li>
                    <?php echo htmlspecialchars($npc['name']); ?>
                     <small>(hp: <?php echo $npc['health']; ?>) - <?php echo htmlspecialchars($npc['description']); ?></small>
                    <?php if (!empty($npc['dialogue'])): ?>
                         <a href="game.php?action=talk&target=<?php echo urlencode($npc['name']); ?>" class="action-link">[talk]</a>
                    <?php endif; ?>
                     <a href="game.php?action=attack&target=<?php echo urlencode($npc['name']); ?>" class="action-link">[attack]</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>