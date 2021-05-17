<?php

if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Inscriptions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

$db =& $this->GetDb();
global $themeObject;
$gp_ops = new groups;
$liste_groupes = $gp_ops->liste_groupes_dropdown();

//debug_display($params, 'Parameters');
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
	$insc_ops = new T2t_inscriptions;
	$liste_unite = array('Heures'=>'Heures', 'Jours'=>'Jours');
	$details_insc = $insc_ops->details_inscriptions($record_id);
	$nom = $details_insc['nom'];
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('view_details_inscription.tpl'), null, null, $smarty);
	$tpl->assign('id_inscription', $record_id);
	$tpl->assign('nom', $nom);
	$tpl->assign('description', $details_insc['description']);
	$tpl->assign('group_notif', $details_insc['group_notif']);
	//$tpl->assign('liste_id',$details_insc['liste_id']);
	$tpl->assign('actif', $details_insc['actif']);
	$tpl->assign('groupe', $details_insc['groupe']);
	$tpl->assign('liste_groupes', $liste_groupes);
	$tpl->assign('date_debut', $details_insc['date_debut']);
	$tpl->assign('ext', $details_insc['ext']);
	$tpl->assign('date_fin', $details_insc['date_fin']);
	$tpl->assign('date_limite', $details_insc['date_limite']);
	$tpl->assign('start_collect', $details_insc['start_collect']);
	$tpl->assign('end_collect', $details_insc['end_collect']);
	
	if($details_insc['occurence'] >0)
	{
		if(true == is_float($details_insc['occurence']/86400))
		{
			//on met le résultat en heures
			$result = $details_insc['occurence']/3600;
			$unite = 'Heures';
		
		}
		else
		{
			//on met le résultat en jours
			$result = $details_insc['occurence']/86400;
			$unite = 'Jours';
		
		}
	}
	else
	{
		$result = 0;
		$unite = "Heures";
	}
	
	$tpl->assign('occurence', $details_insc['occurence']);
	$tpl->assign('result', $result);
	$tpl->assign('unite', $unite);
	$tpl->assign('liste_unite', $liste_unite);
	$tpl->display();
	
	$smarty->assign('id_inscription', $record_id);
}
else
{
	//redir
}

//SELECT * FROM ping_module_ping_recup_parties AS rec right JOIN ping_module_ping_joueurs AS j ON j.licence = rec.licence  ORDER BY j.id ASC
$query= "SELECT id,id_inscription,nom, description, date_debut, date_fin, actif, tarif, jauge FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ? ORDER BY date_debut ASC";

$dbresult= $db->Execute($query, array($record_id));
$rowclass= 'row1';
$rowarray= array();
if ($dbresult && $dbresult->RecordCount() > 0)
  {
	$insc_ops = new T2t_inscriptions;
    while ($row= $dbresult->FetchRow())
      {
	//$id_envoi = (int) $row['id_envoi'];
	$onerow= new StdClass();
	$onerow->rowclass= $rowclass;
	$actif = $row['actif'];
	if($actif == 0)
	{
		$onerow->actif= $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
	}
	else
	{
		$onerow->actif= $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
	}
	
	$onerow->raz = $this->CreateLink($id, 'inscription', $returnid, 'Raz',array("obj"=>"delete_users_in_option", "id_inscription"=>$row['id_inscription'],"id_option"=>$row['id']));
	$onerow->id= $row['id'];
	$onerow->id_inscription= $row['id_inscription'];
	$onerow->nom= $row['nom'];
	$onerow->description= $row['description'];
	$onerow->date_debut= $row['date_debut'];
	$onerow->date_fin= $row['date_fin'];
	$onerow->tarif = $row['tarif'];
	$onerow->inscrits = $insc_ops->count_users_in_option($row['id']);
	$onerow->jauge = $row['jauge'];
	$onerow->duplicate= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/copy.gif', $this->Lang('copy'), '', '', 'systemicon'),array('obj'=>'duplicate_option','id_inscription'=>$row['id_inscription'],'record_id'=>$row['id']));
	$onerow->editlink= $this->CreateLink($id, 'add_edit_option', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array('id_inscription'=>$row['id_inscription'],'record_id'=>$row['id']));
	$onerow->view= $this->CreateLink($id, 'admin_resp_by_option', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'),array('id_option'=>$row['id'], 'id_inscription'=>$row['id_inscription']));
	$onerow->delete= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array('obj'=>'delete_option','record_id'=>$row['id'], 'id_inscription'=>$row['id_inscription']));
	$onerow->assign_users = $this->CreateLink($id, 'assign_users', $returnid,$themeObject->DisplayImage('icons/system/groupassign.gif', $this->Lang('assign'), '', '', 'systemicon'), array('id_inscription'=>$row['id_inscription'],'id_option'=>$row['id']));
	($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
	$rowarray[]= $onerow;
      }
  }

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);
$smarty->assign('form2start',
		$this->CreateFormStart($id,'mass_action',$returnid));
$smarty->assign('form2end',
		$this->CreateFormEnd());
$articles = array("Activer"=>"activate_option", "Désactiver"=>"desactivate_option","Supprimer"=>"delete_option");
$smarty->assign('actiondemasse',
		$this->CreateInputDropdown($id,'actiondemasse',$articles));
$smarty->assign('submit_massaction',
		$this->CreateInputSubmit($id,'submit_massaction',$this->Lang('apply_to_selection'),'','',$this->Lang('areyousure_actionmultiple')));

echo $this->ProcessTemplate('options.tpl');


#
# EOF
#
?>