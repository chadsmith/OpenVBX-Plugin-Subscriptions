<?php
	$user = OpenVBX::getCurrentUser();
	$tenant_id = $user->values['tenant_id'];
	$ci =& get_instance();
	$queries = explode(';', file_get_contents(dirname(__FILE__).'/db.sql'));
	foreach($queries as $query)
		if(trim($query))
			$ci->db->query($query);
	if($remove = intval($_POST['remove'])){
		if($list = intval($_POST['list'])){
			if($ci->db->query(sprintf('SELECT id FROM subscribers_lists WHERE id=%d AND tenant=%d', $list, $tenant_id))->num_rows())
				$ci->db->delete('subscribers', array('id' => $remove, 'list' => $list));
		}
		else{
			$ci->db->delete('subscribers_lists', array('id' => $remove, 'tenant' => $tenant_id));
			if($ci->db->affected_rows())
				$ci->db->delete('subscribers', array('list' => $remove));
		}
		die();
	}
	if(($list = intval($_POST['list']))&&($number = $_POST['number'])&&(($message = $_POST['message'])||($id = intval($_POST['flow'])))&&$ci->db->query(sprintf('SELECT id FROM subscribers_lists WHERE id=%d AND tenant=%d', $list, $tenant_id))->num_rows()){
		$subscribers = $ci->db->query(sprintf('SELECT value FROM subscribers WHERE list=%d',$list))->result();
		require_once(APPPATH . 'libraries/twilio.php');
		$ci->twilio = new TwilioRestClient($ci->twilio_sid, $ci->twilio_token, $ci->twilio_endpoint);
		if($id&&($flow = OpenVBX::getFlows(array('id' => $id, 'tenant_id' => $tenant_id)))&&$flow[0]->values['data'])
			foreach($subscribers as $subscriber)
				$ci->twilio->request("Accounts/{$this->twilio_sid}/Calls", 'POST', array('Caller' => $number, 'Called' => $subscriber->value, 'Url' => site_url('twiml/start/voice/'.$id)));
		elseif($message)
			foreach($subscribers as $subscriber)
				$ci->twilio->request("Accounts/{$this->twilio_sid}/SMS/Messages", 'POST', array('To' => $subscriber->value, 'From' => $number, 'Body' => $message));
	}
	if($name = htmlentities($_POST['name']))
		$ci->db->insert('subscribers_lists', array(
			'tenant' => $tenant_id,
			'name' => $name
		));
	$lists = $ci->db->query(sprintf('SELECT id, name FROM subscribers_lists WHERE tenant=%d', $tenant_id))->result();
	$flows = OpenVBX::getFlows(array('tenant_id' => $tenant_id));
	OpenVBX::addJS('subscriptions.js');
?>
<style>
	.vbx-subscriptions h3 {
		font-size:16px;
		font-weight:bold;
		margin-top:0;
	}
	.vbx-subscriptions .list,
	.vbx-subscriptions .subscriber {
		clear:both;	
		width:95%;
		overflow:hidden;
		margin:0 auto;
		padding:5px 0;
		border-bottom:1px solid #eee;
	}
	.vbx-subscriptions .subscriber {
		display:none;
		background:#ccc;
	}
	.vbx-subscriptions .list span,
	.vbx-subscriptions .subscriber span {
		display:inline-block;
		width:20%;
		text-align:center;
		float:left;
		vertical-align:middle;
		line-height:24px;
	}
	.vbx-subscriptions .list a {
		text-decoration:none;
		color:#111;
	}
	.vbx-subscriptions form {
		display:none;
		padding:20px 5%;
		background:#eee;
		border-bottom:1px solid #ccc;
	}
	.vbx-subscriptions a.sms,
	.vbx-subscriptions a.call,
	.vbx-subscriptions a.delete {
		display:inline-block;
		height:24px;
		width:24px;
		text-indent:-999em;
		background:transparent url(/assets/i/standard-icons-sprite.png) no-repeat 0 0;
	}
	.vbx-subscriptions a.sms {
		background-position:-34px 0;
	}
	.vbx-subscriptions a.delete {
		background:transparent url(/assets/i/action-icons-sprite.png) no-repeat -68px 0;
	}
</style>
<div class="vbx-content-main">
	<div class="vbx-content-menu vbx-content-menu-top">
		<h2 class="vbx-content-heading">Subscription Lists</h2>
		<ul class="vbx-menu-items-right">
			<li class="menu-item"><button id="button-add-list" class="inline-button add-button"><span>Add List</span></button></li>
		</ul>
	</div><!-- .vbx-content-menu -->
    <div class="vbx-table-section vbx-subscriptions">
		<form method="post" action="">
			<h3>Add List</h3>
			<fieldset class="vbx-input-container">
				<label class="field-label">List Name
					<input type="text" class="medium" name="name" />
				</label>
				<p><button type="submit" class="submit-button"><span>Save</span></button></p>
			</fieldset>
		</form>
		<form method="post" action="">
			<h3>Send update to <span></span></h3>
			<fieldset class="vbx-input-container">
<?php if(count($callerid_numbers)): ?>
				<p>
					<label class="field-label">Caller ID<br/>
						<select name="number" class="medium">
<?php foreach($callerid_numbers as $number): ?>
							<option value="<?php echo $number->phone; ?>"><?php echo $number->name; ?></option>
<?php endforeach; ?>
						</select>
					</label>
				</p>
				<p><input type="hidden" name="list" /></p>
				<p>
					<label class="field-label">Message
						<textarea rows="20" cols="100" name="message" class="medium"></textarea>
					</label>
				</p>
				<p><button type="submit" class="submit-button"><span>Send</span></button></p>
<?php else: ?>
				<p>You do not have any phone numbers!</p>
<?php endif; ?>
			</fieldset>
		</form>
		<form method="post" action="">
			<h3>Auto dial <span></span></h3>
			<fieldset class="vbx-input-container">
<?php if(count($callerid_numbers)): ?>
<?php if(count($flows)): ?>
				<p>
					<label class="field-label">Flow<br/>
						<select name="flow" class="medium">
<?php foreach($flows as $flow): ?>
							<option value="<?php echo $flow->values['id']; ?>"><?php echo $flow->values['name']; ?></option>
<?php endforeach; ?>
						</select>
					</label>
				</p>
				<p>
					<label class="field-label">Caller ID<br/>
						<select name="number" class="medium">
<?php foreach($callerid_numbers as $number): ?>
							<option value="<?php echo $number->phone; ?>"><?php echo $number->name; ?></option>
<?php endforeach; ?>
						</select>
					</label>
				</p>
				<p><input type="hidden" name="list" /></p>
				<p><button type="submit" class="submit-button"><span>Call</span></button></p>
<?php else: ?>
				<p>You do not have any flows!</p>
<?php endif; ?>
<?php else: ?>
				<p>You do not have any phone numbers!</p>
<?php endif; ?>
			</fieldset>
		</form>
<?php if(count($lists)): ?>
		<div class="list">
			<h3>
				<span>Name</span>
				<span>Subscribed</span>
				<span>SMS</span>
				<span>Call</span>
				<span>Delete</span>
			</h3>
		</div>
<?php foreach($lists as $list):
	$subscribers=$ci->db->query(sprintf('SELECT id, value, joined FROM subscribers WHERE list=%d',$list->id))->result();
?>
		<div class="list" id="list_<?php echo $list->id; ?>">
			<p>
				<span><?php echo $list->name; ?></span>
				<span><a href="" class="subscribers"><?php echo count($subscribers); ?></a></span>
				<span><a href="" class="sms">SMS</a></span>
				<span><a href="" class="call">Call</a></span>
				<span><a href="" class="delete">X</a></span>
			</p>
		</div>
<?php if(count($subscribers)): ?>
<?php foreach($subscribers as $subscriber): ?>
		<div class="subscriber list_<?php echo $list->id; ?>" id="subscriber_<?php echo $list->id; ?>_<?php echo $subscriber->id; ?>">
			<p>
				<span><?php echo $subscriber->value; ?></span>
				<span><?php echo date('d-M-Y', $subscriber->joined); ?></span>
				<span>&nbsp;</span>
				<span>&nbsp;</span>
				<span><a href="" class="delete">X</a></span>
			</p>
		</div>
<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
    </div>
</div>