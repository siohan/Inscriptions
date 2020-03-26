<?php

if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Inscriptions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

$db =& $this->GetDb();
global $themeObject;

//debug_display($params, 'Parameters');
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$record_id = $params['record_id'];
	$insc_ops = new T2t_inscriptions;
	$details_insc = $insc_ops->details_inscriptions($record_id);
	$nom = $details_insc['nom'];
	$smarty->assign('id_inscription', $record_id);
	$smarty->assign('nom', $nom);
}
else
{
	//redir
}
$smarty->assign('Revenir', $this->CreateLink($id, 'defaultadmin', $returnid, $contents='<= Revenir'));
$smarty->assign('add_edit', 
		$this->CreateLink($id, 'add_edit_option', $returnid, 'Ajouter une option', array("id_inscription"=>$record_id)));	

$dbresult= array();
//SELECT * FROM ping_module_ping_recup_parties AS rec right JOIN ping_module_ping_joueurs AS j ON j.licence = rec.licence  ORDER BY j.id ASC
$query= "SELECT id,id_inscription,nom, description, date_debut, date_fin, actif, tarif FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ?";//" ORDER BY date_debut DESC";

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
	$onerow->tarif = $row['tarif'];
	$onerow->inscrits = $insc_ops->count_users_in_option($row['id']);
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

echo $this->ProcessTemplate('options.tpl');


#
# EOF
#
?>