<?php
$ci =& get_instance();
$list = AppletInstance::getValue('list');
$action = AppletInstance::getValue('action');
$direction = isset($_REQUEST['Direction']) ? $_REQUEST['Direction'] : 'inbound';

if(!empty($_REQUEST['From'])) {
	$number = normalize_phone_to_E164(in_array($direction, array('inbound', 'incoming')) ? $_REQUEST['From'] : $_REQUEST['To']);
	if('add' == $action) {
		if(!$ci->db->query(sprintf('SELECT id FROM subscribers WHERE list = %d AND value = %s', $list, $number))->num_rows())
			$ci->db->insert('subscribers', array(
				'list' => $list,
				'value' => $number,
				'joined' => time()
			));
	}
	else
		$ci->db->delete('subscribers', array('list' => $list, 'value' => $number));
}

$response = new TwimlResponse;

$next = AppletInstance::getDropZoneUrl('next');
if(!empty($next))
	$response->redirect($next);

$response->respond();