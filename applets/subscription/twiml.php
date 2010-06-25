<?php
$ci =& get_instance();
$list = AppletInstance::getValue('list');
$action = AppletInstance::getValue('action');
$number = 'voice' == AppletInstance::getFlowType() ? normalize_phone_to_E164($_REQUEST['Caller']) : normalize_phone_to_E164($_REQUEST['From']);

if('add'==$action){
	if(!$ci->db->query(sprintf('SELECT id FROM subscribers WHERE list=%d AND value=%s',$list,$number))->num_rows())
		$ci->db->insert('subscribers', array(
			'list' => $list,
			'value' => $number,
			'joined' => time()
		));
}
else
	$ci->db->delete('subscribers', array('list' => $list, 'value' => $number));

$response = new Response();

$next = AppletInstance::getDropZoneUrl('next');
if(!empty($next))
	$response->addRedirect($next);

$response->Respond();