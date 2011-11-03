<?php
	$user = OpenVBX::getCurrentUser();
	$tenant_id = $user->values['tenant_id'];
	$ci =& get_instance();
	$selected = AppletInstance::getValue('list');
	$action = AppletInstance::getValue('action');
	$queries = explode(';', file_get_contents(dirname(dirname(dirname(__FILE__))) . '/db.sql'));
	foreach($queries as $query)
		if(trim($query))
			$ci->db->query($query);
	$lists = $ci->db->query(sprintf('SELECT id, name FROM subscribers_lists WHERE tenant = %d', $tenant_id))->result();
?>
<div class="vbx-applet">
<?php if(count($lists)): ?>
	<div class="vbx-full-pane">
		<h2>Dispatcher</h2>
		<p>If the message is from this group or user:</p>
<?php echo AppletUI::UserGroupPicker('dispatcher'); ?>
		<h3>Dispatch it to: </h3>
		<fieldset class="vbx-input-container">
			<select class="medium" name="list">
<?php foreach($lists as $list): ?>
				<option value="<?php echo $list->id; ?>"<?php echo $list->id == $selected ? ' selected="selected" ' : ''; ?>><?php echo $list->name; ?></option>
<?php endforeach; ?>
			</select>
		</fieldset>
		<h3>And then:</h3>
<?php echo AppletUI::DropZone('dispatched'); ?>
	</div>
	<div class="vbx-full-pane">
		<h2>Otherwise:</h2>
<?php echo AppletUI::DropZone('next'); ?>
	</div>
<?php else: ?>
	<div class="vbx-full-pane">
		<h3>You need to create a subscription list first.</h3>
	</div>
<?php endif; ?>
</div>
