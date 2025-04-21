<?php
// expects $message variable passed in
if (!empty($message)):
    // basic check for error words could be fancier
    $msg_class = (strpos(strtolower($message), 'died') !== false || strpos(strtolower($message), 'cant') !== false) ? 'message error' : 'message';
?>
    <div class="<?php echo $msg_class; ?>"><?php echo $message; // message already processed safe to echo ?></div>
<?php endif; ?>