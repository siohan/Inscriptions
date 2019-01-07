<?php
set_time_limit(300);
if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Adherents use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
$aujourdhui = date('Y-m-d');

if(isset($params['obj']) && $params['obj'] != '')
{
	$obj = $params['obj'];
}
else
{
	//redir
}
switch($obj)
{
	case "activate_desactivate" :
		$db = cmsms()->GetDb();
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
		}
		if(isset($params['act']) && $params['act'] != '')
		{
			$act = $params['act'];
			
		}
		$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET actif = ? WHERE id = ?";
		$dbresult = $db->Execute($query, array($act, $record_id));
		
		$this->RedirectToAdminTab('inscriptions');
	break;
}