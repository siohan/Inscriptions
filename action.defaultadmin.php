<?php
if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Inscriptions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
	
//debug_display($params, 'Parameters');
echo $this->StartTabheaders();

if(isset($params['activetab']) && !empty($params['activetab']))
  {
    $tab = $params['activetab'];
  } else {
  $tab = 'ins';
 }	
	
	echo $this->SetTabHeader('ins', 'Inscriptions', ('ins' == $tab)?true:false);
	echo $this->SetTabHeader('emails', 'Emails', ('emails' == $tab)?true:false);
//	echo $this->SetTabHeader('auto_process', 'Alertes auto', ('auto_process' == $tab)?true:false);	
	
	echo $this->EndTabHeaders();

	echo $this->StartTabContent();
	
	
	echo $this->StartTab('ins', $params);
	include(dirname(__FILE__).'/action.admin_inscriptions_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('emails', $params);
	include(dirname(__FILE__).'/action.admin_notifications_tab.php');
   	echo $this->EndTab();
/*
	echo $this->StartTab('auto_process', $params);
	include(dirname(__FILE__).'/action.auto_process_tab.php');
   	echo $this->EndTab();
*/

echo $this->EndTabContent();

?>


