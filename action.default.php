<?php
if(!isset($gCms)) exit;
$db = cmsms()->GetDb();
//debug_display($params, 'Parameters');
$error = 0; //on instancie un compteur d'erreur
if(isset($params['id_inscription']) && $params['id_inscription'] !='')
{
	$id_inscription = $params['id_inscription'];
	$insc_ops = new T2t_inscriptions;
	$details = $insc_ops->details_inscriptions($id_inscription);	
	$date_limite = $details['date_limite'];
	$description = $details['description'];
	$actif = $details['actif'];
	$choix_multi = $details['choix_multi'];
	$date2 = $insc_ops->datetotimestamp($date_limite);
	$date3 = time();
	$titre = $details['nom'];
	$smarty->assign('titre', $titre);
	$smarty->assign('description', $description);
	if($actif == 0 || $date3 - $date2 >0)
	{
		echo 'Les inscriptions sont fermées ou la date limite de réponse est dépassée !';
	//	$this->Redirect();exit;
	}
}
else
{
	$error++;
}
if(isset($params['genid']) && $params['genid'] !='')
{
	$genid = $params['genid'];
	//on vérifie si le genid correspond bien à qqun ds la base	
	$asso_adh = new Asso_adherents;
//	$adh_exists
	$details = $asso_adh->details_adherent_by_genid($genid);
	$prenom = $details['prenom'];
	$smarty->assign('prenom', $prenom);
}
else
{
	$error++;
}


if($error < 1)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,nom, description, date_debut, tarif FROM ".cms_db_prefix()."module_inscriptions_options AS opt WHERE id_inscription = ? AND actif = 1 ";
	$dbresult = $db->Execute($query, array($id_inscription));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		//on construit le formulaire de réponse
		$smarty->assign('formstart',
			$this->CreateFormStart($id,  'feu_do_edit_inscription', $returnid));
			$smarty->assign('id_inscription', 
			$this->CreateInputHidden($id, 'id_inscription', $id_inscription));
			$smarty->assign('genid', 
				$this->CreateInputHidden($id, 'genid', $genid));//attention choix multi ou pas ?
			$it = array();
			$i = 0;
			while($row = $dbresult->FetchRow())
			{
		
				$i++;
				${'nom_'.$i} = $row['nom'];
				$nom[] = $row['nom'];
				${'value_'.$i} = $row['id'];
				$id_opt[] = $row['id'];
				$it[$row['nom']] = $row['id'];
				$smarty->assign('nom_'.$i, $row['nom']);			
			}
			
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
					$smarty->assign('name_'.$a, $this->CreateInputCheckbox($id, 'nom[]', ${'value_'.$a}));
				}
			}

			$smarty->assign('submit',
				$this->CreateInputSubmit($id, 'submit', 'Envoyer', 'class="button"'));
			$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
						'Annuler'));


			$smarty->assign('formend',
						$this->CreateFormEnd());
			echo $this->ProcessTemplate('feu_edit_inscription.tpl');
	}		
}
?>
