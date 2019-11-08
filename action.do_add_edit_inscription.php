<?php
if (!isset($gCms)) exit;
debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Inscriptions use'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('insc');
	}
if(isset($params['cancel']))
{
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
		if (!isset($params['date_fin']) || $params['date_fin'] !='' || $params['date_fin'] < $params['date_debut'])
		{
			$date_fin = $date_debut;
		}
		else
		{
			$date_fin = $params['date_fin'];
		}
		if (isset($params['date_limite']) && $params['date_limite'] !='' && $params['date_limite'] < $date_fin)
		{
			$date_limite = $params['date_limite'];
		}
		else
		{
			$date_limite = $date_debut;
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
		
		if (isset($params['choix_multi']) && $params['choix_multi'] !='')
		{
			$choix_multi = $params['choix_multi'];
		}

		if($error < 1)
		{
			if($edit == 0)
			{
				$add = $insc_ops->add_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut, $groupe, $choix_multi);
				if(true === $add)
				{
					$this->SetMessage('Inscription ajoutée');
				}
				else
				{
					$this->SetMessage('Inscription non ajoutée !!');
				}
			}
			else
			{
				$edit = $insc_ops->edit_inscription($record_id,$nom, $description, $date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut,$groupe, $choix_multi);
				if(true === $edit)
				{
					$this->SetMessage('Inscription modifiée');
				}
				else
				{
					$this->SetMessage('Inscription non modifiée !!');
				}
			}
		}
		else
		{
			$this->SetMessage('Il manque le nom !');
		}
			
			
$this->RedirectToAdminTab('inscriptions');//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));

?>