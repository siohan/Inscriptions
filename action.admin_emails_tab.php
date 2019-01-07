<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
if(isset($params['submit']))
{
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('admin_email', $params['adminemail']);
	$this->SetPreference('email_inscription_subject', $params['emailinscriptionsubject']);
	$this->SetTemplate('newinscriptionemail_Sample', $params['inscription_mail_template']);
	
	//on redirige !
	$this->RedirectToAdminTab('notifications');
}
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_emails_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());
$smarty->assign('input_emailinscriptionsubject', $this->CreateInputText($id, 'emailinscriptiontionsubject',$this->GetPreference('email_inscription_subject'), 50, 150));
$smarty->assign('input_adminemail', $this->CreateInputText($id, 'adminemail',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('input_emailinscriptionbody', $this->CreateSyntaxArea($id, $this->GetTemplate('newinscriptionemail_Sample'), 'inscription_mail_template', '', '', '', '', 80, 7));
$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('notifications.tpl');
#
# EOF
#
?>