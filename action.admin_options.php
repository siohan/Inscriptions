<?php

if( !isset($gCms) ) exit;

$db =& $this->GetDb();
global $themeObject;


if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
	
}
else
{
	//redir
}
$smarty->assign('add_edit', 
		$this->CreateLink($id, 'add_edit_option', $returnid, 'Ajouter une option',	array("id_inscription"=>$record_id)));		
$dbresult= array();
//SELECT * FROM ping_module_ping_recup_parties AS rec right JOIN ping_module_ping_joueurs AS j ON j.licence = rec.licence  ORDER BY j.id ASC
$query= "SELECT id,id_inscription,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif, tarif, groupe, categ FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ?";//" ORDER BY date_debut DESC";

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
	
	$onerow->view = $this->CreateLink($id, 'sent_back', $returnid, $themeObject->DisplayImage('icons/topfiles/cmsmailer.gif', 'Renvoyer le mail', '', '', 'systemicon'),array("record_id"=>$row['id']));
	$onerow->id= $row['id'];
	$onerow->id_inscription= $row['id_inscription'];
	$onerow->nom= $row['nom'];
	$onerow->description= $row['description'];
	$onerow->date_debut= $row['date_debut'];
	$onerow->date_fin= $row['date_fin'];
	$onerow->heure_debut= $row['heure_debut'];
	$onerow->heure_fin= $row['heure_fin'];
	$onerow->tarif = $row['tarif'];
	$onerow->inscrits = $this->CreateLink($id, 'assign_users', $returnid,$insc_ops->count_users_in_option($row['id']), array("id_inscription"=>$row['id_inscription'], "id_option"=>$row['id']));
	$categ = $row['categ'];
	if($categ == 1)
	{
		$onerow->categ = $this->CreateLink($id, 'add_options_categ', $returnid, $themeObject->DisplayImage('icons/system/permissions.gif', $this->Lang('new'), '', '', 'systemicon'),array("record_id"=>$row['id']));
	}
	else
	{
		$onerow->categ = $this->CreateLink($id, 'add_options_categ', $returnid, $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('new'), '', '', 'systemicon'),array("record_id"=>$row['id']));
	}
//	$onerow->renvoyer = $this->CreateLink($id, 'sent_back', $returnid, $themeObject->DisplayImage('icons/system/new.gif', $this->Lang('new'), '', '', 'systemicon'),array("message_id"=>$row['id']));	
	$onerow->editlink= $this->CreateLink($id, 'add_edit_options', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array('record_id'=>$row['id']));
	($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
	$rowarray[]= $onerow;
      }
  }

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);

echo $this->ProcessTemplate('options.tpl');


#
# EOF
#
?>