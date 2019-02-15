<?php

if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
$insc_ops = new T2t_inscriptions;

if(isset($params['id_inscription']) && $params['id_inscription'] !='')
{
	$id_inscription = $params['id_inscription'];
	//details de la présence	
	
	$details_pres = $insc_ops->details_inscriptions($id_inscription);
	$groupe = $details_pres['groupe'];
	$choix_multi = $details_pres['choix_multi'];
	$titre = $details_pres['nom'];
	$smarty->assign('titre', $titre);
}
else
{
	//redir
}

$smarty->assign('revenir', $this->CreateLink($id, 'defaultadmin', $returnid, $contents='<= Revenir'));	
$dbresult= array();
$query= "SELECT beadh.genid,be_insc.id, be_insc.id_option, be_insc.id_inscription FROM ".cms_db_prefix()."module_adherents_groupes_belongs AS beadh LEFT JOIN ".cms_db_prefix()."module_inscriptions_belongs AS be_insc ON be_insc.genid = beadh.genid";//"  WHERE beadh.id_group = ?";
if(isset($params['record_id']) && $params['record_id'] !='')
{
	$id_option = $params['record_id'];
	$query.=" WHERE be_insc.id_option = ? AND beadh.id_group = ?";
	$parms['id_option'] = $id_option;
	$parms['id_group'] = $groupe;
}
else
{
	$query.=" WHERE beadh.id_group = ?";
	$parms['id_group'] = $groupe;
}
$dbresult= $db->Execute($query, $parms);
$rowclass= 'row1';
$rowarray= array();
if ($dbresult && $dbresult->RecordCount() > 0)
  {
	
	$assoadh = new adherents_spid;
    	while ($row= $dbresult->FetchRow())
      	{
	
		$id_option = (int) $row['id_option'];
		$onerow= new StdClass();
		$onerow->rowclass= $rowclass;
		$genid = $row['genid'];
		$onerow->adherent = $assoadh->get_name($genid);
		$has_expressed = $insc_ops->has_expressed($id_inscription,$genid);

		if(FALSE === $has_expressed)
		{
			$onerow->reponse= $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
			$onerow->delete = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
		}
		else
		{			
			$user_choice = $insc_ops->user_choice($id_inscription, $id_option,$genid);
			$onerow->reponse= $user_choice;//
			$onerow->delete = $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array('obj'=>'delete_reponse', 'id_inscription'=>$row['id_inscription'], 'genid'=>$row['genid'], 'id_option'=>$row['id_option'] ));				
		}
		
		$rowarray[]= $onerow;
      }
  }

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);

echo $this->ProcessTemplate('reponses.tpl');


#
# EOF
#
?>