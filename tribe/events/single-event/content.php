<?php

/**
 * Single Event Content Template Part
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/single-event/content.php
 *
 * See more documentation about our Blocks Editor templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 4.7
 *
 */
?>

<?php $event_id = $this->get('post_id'); ?>
<div class="post-inner" id="post-inner">
	<?php $this->template('single-event/back-link'); ?>
	<?php tribe_the_content(null, false, $event_id); ?>
</div>