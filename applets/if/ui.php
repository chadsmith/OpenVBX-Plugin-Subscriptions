<?php
	$user = OpenVBX::getCurrentUser();
	$tenant_id = $user->values['tenant_id'];
	$ci =& get_instance();
	$selected = AppletInstance::getValue('list');
	$queries = explode(';', file_get_contents(dirname(dirname(dirname(__FILE__))).'/db.sql'));
	foreach($queries as $query)
		if(trim($query))
			$ci->db->query($query);
	$lists = $ci->db->query(sprintf('SELECT id, name FROM subscribers_lists WHERE tenant=%d', $tenant_id))->result();
?>
<div class="vbx-applet">
<?php if(count($lists)): ?>
	<div class="vbx-full-pane">
		<h3>List</h3>
		<fieldset class="vbx-input-container">
				<select class="medium" name="list">
<?php foreach($lists as $list): ?>
					<option value="<?php echo $list->id; ?>"<?php echo $list->id==$selected?' selected="selected" ':''; ?>><?php echo $list->name; ?></option>
<?php endforeach; ?>
				</select>
		</fieldset>
	</div>
	<h2>Members</h2>
	<div class="vbx-full-pane">
		<?php echo AppletUI::DropZone('pass'); ?>
	</div>
	<h2>Non-members</h2>
	<div class="vbx-full-pane">
		<?php echo AppletUI::DropZone('fail'); ?>
	</div>
<?php else: ?>
	<div class="vbx-full-pane">
		<h3>You need to create a subscription list first.</h3>
	</div>
<?php endif; ?>
</div>
