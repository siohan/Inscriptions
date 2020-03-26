<?php
set_time_limit(300);
if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
$aujourdhui = date('Y-m-d');
$duplication_time = $this->GetPreference('duplication_time');
$insc_ops = new T2t_inscriptions;
debug_display($params, 'Parameters');
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
			$del_belongs = $insc_ops->delete_inscription_belongs($record_id);
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
	case "delete_reponse"://supprime le choix d'une option d'un user = désinscription d'une option
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
		$this->Redirect($id, 'assign_user_idoption', $returnid, array('id_inscription'=>$id_inscription, 'genid'=>$genid, 'details'=>1));
	}
	
	//ajoute un choix d'une option pour un utilisateur
	case "add_reponse"://supprime le choix d'une option d'un user = désinscription d'une option
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
			$del_rep = $insc_ops->add_reponse($id_inscription,$id_option, $genid);
			if(true === $del_rep)
			{
				$this->SetMessage('Choix ajouté');
			}
			else
			{
				$this->SetMessage('Choix non ajouté');
			}
		}
		$this->Redirect($id, 'assign_user_idoption', $returnid, array('id_inscription'=>$id_inscription, 'genid'=>$genid, 'details'=>1));
	}
	
	case "refresh" : 
		if(isset($params['id_inscription']) && $params['id_inscription'] != '')
		{
			$id_inscription = $params['id_inscription'];
			$del = $insc_ops->delete_all_responses($id_inscription);
		}
		$this->RedirectToAdminTab('inscriptions');
	break;
	
	case "duplicate" :
	if(isset($params['record_id']) && $params['record_id'] != '')
	{
		$id_inscription = $params['record_id'];
		$details = $insc_ops->details_inscriptions($id_inscription);
		$date_limite = $details['date_limite'] + $duplication_time;
		$date_debut = $details['date_debut']+$duplication_time;
		$date_fin = $details['date_fin'] + $duplication_time;
		$timbre = time();
		$add_insc = $insc_ops->duplicate_inscription($details['nom'], $details['description'], $date_limite, $date_debut, $date_fin, $details['actif'], $details['groupe'], $details['group_notif'], $details['choix_multi'],$timbre, $details['occurence'], $details['start_collect'], $details['collect_mode'], $details['end_collect']);
		//var_dump($add_insc);
		if(FALSE != $add_insc)
		{
			//il faut récupérer l'id du dernier élément inséré.
			
			$details_opts = $insc_ops->duplicate_options($id_inscription, $add_insc,$duplication_time);
		}
		else
		{
			//on envoie un mesage d'erreur
		}
		
	}
	$this->RedirectToAdminTab('inscriptions');
	break;
	
	case "duplicate_option" :
	if(isset($params['record_id']) && $params['record_id'] != '')
	{
		$copy = $insc_ops->duplicate_option($params['record_id'],$duplication_time);
		var_dump($copy);
	}
	if(isset($params['id_inscription']) && $params['id_inscription'] != '')
	{
		$this->Redirect($id, 'view_details_inscription', $returnid, array('record_id'=>$params['id_inscription'] ));
	}
	break;
}