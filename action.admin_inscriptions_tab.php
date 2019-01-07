<?php

if( !isset($gCms) ) exit;

$db =& $this->GetDb();
global $themeObject;

$smarty->assign('add_edit', 
		$this->CreateLink($id, 'add_edit_inscription', $returnid,$themeObject->DisplayImage('icons/system/add.gif', 'Ajouter une inscription', '', '', 'systemicon')).
$this->CreateLink($id, 'envoi_emails', $returnid, 
		  $this->Lang('add')));

		
$dbresult= array ();
//SELECT * FROM ping_module_ping_recup_parties AS rec right JOIN ping_module_ping_joueurs AS j ON j.licence = rec.licence  ORDER BY j.id ASC
$query= "SELECT id,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif, statut FROM ".cms_db_prefix()."module_inscriptions_inscriptions ORDER BY date_debut DESC";

$dbresult= $db->Execute($query);
$rowclass= 'row1';
$rowarray= array ();
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
		$onerow->actif= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'), array("obj"=>"activate_desactivate", "record_id"=>$row['id'], "act"=>"1"));
	}
	else
	{
		$onerow->actif= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon'), array("obj"=>"activate_desactivate", "record_id"=>$row['id'], "act"=>"0"));
		$onerow->emailing = $this->Createlink($id, 'emailing', $returnid, $themeObject->DisplayImage('icons/topfiles/cmsmailer.gif', 'Prévenir par email', '', '', 'systemicon'), array("id_inscription"=>$row['id']));
	}
	
	$onerow->nb_options = $insc_ops->count_options_per_inscription($row['id']);//$this->CreateLink($id, 'sent_back', $returnid, $themeObject->DisplayImage('icons/topfiles/cmsmailer.gif', 'Renvoyer le mail', '', '', 'systemicon'),array("record_id"=>$row['id']));
	$onerow->id= $row['id'];
	$onerow->nom= $row['nom'];
	$onerow->description= $row['description'];
	$onerow->date_debut= $row['date_debut'];
	$onerow->date_fin= $row['date_fin'];
	$onerow->heure_debut= $row['heure_debut'];
	$onerow->heure_fin= $row['heure_fin'];
	$onerow->statut = $row['statut'];
	$onerow->inscrits = $insc_ops->count_users_in_inscription($row['id']);
	$onerow->view= $this->CreateLink($id, 'admin_options', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'),array('record_id'=>$row['id']));
	$onerow->print = $this->CreateLink($id, 'admin_print', $returnid,$themeObject->DisplayImage('icons/system/document-list.png', $this->Lang('print'), '', '', 'systemicon'), array("id_inscription"=>$row['id']));
//	$onerow->renvoyer = $this->CreateLink($id, 'sent_back', $returnid, $themeObject->DisplayImage('icons/system/new.gif', $this->Lang('new'), '', '', 'systemicon'),array("message_id"=>$row['id']));	
	$onerow->editlink= $this->CreateLink($id, 'add_edit_inscription', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array('record_id'=>$row['id']));
	($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
	$rowarray[]= $onerow;
      }
  }

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);

echo $this->ProcessTemplate('inscriptions.tpl');


#
# EOF
#
?>