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
//debug_display($params, 'Parameters');
$error = 0;
if(isset($params['id_inscription']) && $params['id_inscription'] !='')
{
	$id_inscription = $params['id_inscription'];
	//details de la présence	
	/*
	$details_pres = $insc_ops->details_inscriptions($id_inscription);
	$groupe = $details_pres['groupe'];
	$choix_multi = $details_pres['choix_multi'];
	$titre = $details_pres['nom'];
	$smarty->assign('titre', $titre);
	
	*/
	$smarty->assign('id_inscription', $id_inscription);
}
else
{
	$error++;
}
if(isset($params['id_option']) && $params['id_option'] !='')
{
	$id_option = $params['id_option'];
	$details_option = $insc_ops->details_option($id_option);
	$titre = $details_option['nom'];
	$smarty->assign('titre', $titre);
}
else
{
	$error++;
}
$smarty->assign('revenir', $this->CreateLink($id, 'defaultadmin', $returnid, $contents='<= Revenir'));	
	
$dbresult= array();
$query= "SELECT id_option, genid FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND id_option = ? ";
$dbresult= $db->Execute($query, array( $id_inscription, $id_option));
$rowclass= 'row1';
$rowarray= array();
if ($dbresult && $dbresult->RecordCount() > 0)
  {
	
	$assoadh = new Asso_adherents;
    	while ($row= $dbresult->FetchRow())
      	{
	
		
		$has_expressed = $insc_ops->user_choice($id_inscription, $row['id_option'], $row['genid']);
		$onerow= new StdClass();
		$onerow->rowclass= $rowclass;
		$genid = $row['genid'];
		$onerow->adherent = $assoadh->get_name($row['genid']);		

		if(!FALSE === $has_expressed)
		{
			$onerow->reponse= $has_expressed;
			
		}
		
		
		$rowarray[]= $onerow;
      }
  }

$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
$smarty->assign('itemcount', count($rowarray));
$smarty->assign('items', $rowarray);

echo $this->ProcessTemplate('reponses_by_id_options.tpl');


#
# EOF
#
?>