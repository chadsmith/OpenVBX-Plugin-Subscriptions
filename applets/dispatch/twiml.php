<?php
$ci =& get_instance();
$dispatcher = AppletInstance::getUserGroupPickerValue('dispatcher');
$list = AppletInstance::getValue('list');
$sender = normalize_phone_to_E164($_REQUEST['From']);
$number = normalize_phone_to_E164($_REQUEST['To']);
$body = $_REQUEST['Body'];
$dispatch = false;

if(is_null($dispatcher))
	$dispatch = true;
else
	switch(get_class($dispatcher)){
		case 'VBX_User':
			foreach($dispatcher->devices as $device)
				if($sender == $device->value)
					$dispatch = true;
			break;
		case 'VBX_Group':
			foreach($dispatcher->users as $user){
				$user = VBX_User::get($user->user_id);
				foreach($user->devices as $device)
					if($sender == $device->value)
						$dispatch = true;
			}
		break;
	}

$response = new Response();

if($dispatch){
	$subscribers = $ci->db->query(sprintf('SELECT value FROM subscribers WHERE list=%d',$list))->result();
	require_once(APPPATH . 'libraries/twilio.php');
	$ci->twilio = new TwilioRestClient($ci->twilio_sid, $ci->twilio_token, $ci->twilio_endpoint);
	if($body&&count($subscribers))
		foreach($subscribers as $subscriber)
			$ci->twilio->request("Accounts/{$ci->twilio_sid}/SMS/Messages", 'POST', array('From' => $number, 'To' => $subscriber->value, 'Body' => $body));
	$dispatched = AppletInstance::getDropZoneUrl('dispatched');
	if(!empty($dispatched))
		$response->addRedirect($dispatched);
}
else{
	$next = AppletInstance::getDropZoneUrl('next');
	if(!empty($next))
		$response->addRedirect($next);
}

$response->Respond();
