<?php
if( !isset($gCms) ) exit;


//debug_display($_POST, 'Parameters');
$db =& $this->GetDb();
global $themeObject;
$insc_ops = new T2t_inscriptions;
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		$this->RedirectToAdminTab();
	}
	//on récupère les données en POST
	$error = 0;
	$edit = 0;
	
		if (isset($_POST['id_inscription']) && $_POST['id_inscription'] !='')
		{
			$id_inscription = $_POST['id_inscription'];
			//on va chercher qqs détails de cette inscription comme les dates par ex
			$details = $insc_ops->details_inscriptions($id_inscription);
			$debut_insc = $details['date_debut'];
			

		}
		else
		{
			$error++;
		}
		
		if (isset($_POST['record_id']) && $_POST['record_id'] !='')
		{
			$record_id = $_POST['record_id'];
			$edit = 1;

		}

		if (isset($_POST['nom']) && $_POST['nom'] !='')
		{
			$nom = $_POST['nom'];
		}
		else
		{
			$error++;
		}
		if (isset($_POST['timbre']) && $_POST['timbre'] !='')
		{
			$timbre = $_POST['timbre'];
		}
		else
		{
			$timbre = time();
		}
		if (isset($_POST['tarif']) && $_POST['tarif'] !='')
		{
			$tarif = $_POST['tarif'];
		}
		if (isset($_POST['description']))
		{
			$description = $_POST['description'];
		}
		//$date_limite = mktime($_POST['limite_Hour'], $_POST['limite_Minute'], $_POST['limite_Second'],$_POST['limite_Month'], $_POST['limite_Day'], $_POST['limite_Year']);
		
		$date_debut = mktime($_POST['debut_Hour'], $_POST['debut_Minute'], $_POST['debut_Second'],$_POST['debut_Month'], $_POST['debut_Day'], $_POST['debut_Year']);
		$date_fin = mktime($_POST['fin_Hour'], $_POST['fin_Minute'], $_POST['fin_Second'],$_POST['fin_Month'], $_POST['fin_Day'], $_POST['fin_Year']);
		
		if($date_debut < $debut_insc)
		{
			$date_debut = $debut_insc;
		}
		if($date_fin < $date_debut)
		{
			$date_fin = $date_debut;
		}
		if (isset($_POST['actif']) && $_POST['actif'] !='')
		{
			$actif = $_POST['actif'];
		}
		

		if($error < 1)
		{
			if($edit == 0)
			{
				$add = $insc_ops->add_option($id_inscription,$nom, $description,  $date_debut, $date_fin, $actif, $tarif,$timbre);
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
				$edit = $insc_ops->edit_option($record_id,$id_inscription,$nom, $description, $date_debut, $date_fin, $actif,$tarif);
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


$this->Redirect($id,'admin_options', $returnid, array('record_id'=>$id_inscription));//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));
	
}
else
{
	$error = 0;
	$edit = 0;
	
	//Les données par défaut :
	$record_id = '';	
	$nom = '';
	$description = '';
	$timbre = time();
	$actif = 1;
	$tarif = '0.00';
	
	
	
	if(isset($params['id_inscription']) && $params['id_inscription'] != '')
	{
		$id_inscription = $params['id_inscription'];
		//on va chercher les détails de l'inscription
		//on va chercher qqs détails de cette inscription comme les dates par ex
		$details = $insc_ops->details_inscriptions($id_inscription);
		$date_debut = $details['date_debut'];
		$date_fin = $details['date_fin'];
	}
	else
	{
		$error++;
	}

	if(isset($params['record_id']) && $params['record_id'] != '')
	{
		$record_id = $params['record_id'];
		$edit = 1;	
		$details_options = $insc_ops->details_option($record_id);
		$nom = $details_options['nom'];
		$description = $details_options['description'];
		$date_debut = $details_options['date_debut'];
		$date_fin = $details_options['date_fin'];
		$actif = $details_options['actif'];
		$tarif = $details_options['tarif'];
		$timbre = $details_options['timbre'];
	}	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_option.tpl'), null, null, $smarty);
	$tpl->assign('id_inscription', $id_inscription);
	$tpl->assign('record_id', $record_id);
	$tpl->assign('edit', $edit);
	$tpl->assign('actif', $actif);
	$tpl->assign('nom', $nom);
	$tpl->assign('description', $description);
	$tpl->assign('date_debut', $date_debut);
	$tpl->assign('date_fin', $date_fin);
	$tpl->assign('timbre', $timbre);
	$tpl->assign('tarif', $tarif);
	$tpl->display();
}

#
# EOF
#
?>