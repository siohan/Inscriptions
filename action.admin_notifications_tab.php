<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
if(isset($params['submit']))
{
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('pageid_inscriptions', $params['pageid_inscriptions']);
	$this->SetPreference('admin_email', $params['adminemail']);
	$this->SetTemplate('relanceemail', $params['relanceemail']);
//	$this->SetTemplate('send_email', $params['send_email']);
	//on redirige !
	$this->RedirectToAdminTab('notifications');
}
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_notifications_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());
$smarty->assign('pageid_inscriptions', $this->CreateInputText($id, 'pageid_inscriptions',$this->GetPreference('pageid_inscriptions'), 50, 150));
$smarty->assign('input_adminemail', $this->CreateInputText($id, 'adminemail',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('relanceemail', $this->CreateSyntaxArea($id, $this->GetTemplate('relanceemail'), 'relanceemail', '', '', '', '', 80, 7));
//$smarty->assign('send_email', $this->CreateSyntaxArea($id, $this->GetTemplate('send_email'), 'send_email', '', '', '', '', 80, 7));

$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('notifications.tpl');
#
# EOF
#
?>