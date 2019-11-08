<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($_POST, 'Parameters');
if( !empty($_POST) ) {
        if( isset($_POST['cancel']) ) {
            $this->RedirectToAdminTab();
        }
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('pageid_inscriptions', $_POST['pageid_inscriptions']);
	$this->SetPreference('admin_email', $_POST['admin_email']);
	$this->SetTemplate('relanceemail', $_POST['relanceemail']);
	
	//on redirige !
	$this->RedirectToAdminTab('emails');
}
else
{
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('notifications.tpl'), null, null, $smarty);
	$tpl->assign('admin_email', $this->GetPreference('admin_email'));
	$tpl->assign('relanceemail', $this->GetTemplate('relanceemail'));
	$tpl->assign('pageid_inscriptions', $this->GetPreference('pageid_inscriptions'));
	$tpl->display();	
}
#
# EOF
#
?>