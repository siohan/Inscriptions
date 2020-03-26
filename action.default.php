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
	if($actif == 0 || $date3 - $date_limite >0)
	{
		echo 'Les inscriptions sont fermées ou la date limite de réponse est dépassée !';
		$error++;
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
			echo "Désolé, vous n'avez pas le droit de visionner les réponses à cette inscription";
		}
		
		
	}
	else
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id,nom, description, date_debut, tarif, jauge FROM ".cms_db_prefix()."module_inscriptions_options AS opt WHERE id_inscription = ? AND actif = 1 ";
		$dbresult = $db->Execute($query, array($id_inscription));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			//on construit le formulaire de réponse
			$tpl = $smarty->CreateTemplate($this->GetTemplateResource('feu_edit_inscription.tpl'), null, null, $smarty);
			$tpl->assign('id_inscription', $id_inscription);
			$tpl->assign('genid', $genid);
		
				$it = array();
				$i = 0;
				while($row = $dbresult->FetchRow())
				{
					//on vérifie si l'option a déjà été choisie par le membre
					$checked = $insc_ops->is_inscrit_opt($row['id'], $genid);
					if(true == $checked)
					{
						$check = true;
						$tpl->assign('check', true);
					}
					//on regarde si la jauge est ok
					$jauge = $row['jauge'];
					
					if($jauge >0)//la jauge est activée ! le nb de places est limité
					{
						//on vérifie qu'il reste de la place !
						$quota = $insc_ops->count_users_in_option($row['id']);
						$places_restantes = $jauge-$quota;
						
							$i++;
							if($places_restantes >0)
							{
								$tpl->assign('available_'.$i, true);
							}
							else
							{
								$tpl->assign('available_'.$i, false);
							}
							$checked = $insc_ops->is_inscrit_opt($row['id'], $genid);
							if(true == $checked)
							{
								$tpl->assign('check_'.$i, true);
							}
							${'nom_'.$i} = $row['nom'];
							$nom[] = $row['nom'];
							${'value_'.$i} = $row['id'];
							$id_opt[] = $row['id'];
							$it[$row['nom']] = $row['id'];
							$smarty->assign('nom_'.$i, $row['nom']);
							$tpl->assign('nom_'.$i, $row['nom']);
							$tpl->assign('places_restantes_'.$i, $places_restantes);
									
					}
					else
					{
						$i++;
						$checked = $insc_ops->is_inscrit_opt($row['id'], $genid);
						if(true == $checked)
						{
							$tpl->assign('check_'.$i, true);
						}
						$tpl->assign('available_'.$i, true);
						${'nom_'.$i} = $row['nom'];
						$nom[] = $row['nom'];
						${'value_'.$i} = $row['id'];
						$id_opt[] = $row['id'];
						$it[$row['nom']] = $row['id'];
						$smarty->assign('nom_'.$i, $row['nom']);
						$tpl->assign('nom_'.$i, $row['nom']);
					}						
				}
				$tpl->assign('compteur', $i);
				$tpl->assign('choix_multi', $choix_multi);
				$tpl->assign('choix_multi2', $choix_multi2);
				
				
				
				for($a=1; $a<=$i;$a++)
				{
					
					$tpl->assign('name_'.$a, ${'value_'.$a});
					//$tpl->assign('nom_'.$a, ${'nom'.$a});//$it);
				}
				$tpl->display();
				
		}
	}
}
?>
