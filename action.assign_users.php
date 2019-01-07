<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Inscription use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$id_option = '';
$id_inscription  = '';
$rowarray = array();

	
if(!isset($params['id_option']) || $params['id_option'] == '')
{
	$this->SetMessage("parametres manquants");
	$this->RedirectToAdminTab('groups');
}
else
{
	$id_option = $params['id_option'];
}
if(!isset($params['id_inscription']) || $params['id_inscription'] == '')
{
	$this->SetMessage("parametres manquants");
	$this->RedirectToAdminTab('groups');
}
else
{
	$id_inscription = $params['id_inscription'];
}
	
$db = $this->GetDb();
/*
//on va sélectionner les licences déjà présentes en bdd pour cette cotisation et l'exclure de la liste
$query = "SELECT licence FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND id_inscription = ?";
$dbresult = $db->Execute($query, array($id_option,$id_inscription ));
$count = $dbresult->RecordCount();
$tableau = array();
if($count >0)
{
	while($row = $dbresult->FetchRow())
	{
		$licence = $row['licence'];
		$tableau[]=$licence;
	}

$tab = implode(', ',$tableau);
//$tab = array_values($tableau);	
//on va aussi exclure certaines licences ne faisant pas parties des catégories de la cotisation

}
*/
$insc_ops = new T2t_inscriptions;
$tab1 = $insc_ops->categ_per_option($id_option);
var_dump($tab1);


//var_dump($tab2);

if(false !== $tab1 && false == is_null($tab1))
{
	$tab2 = implode(', ', $tab1);
	$query = "SELECT j.licence, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents AS j WHERE j.actif = 1  AND j.cat IN ($tab2) ORDER BY j.nom ASC, j.prenom ASC ";
}
else
{
	$query = "SELECT j.licence, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents AS j WHERE j.actif = 1 ";
//	$query.=" AND j.cat IN ($tab2) ";
	$query.=" ORDER BY j.nom ASC, j.prenom ASC ";
}

//echo $query;
$dbresult = $db->Execute($query);//, array($tableau));
//echo $query;
	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('groups');
	}

	$smarty->assign('formstart',
			$this->CreateFormStart( $id, 'do_assign_users', $returnid ) );
	$smarty->assign('id_option',
			$this->CreateInputText($id,'id_option',$id_option,10,15));
	$smarty->assign('id_inscription',
			$this->CreateInputText($id,'id_inscription',$id_inscription,10,15));	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			//var_dump($row);
			
			$licence = $row['licence'];
			$joueur = $row['joueur'];
			$rowarray[$licence]['name'] = $joueur;
			$rowarray[$licence]['participe'] = false;
			
			//on va chercher si le joueur est déjà dans la table participe
			$query2 = "SELECT licence, id_option FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE licence = ? AND id_option = ?";
			//echo $query2;
			$dbresultat = $db->Execute($query2, array($licence, $id_option));
			
			if($dbresultat->RecordCount()>0)
			{
				while($row2 = $dbresultat->FetchRow())
				{
			
					// l'adhérent est déjà en bdd
					$rowarray[$licence]['participe'] = true;
				}
			}
			
			//print_r($rowarray);
			
			
						
			
			
		}
		$smarty->assign('rowarray',$rowarray);	
			
	}
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));
	$smarty->assign('back',
			$this->CreateInputSubmit($id,'back',
						$this->Lang('back')));

	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('assign_users.tpl');
#
#EOF
#
?>