<?php
if (!isset($gCms)) exit;
debug_display($params, 'Parameters');

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
	
		
		
		if (isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			$insc_ops = new T2t_inscriptions;
			
			$delete = $insc_ops->delete_options_categ($record_id);
			//On supprime tt d'abord
			
			if(true === $delete)
			{
				$VM = 0;
				if (isset($params['VM']) && $params['VM'] !='')
				{
					$VM = 'VM';//$params['VM'];
					$insc_ops->add_options_categ($record_id,$VM);
				}
				$VF = 0;
				if (isset($params['VF']) && $params['VF'] !='')
				{
					$VF = 'VF';
					$insc_ops->add_options_categ($record_id,$VF);
				}
				$SM = 0;
				if (isset($params['SM']) && $params['SM'] !='')
				{
					$SM = 'SM';
					$insc_ops->add_options_categ($record_id,$SM);
				}
				$SF = 0;
				if (isset($params['SF']) && $params['SF'] !='')
				{
					$SF = 'SF';
					$insc_ops->add_options_categ($record_id,$SF);
				}
				$JM = 0;
				if (isset($params['JM']) && $params['JM'] !='')
				{
					$JM = 'JM';
					$insc_ops->add_options_categ($record_id,$JM);
				}
				$JF = 0;
				if (isset($params['JF']) && $params['JF'] !='')
				{
					$JF = 'JF';
					$insc_ops->add_options_categ($record_id,$JF);
				}
				$CM = 0;
				if (isset($params['CM']) && $params['CM'] !='')
				{
					$CM = 'CM';
					$insc_ops->add_options_categ($record_id,$CM);
				}
				$CF = 0;
				if (isset($params['CF']) && $params['CF'] !='')
				{
					$CF = 'CF';
					$insc_ops->add_options_categ($record_id,$CF);
				}
				$MM = 0;
				if (isset($params['MM']) && $params['MM'] !='')
				{
					$MM = 'MM';
					$insc_ops->add_options_categ($record_id,$MM);
				}
				$MF = 0;
				if (isset($params['MF']) && $params['MF'] !='')
				{
					$MF = 'MF';
					$insc_ops->add_options_categ($record_id,$MF);
				}
				$BM = 0;
				if (isset($params['BM']) && $params['BM'] !='')
				{
					$BM = 'BM';
					$insc_ops->add_options_categ($record_id,$BM);
				}
				$BF = 0;
				if (isset($params['BF']) && $params['BF'] !='')
				{
					$BF = 'BF';
					$insc_ops->add_options_categ($record_id,$BF);
				}
				$PM = 0;
				if (isset($params['PM']) && $params['PM'] !='')
				{
					$PM = 'PM';
					$insc_ops->add_options_categ($record_id,$PM);
				}
				$PF = 0;
				if (isset($params['PF']) && $params['PF'] !='')
				{
					$PF = 'PF';
					$insc_ops->add_options_categ($record_id,$PF);
				}
			}
			else
			{
				$this->SetMessage('Suppression impossible des catégories de cette inscription');
			}
			
						
		}
		else
		{
			$this->SetMessage('Paramètres manquants');
		}		
		
		
		
	/*
		
		
		
		
				
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('types_cotis');
		}
		else // pas d'erreurs on continue
		{
			
			
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_cotisations_types_cotisations (nom, description, tarif,actif) VALUES ( ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($nom, $description,$tarif, $actif));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_cotisations_types_cotisations SET nom = ?, description = ?, tarif = ?, actif = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($nom, $description, $tarif,$actif,$record_id));
				
				
			}
			
			
			
		}		
	//	echo "la valeur de edit est :".$edit;
		
		
	
*/			
		


$this->RedirectToAdminTab('cotis');//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));

?>