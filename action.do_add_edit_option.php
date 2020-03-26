<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Inscriptions use'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('insc');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$insc_ops = new T2t_inscriptions;	
		
		
		if (isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			$edit = 1;
						
		}
		if (isset($params['id_inscription']) && $params['id_inscription'] !='')
		{
			$id_inscription = $params['id_inscription'];		
		}
				
		if (isset($params['nom']) && $params['nom'] !='')
		{
			$nom = $params['nom'];
		}
		else
		{
			$error++;
		}
		if (isset($params['description']) && $params['description'] !='')
		{
			$description = $params['description'];
		}
		if (isset($params['date_debut']) && $params['date_debut'] !='')
		{
			$date_debut = $params['date_debut'];
		}
		if (isset($params['date_fin']) && $params['date_fin'] !='')
		{
			$date_fin = $params['date_fin'];
		}
		if (isset($params['heure_debut']) && $params['heure_debut'] !='')
		{
			$heure_debut = $params['heure_debut'];
		}
		if (isset($params['heure_fin']) && $params['heure_fin'] !='')
		{
			$heure_fin = $params['heure_fin'];
		}
		if (isset($params['actif']) && $params['actif'] !='')
		{
			$actif = $params['actif'];
		}
		if (isset($params['groupe']) && $params['groupe'] !='')
		{
			$groupe = $params['groupe'];
		}
		$tarif = 0;
		if (isset($params['tarif']) && $params['tarif'] !='')
		{
			$tarif = $params['tarif'];
		}
		


		if($error < 1)
		{
			if($edit == 0)
			{
				$add = $insc_ops->add_option($id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $tarif);
				if(true === $add)
				{
					$this->SetMessage('Option ajoutée');
				}
				else
				{
					$this->SetMessage('Option non ajoutée !!');
				}
			}
			else
			{
				$edit = $insc_ops->edit_option($record_id,$id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut);
				if(true === $edit)
				{
					$this->SetMessage('Option modifiée');
				}
				else
				{
					$this->SetMessage('Option non modifiée !!');
				}
			}
		}
		else
		{
			$this->SetMessage('Il manque le nom !');
		}
			
			
$this->RedirectToAdminTab('inscriptions');//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));

?>