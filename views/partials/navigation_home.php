<h2 class="content_title"><img src="<?= $modules_assets ?>paypal_32.png"> Paypal</h2>
<ul class="content_navigation">
	<?= navigation_list_btn('home/paypal/make_payment', 'Make a Payment') ?>
	<?= navigation_list_btn('home/paypal/custom', 'Custom') ?>
	<?php if ($logged_user_level_id <= 2) echo navigation_list_btn('home/paypal/manage', 'Manage', $this->uri->segment(4)) ?>
</ul>