<?php
// expects $inventory array from session
?>
<div class="section inventory">
    <h3>ur carrying:</h3>
    <?php if (empty($inventory)): ?>
        <p>nothin but lint</p>
    <?php else: ?>
        <ul>
            <?php
            $pdo = getdb(); // need db connection to get item details
            foreach ($inventory as $invitem_name):
                $itemdata = getitemdata($pdo, $invitem_name); // get full item data
            ?>
                <li>
                    <?php echo htmlspecialchars($invitem_name); ?>
                    <?php // show bonuses
                        $bonuses = '';
                        if ($itemdata['attack_bonus'] > 0) $bonuses .= ' atk+'. $itemdata['attack_bonus'];
                        if ($itemdata['defense_bonus'] > 0) $bonuses .= ' def+'. $itemdata['defense_bonus'];
                        if ($bonuses) echo ' <small>('.trim($bonuses).')</small>';
                    ?>
                    <?php if ($itemdata && $itemdata['use_effect']): ?>
                         <a href="game.php?action=use&target=<?php echo urlencode($invitem_name); ?>" class="action-link">[use]</a>
                    <?php endif; ?>
                    <?php if ($itemdata && $itemdata['can_drop']): ?>
                        <a href="game.php?action=drop&target=<?php echo urlencode($invitem_name); ?>" class="action-link">[drop]</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>