<form name="settings_update" id="settings_update" method="post" action="<?= base_url() ?>api/settings/modify" enctype="multipart/form-data">
<div class="content_wrap_inner">

	<div class="content_inner_top_right">
		<h3>App</h3>
		<p><?= form_dropdown('enabled', config_item('enable_disable'), $settings['paypal']['enabled']) ?></p>
		<p><a href="<?= base_url() ?>api/<?= $this_module ?>/uninstall" id="app_uninstall" class="button_delete">Uninstall</a></p>
	</div>
	
	<h3>Permissions</h3>

	<p>Create
	<?= form_dropdown('create_permission', config_item('users_levels'), $settings['paypal']['create_permission']) ?>
	</p>

	<p>Publish
	<?= form_dropdown('publish_permission', config_item('users_levels'), $settings['paypal']['publish_permission']) ?>	
	</p>

	<p>Manage All
	<?= form_dropdown('manage_permission', config_item('users_levels'), $settings['paypal']['manage_permission']) ?>	
	</p>
	
	<span class="item_separator"></span>

  <div class="content_wrap_inner">
	
    <h3>Account Info</h3>
    <p>Sandboxed
    <?= form_dropdown('sandbox',array('true' => 'TRUE', 'false' => 'FALSE'),'true') ?>
    </p>
    <p>Username
    <input type="text" name="username" value="<?= $settings['paypal']['username'] ?>">
    </p>
    <p>Password
    <input type="text" name="password" value="<?= $settings['paypal']['password'] ?>">
    </p>
    <p>Api Signature
    <input type="text" name="signature" value="<?= $settings['paypal']['signature'] ?>">
    </p>

    
	</div>


	<input type="hidden" name="module" value="<?= $this_module ?>">
	<p><input type="submit" name="save" value="Save" /></p>
	
	

</div>
</form>

<?= $shared_ajax ?>