<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$id_option = '';
$id_inscription  = '';
$rowarray = array();
$insc_ops = new T2t_inscriptions;
	
if(!isset($params['id_option']) || $params['id_option'] == '')
{
	$this->SetMessage("parametres manquants");
//	$this->RedirectToAdminTab('groups');
}
else
{
	$id_option = $params['id_option'];
}
if(!isset($params['id_inscription']) || $params['id_inscription'] == '')
{
	$this->SetMessage("parametres manquants");
//	$this->RedirectToAdminTab('groups');
}
else
{
	$id_inscription = $params['id_inscription'];
	$details = $insc_ops->details_inscriptions($id_inscription);
}
	
$db = $this->GetDb();

$gp_ops = new groups;
$tab1 = $gp_ops->liste_licences_from_group($details['groupe']);

//var_dump($tab1);

if(false !== $tab1 && false == is_null($tab1))
{
	$tab2 = implode(', ', $tab1);
	$query = "SELECT j.genid, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents AS j WHERE j.actif = 1  AND j.genid IN ($tab2) ORDER BY j.nom ASC, j.prenom ASC ";
}
else
{
	$query = "SELECT j.genid, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents AS j WHERE j.actif = 1 ";
	$query.=" ORDER BY j.nom ASC, j.prenom ASC ";
}

$dbresult = $db->Execute($query);

	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('groups');
	}

	$smarty->assign('formstart',
			$this->CreateFormStart( $id, 'do_assign_users', $returnid ) );
	$smarty->assign('id_option',
			$this->CreateInputHidden($id,'id_option',$id_option));
	$smarty->assign('id_inscription',
			$this->CreateInputHidden($id,'id_inscription',$id_inscription));	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
		
			$genid = $row['genid'];
			$joueur = $row['joueur'];
			$rowarray[$genid]['name'] = $joueur;
			$rowarray[$genid]['participe'] = false;
			
			//on va chercher si le joueur est déjà dans la table participe
			$query2 = "SELECT genid, id_option FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE genid = ? AND id_option = ?";
			$dbresultat = $db->Execute($query2, array($genid, $id_option));
			
			if($dbresultat && $dbresultat->RecordCount()>0)
			{
				while($row2 = $dbresultat->FetchRow())
				{
					// l'adhérent est déjà en bdd
					$rowarray[$genid]['participe'] = true;
				}
			}
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