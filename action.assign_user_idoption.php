<?php
if (!isset($gCms)) exit;
//debug_display($params,'Parameters');
global $themeObject;

if(isset($params['id_inscription']) && $params['id_inscription'] !='')
{
	$id_inscription = $params['id_inscription'];
	//choix multi ou pas ?
	$insc_ops = new T2t_inscriptions;
	$detailsInsc = $insc_ops->details_inscriptions($id_inscription);
	$choix_multi = $detailsInsc['choix_multi'];
	$smarty->assign('id_inscription', $id_inscription);
//	var_dump($choix_multi); 
}
$details=0;
if(isset($params['details']) && $params['details'] !='')
{
	$details = $params['details'];
}
if(isset($params['genid']) && $params['genid'] !='')
{
	$genid = $params['genid'];
}

$db = cmsms()->GetDb();
$query = "SELECT id,nom, description, date_debut, tarif FROM ".cms_db_prefix()."module_inscriptions_options AS opt WHERE id_inscription = ? AND actif = 1 ";
$dbresult = $db->Execute($query, array($id_inscription));
if($dbresult && $dbresult->RecordCount()>0)
{
	if($details ==1)//on affiche le détail sans permettre de changer
	{
	
		$insc_ops = new T2t_inscriptions;
		$rowarray= array();
		$rowclass = '';
		while($row = $dbresult->FetchRow())
		{
			$onerow = new StdClass();
			$onerow->nom = $row['nom'];
			$id_option = $row['id'];
			$onerow->description = $row['description'];
			$onerow->date_debut =  $row['date_debut'];
			$inscrit = $insc_ops->is_inscrit_opt($row['id'], $genid);
			if(FALSE ===$inscrit )
			{
				$onerow->is_inscrit = $this->CreateLink($id, 'inscription',$returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'),array('obj'=>'add_reponse', 'id_inscription'=>$id_inscription, 'genid'=>$genid, 'id_option'=>$id_option));
			
			}
			else
			{
				$onerow->is_inscrit = $this->CreateLink($id, 'inscription', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon'), array("obj"=>"delete_reponse", 'id_inscription'=>$id_inscription, 'genid'=>$genid, 'id_option'=>$id_option));
			
			}
			
			($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
			$rowarray[]= $onerow;
		}
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		echo $this->ProcessTemplate('user_options.tpl');
	}
	else //on affiche le formulaire
	{
		
		$i = 0;
		$smarty->assign('formstart',
				$this->CreateFormStart($id,  'fe_do_edit_inscription', $returnid));
		$smarty->assign('id_inscription', 
				$this->CreateInputHidden($id, 'id_inscription', $id_inscription));
		$smarty->assign('genid', 
				$this->CreateInputHidden($id, 'genid', $genid));//attention choix multi ou pas ?
				$it = array();
		while($row = $dbresult->FetchRow())
		{
			
			$i++;
			${'nom_'.$i} = $row['nom'];
			$nom[] = $row['nom'];
			$id_opt[] = $row['id'];
			$it[$row['nom']] = $row['id'];
			$smarty->assign('nom_'.$i, $row['nom']);			
		}
		//$it = array("V1"=>"1", "V2"=>"2", "V3"=>"3");
		//var_dump($nom_1);
		$smarty->assign('compteur',  $i);
		$smarty->assign('choix_multi', $choix_multi);
		$smarty->assign('choix_multi2', $this->createInputHidden($id, 'choix_multi2',$choix_multi));
		
				for($a=1; $a<=$i;$a++)
				{
					//echo $a;
					if($choix_multi == '0')//bouton radio
					{
						$smarty->assign('nom', $this->CreateInputRadioGroup($id, 'nom', $it,$selectedvalue='', '','<br />'));//${'nom_'.$a}, ''));
					}
					else
					{
						$smarty->assign('name_'.$a, $this->CreateInputCheckbox($id, 'nom[]', ${'nom_'.$a}, ''));
					}
				}
	
		$smarty->assign('submit',
				$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
		$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
							$this->Lang('cancel')));


		$smarty->assign('formend',
				$this->CreateFormEnd());
	echo $this->ProcessTemplate('assign_user_idoption.tpl');
	}
}


#
# EOF
#

?>