<?php
if (!isset($gCms)) exit;
//require_once(dirname(__FILE__).'/include/prefs.php');
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$message = '';
$annee = date('Y');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		$id_option = '';
		if (isset($params['id_option']) && $params['id_option'] != '')
		{
			$id_option = $params['id_option'];
		}
		else
		{
			$error++;
		}
		$id_inscription = '';
		if (isset($params['id_inscription']) && $params['id_inscription'] != '')
		{
			$id_inscription = $params['id_inscription'];
		}
		else
		{
			$error++;
		}
	
		if($error ==0)
		{
			//on vire toutes les données de cette compet avant 
		
				$licence = '';
				if (isset($params['licence']) && $params['licence'] != '')
				{
					$licence = $params['licence'];
					//var_dump($licence);
					$error++;
				}
				$insc_ops = new T2t_inscriptions;
				$delete = $insc_ops->delete_users_in_option($id_option);
			
				foreach($licence as $key=>$value)
				{
				//	$ref_action = $this->random_string(15);
					$query2 = "INSERT INTO ".cms_db_prefix()."module_inscriptions_belongs (id_inscription,id_option,licence) VALUES ( ?, ?, ?)";
					//echo $query2;
					$dbresultat = $db->Execute($query2, array($id_inscription,$id_option,$key));
				/*	//la requete a fonctionné ? On ajoute à la table Paiements
					if($dbresultat)
					{
						//on ajoute 
						$message.="Adhérent(s) ajouté(s) à l'options.";
						$tableau = $cotisation_ops->types_cotisations($record_id);
						//var_dump($nom);
						if(is_array($tableau))
						{
							$nom = $tableau['nom'];
							$tarif = $tableau['tarif'];
							$module = 'Cotisations';
							$add = $paiements_ops->add_paiement($key,$ref_action,$module,$nom,$tarif);
							if(true === $add)
							{
								$message.=" Paiement en attente de règlement.";
							}
							//var_dump($add);
							
						}
						
					}
					*/
				}
			$this->SetMessage($message);
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		


$this->RedirectToAdminTab('default');

?>