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
//	echo $this->SetTabHeader('config', 'Configuration', ('config' == $tab)?true:false);	
	echo $this->EndTabHeaders();

	echo $this->StartTabContent();
	
	
	echo $this->StartTab('mess', $params);
	include(dirname(__FILE__).'/action.admin_inscriptions_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('emails', $params);
	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();
/*
	echo $this->StartTab('config', $params);
	include(dirname(__FILE__).'/action.admin_config_tab.php');
    	//include(dirname(__FILE__).'/action.admin_joueurs_tab.php');
   	echo $this->EndTab();
*/

echo $this->EndTabContent();

?>

