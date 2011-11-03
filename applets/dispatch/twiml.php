<?php
$ci =& get_instance();
$dispatcher = AppletInstance::getUserGroupPickerValue('dispatcher');
$list = AppletInstance::getValue('list');
$dispatch = false;

if(!empty($_REQUEST['From'])) {
	$sender = normalize_phone_to_E164($_REQUEST['From']);
	$number = normalize_phone_to_E164($_REQUEST['To']);
	$body = $_REQUEST['Body'];

	if(is_null($dispatcher))
		$dispatch = true;
	else
		switch(get_class($dispatcher)) {
			case 'VBX_User':
				foreach($dispatcher->devices as $device)
					if($sender == $device->value)
						$dispatch = true;
				break;
			case 'VBX_Group':
				foreach($dispatcher->users as $user) {
					$user = VBX_User::get($user->user_id);
					foreach($user->devices as $device)
						if($sender == $device->value)
							$dispatch = true;
				}
		}
}

$response = new TwimlResponse;

if($dispatch) {
	$subscribers = $ci->db->query(sprintf('SELECT value FROM subscribers WHERE list = %d', $list))->result();
	require_once(APPPATH . 'libraries/Services/Twilio.php');
	$service = new Services_Twilio($ci->twilio_sid, $ci->twilio_token);
	if($body && count($subscribers))
		foreach($subscribers as $subscriber)
			$service->account->sms_messages->create($number, $subscriber->value, $body);
	$dispatched = AppletInstance::getDropZoneUrl('dispatched');
	if(!empty($dispatched))
		$response->redirect($dispatched);
}
else {
	$next = AppletInstance::getDropZoneUrl('next');
	if(!empty($next))
		$response->redirect($next);
}

$response->respond();
