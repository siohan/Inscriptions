<?php
if (!isset($gCms)) exit;
if (!$this->CheckPermission('Inscriptions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

if(isset($params['id_inscription']) && $params['id_inscription'] !="")
{
	$id_inscription = $params['id_inscription'];
}
else
{
//redir
	$this->Redirect();
}
$db = cmsms()->GetDb();
$i= 0;
$rowarray = array();
$query = "SELECT insc.id, insc.nom, insc.date_debut, insc.date_fin, insc.heure_debut, insc.heure_fin , opt.id AS id_option, opt.nom AS division FROM ".cms_db_prefix()."module_inscriptions_inscriptions AS insc, ".cms_db_prefix()."module_inscriptions_options AS opt WHERE insc.id = opt.id_inscription AND id_inscription = ?";
$dbresult = $db->Execute($query, array($id_inscription));
if($dbresult && $dbresult->RecordCount()>0)
{
	while($row = $dbresult->FetchRow())
	{
		$id = $row['id'];
		$nom = $row['nom'];
		$division = $row['division'];
		echo $nom;
		echo '<br />'.$division;
		$rowclass = 'row1';
		$id_option = $row['id_option'];
		$query2 = "SELECT licence FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND id_inscription = ?";
		$dbresult2 = $db->Execute($query2, array($id_option, $id_inscription));
		if($dbresult2)
		{
			if($dbresult2->RecordCount()>0)
			{
				while($row2 = $dbresult2->FetchRow())
				{
					$onerow= new StdClass();
					$onerow->rowclass= $rowclass;
					$onerow->licence = $row2['licence'];
					($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
					$rowarray[]= $onerow;
				}
				$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
				$smarty->assign('itemcount', count($rowarray));
				$smarty->assign('items', $rowarray);

				echo $this->ProcessTemplate('print.tpl');
			}
			else
			{
				echo "pas de résultats";
			}
		}
		else
		{
			echo "la req ne fonctionne pas";
		}
	}
}