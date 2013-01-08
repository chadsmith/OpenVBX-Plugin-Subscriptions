<?php
$ci =& get_instance();
$list = AppletInstance::getValue('list');
$direction = isset($_REQUEST['Direction']) ? $_REQUEST['Direction'] : 'inbound';

if(!empty($_REQUEST['From'])) {
	$number = normalize_phone_to_E164(in_array($direction, array('inbound', 'incoming')) ? $_REQUEST['From'] : $_REQUEST['To']);
	$subscriber = $ci->db->query(sprintf('SELECT id FROM subscribers WHERE list = %d AND value = %s', $list, $number))->num_rows() > 0;
	$next = AppletInstance::getDropZoneUrl($subscriber ? 'pass' : 'fail');
}

$response = new TwimlResponse;

if(!empty($next))
	$response->redirect($next);

$response->respond();