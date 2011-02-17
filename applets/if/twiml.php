<?php
$ci =& get_instance();
$list = AppletInstance::getValue('list');
$number = normalize_phone_to_E164($_REQUEST['From']);
$subscriber = $ci->db->query(sprintf('SELECT id FROM subscribers WHERE list=%d AND value=%s', $list, $number))->num_rows() > 0;

$response = new Response();

$next = AppletInstance::getDropZoneUrl($subscriber ? 'pass' : 'fail');
if(!empty($next))
	$response->addRedirect($next);

$response->Respond();
