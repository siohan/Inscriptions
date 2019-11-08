<?php

if( !isset($gCms) ) exit;

$db =& $this->GetDb();
global $themeObject;
/*
$smarty->assign('add_edit', 
		$this->CreateLink($id, 'add_edit_inscription', $returnid,$themeObject->DisplayImage('icons/system/newobject.gif', 'Ajouter une inscription', '', '', 'systemicon'), $this->Lang('add')));
*/
$refresh = '<img src="../modules/Inscriptions/images/refresh.png" alt="remise à zéro des inscrits" title="Remise à zéro des inscrits" />';
		
$dbresult= array();
$query= "SELECT id,nom, description,date_limite, date_debut, date_fin, actif, groupe , choix_multi FROM ".cms_db_prefix()."module_inscriptions_inscriptions ORDER BY actif DESC, date_debut DESC";

$dbresult= $db->Execute($query);
$rowclass= 'row1';
$rowarray= array();
$cont_ops = new contact;
if ($dbresult)
{
	if($dbresult->RecordCount() > 0)
	{
		
	
	  
		$insc_ops = new T2t_inscriptions;
	    while ($row= $dbresult->FetchRow())
	      {
		//$id_envoi = (int) $row['id_envoi'];
		$onerow= new StdClass();
		$onerow->rowclass= $rowclass;
		$actif = $row['actif'];
		$choix_multi = $row['choix_multi'];
		$date_limite = $row['date_limite'];
		$details = $cont_ops->details_group($row['groupe']);
		$nom_gp = $details['nom'];
		$nb_total = $cont_ops->CountUsersFromGroup($row['groupe']);
	
		$nb_inscrits = $insc_ops->count_users_in_inscription($row['id']);
		$nb_options = $insc_ops->count_options_per_inscription($row['id']);
		
		if($choix_multi == 1)
		{
			$nb_total = $nb_options*$nb_total;
		}
	
		if($actif == 0)
		{
			$onerow->actif= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'), array("obj"=>"activate_desactivate", "record_id"=>$row['id'], "act"=>"1"));
		}
		elseif($date_limite < time() || $nb_options <1 || $nb_inscrits == $nb_total )
		{
			$onerow->actif= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon'), array("obj"=>"activate_desactivate", "record_id"=>$row['id'], "act"=>"0"));
		}
		else
		{
			$onerow->actif= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon'), array("obj"=>"activate_desactivate", "record_id"=>$row['id'], "act"=>"0"));
			$onerow->emailing = $this->Createlink($id, 'emailing', $returnid, $themeObject->DisplayImage('icons/topfiles/cmsmailer.gif', 'Prévenir par email', '', '', 'systemicon'), array("id_inscription"=>$row['id']));
		}

		if($choix_multi == 0)
		{
			$onerow->choix_multi= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'), array("obj"=>"choix_multi", "record_id"=>$row['id'], "act"=>"1"));
		}
		else
		{
			$onerow->choix_multi= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon'), array("obj"=>"choix_multi", "record_id"=>$row['id'], "act"=>"0"));
		}

		$onerow->nb_options = $insc_ops->count_options_per_inscription($row['id']);
		$onerow->options = $this->CreateLink($id, 'admin_options', $returnid, 'Options',array('record_id'=>$row['id']));
		$onerow->id= $row['id'];
		$onerow->nom= $row['nom'];
		$onerow->description= $row['description'];
		$onerow->date_limite= $row['date_limite'];
		$onerow->date_debut= $row['date_debut'];
		$onerow->date_fin= $row['date_fin'];
		$onerow->nb_total = $nb_total;
		$onerow->nb_inscrits = $nb_inscrits;
		$onerow->groupe = $nom_gp;
		$onerow->inscrits = $this->CreateLink($id, 'admin_reponses', $returnid, $insc_ops->count_users_in_inscription($row['id']), array("id_inscription"=>$row['id']));
		$onerow->refresh = $this->CreateLink($id, 'inscription', $returnid, 'RAZ', array('obj'=>'refresh','id_inscription'=>$row['id']));
		$onerow->view= $this->CreateLink($id, 'admin_reponses', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'),array('id_inscription'=>$row['id']));
		$onerow->editlink= $this->CreateLink($id, 'add_edit_inscription', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array('record_id'=>$row['id']));
		$onerow->delete= $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array('obj'=>'delete_inscription','record_id'=>$row['id']),$warn_message='Attention, ceci supprimera les options et les adhésions des utilisateurs');
		($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
		$rowarray[]= $onerow;
	      }
	}
}
else
{
	echo "Pas de résultats...";
}
  

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);

echo $this->ProcessTemplate('inscriptions.tpl');


#
# EOF
#
?>