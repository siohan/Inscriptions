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
	$groupe = $details['groupe'];
	$actif = $details['actif'];
	$choix_multi = $details['choix_multi'];
	$group_notif = $details['group_notif'];
	$visible = 1;
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
	$actif = $details['actif'];
	
	$prenom = $details['prenom'];
	$smarty->assign('prenom', $prenom);
}
else
{
	$error++;
}


if($error < 1)
{
	if(isset($params['recap']) && $visible == 1)
	{
		
		
		//On vérifie que le genid a les droits de voir 
		$gp_ops = new groups;
		$member = $gp_ops->is_member($genid, $group_notif);
		if(true == $member)
		{
			//on affiche la liste des inscriptions
			$query= "SELECT genid,id_option, id_inscription, timbre FROM  ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";

			$query.=" ORDER BY timbre DESC ";
			$dbresult= $db->Execute($query, array($id_inscription));
			$rowclass= 'row1';
			$rowarray= array();
			if ($dbresult && $dbresult->RecordCount() > 0)
			  {

				$assoadh = new Asso_adherents;
			    	while ($row= $dbresult->FetchRow())
			      	{

					$id_option = (int) $row['id_option'];
					$prix = $insc_ops->details_option($id_option);
					//var_dump($id_option);
					if($id_option == 0)
					{
						$price = 0;
					}
					else
					{
						$price = $prix['tarif'];
					}

					$onerow= new StdClass();
					$onerow->rowclass= $rowclass;
					$genid = $row['genid'];
					$onerow->adherent = $assoadh->get_name($genid);
					$onerow->tarif = $price;
					$onerow->timbre = date('d-m-Y H:i:s', $row['timbre']);
					$has_expressed = $insc_ops->has_expressed($id_inscription,$genid);

					if(FALSE === $has_expressed)
					{
						$onerow->reponse= $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
					}
					else
					{			
						$user_choice = $insc_ops->user_choice($id_inscription, $id_option,$genid);
						$onerow->reponse= $user_choice;//	
					}

					$rowarray[]= $onerow;
			        }
			  }

			$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
			$smarty->assign('itemcount', count($rowarray));
			$smarty->assign('items', $rowarray);

			echo $this->ProcessTemplate('recap.tpl');
		}
		else
		{
			echo "Désolé, vous n'avez pas le droit de visionner les réponses à cette inscriptions";
		}
		
		
	}
	else
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
}
?>
