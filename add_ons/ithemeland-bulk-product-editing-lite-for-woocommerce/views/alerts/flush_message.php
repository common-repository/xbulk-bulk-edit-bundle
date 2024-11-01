<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!is_array($flush_message) || !isset($flush_message['message'])) {
    return false;
}
?>

<div class="wcbel-flush-message <?php echo (isset($flush_message['type'])) ? 'wcbel-flush-message-' . esc_attr($flush_message['type']) : 'wcbel-flush-message-default' ?>">
    <span><?php echo esc_html($flush_message['message']); ?></span>
</div>