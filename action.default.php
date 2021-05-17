<?php
if(!isset($gCms)) exit;
$db = cmsms()->GetDb();
//debug_display($params, 'Parameters');
$error = 0; //on instancie un compteur d'erreur
$error_message = '';
$insc_ops = new T2t_inscriptions;

if(isset($params['id_inscription']) && $params['id_inscription'] >0)
{
	//on regarde si le genid est spécifié alors on récupère le nom et le prénom
	if(isset($params['genid']) && $params['genid'] >0)
	{
		$genid = $params['genid'];
		$adh_ops = new Asso_adherents;
		$details = $adh_ops->details_adherent_by_genid($genid);
		$smarty->assign('user_name', $details['nom']);
		$smarty->assign('user_forename', $details['prenom']);
	}
	
	$id_inscription = $params['id_inscription'];
	//on va chercher les détails de cette inscription
	$details = $insc_ops->details_inscriptions($id_inscription);
	$smarty->assign('titre', $details['nom']);
	$choix_multi = $details['choix_multi'];
	//deux cas : 1 il y a un genid, 2 pas de genid
	$query = "SELECT id,nom, description, date_debut, tarif, jauge FROM ".cms_db_prefix()."module_inscriptions_options AS opt WHERE id_inscription = ? AND actif = 1 ORDER BY date_debut ASC";
		$dbresult = $db->Execute($query, array($id_inscription));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			//on construit le formulaire de réponse
			$tpl = $smarty->CreateTemplate($this->GetTemplateResource('feu_edit_inscription.tpl'), null, null, $smarty);
			$tpl->assign('id_inscription', $id_inscription);
			
		
				$it = array();
				$i = 0;
				while($row = $dbresult->FetchRow())
				{
					//on vérifie si l'option a déjà été choisie par le membre
					
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
				//$tpl->assign('choix_multi2', $choix_multi2);
				
				
				
				for($a=1; $a<=$i;$a++)
				{
					
					$tpl->assign('name_'.$a, ${'value_'.$a});
					//$tpl->assign('nom_'.$a, ${'nom'.$a});//$it);
				}
				$tpl->display();
		}
}
else
{
	if(isset($params['genid']) && $params['genid'] >0)
	{
		$genid = $params['genid'];
		$smarty->assign('genid', $params['genid']);
	}
	
	$query= "SELECT id, nom, description FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE actif = 1 AND date_limite > UNIX_TIMESTAMP()";// FROM_UNIXTIME( date_debut, date('%m')) = ? 
	$query.=" ORDER BY date_debut DESC";
	$dbresult= $db->Execute($query);
	$rowclass= 'row1';
	$rowarray= array();
	$cont_ops = new contact;
	
	if($dbresult && $dbresult->RecordCount() > 0)
	{
		$insc_ops = new T2t_inscriptions;
		while ($row= $dbresult->FetchRow())
		{
			$onerow= new StdClass();
			$onerow->rowclass= $rowclass;
			$onerow->actif= $row['actif'];
			$onerow->nom= $row['nom'];
			$onerow->id = $row['id'];
			$onerow->description= $row['description'];
		
			($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
			$rowarray[]= $onerow;
		}
	  
		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
	}
echo $this->ProcessTemplate('feu_inscriptions.tpl');
}
?>
