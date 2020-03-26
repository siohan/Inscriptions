<?php
if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Inscriptions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
	
//debug_display($params, 'Parameters');
echo $this->StartTabheaders();
$active_tab = empty($params['active_tab']) ? '': $params['active_tab'];
	
	
	echo $this->SetTabHeader('ins', 'Inscriptions', ($active_tab == 'ins')?true:false);
	echo $this->SetTabHeader('emails', 'Notifications', ($active_tab == 'emails')?true:false);
	echo $this->SetTabHeader('config', 'Config', ($active_tab == 'config')?true:false);
	
	echo $this->EndTabHeaders();

	echo $this->StartTabContent();
	
	echo $this->StartTab('ins', $params);
	include(dirname(__FILE__).'/action.admin_inscriptions_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('emails', $params);
	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('config', $params);
	include(dirname(__FILE__).'/action.admin_options_tab.php');
   	echo $this->EndTab();

echo $this->EndTabContent();

?>


