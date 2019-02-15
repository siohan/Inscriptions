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
$insc_ops = new T2t_inscriptions;

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
	
	//change le choix_multi (0 ou 1)
	case "choix_multi" :
	{
		$db = cmsms()->GetDb();
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
		}
		if(isset($params['act']) && $params['act'] != '')
		{
			$act = $params['act'];			
		}
		$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET choix_multi = ? WHERE id = ?";
		$dbresult = $db->Execute($query, array($act, $record_id));
	}
	$this->RedirectToAdminTab('inscriptions');
	break;
	
	//
	case "delete_inscription":
	{
		$db = cmsms()->GetDb();
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
			$del = $insc_ops->delete_inscription($record_id);
			if(true === $del)
			{
				$del_opt = $insc_ops->delete_options($record_id);
				if(true === $del_opt)
				{
					$del_belongs = $insc_ops->delete_inscription_belongs($record_id);
				}
			}
		}
		
		
	}
	case "delete_options":
	{
		//supprime toutes les options d'une inscription
		$db = cmsms()->GetDb();
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
		}
		$del_opt = $insc_ops->delete_options($record_id);
		if(true === $del_opt)
		{
			//supprime les adhésions à une (et une seule) option donnée
			$del_belongs = $insc_ops->delete_option_belongs($record_id);
		}
		
	}
	case "delete_option":
	{
		//supprime une seule option d'une inscription
		$db = cmsms()->GetDb();
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
		}
		$del_opt = $insc_ops->delete_option($record_id);
		if(true === $del_opt)
		{
			$del_belongs = $insc_ops->delete_users_in_option($record_id);
		}
		$this->RedirectToAdminTab('inscriptions');
	}
	case "delete_reponse":
	{
		$db = cmsms()->GetDb();
		$error = 0;
		if(isset($params['id_inscription']) && $params['id_inscription'] != '')
		{
			$id_inscription = $params['id_inscription'];
		}
		if(isset($params['genid']) && $params['genid'] != '')
		{
			$genid = $params['genid'];
		}
		else
		{
			$error++;
		}
		if(isset($params['id_option']) && $params['id_option'] != '')
		{
			$id_option = $params['id_option'];
		}
		else
		{
			$error++;
		}
		if($error < 1)
		{
			$del_rep = $insc_ops->delete_user_in_option($id_option, $genid);
			if(true === $del_rep)
			{
				$this->SetMessage('Choix supprimé');
			}
			else
			{
				$this->SetMessage('Choix non supprimé');
			}
		}
		$this->Redirect($id, 'admin_reponses', $returnid, array('id_inscription'=>$id_inscription));
	}
}