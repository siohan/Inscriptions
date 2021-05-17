<?php

if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Inscriptions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

if( isset($params['cancel']) )
{
    $this->RedirectToAdminTab('inscriptions');
    return;
}
//debug_display($_POST, 'Parameters');
$insc_ops = new T2t_inscriptions;
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		$this->RedirectToAdminTab();
	}

	$error = 0;
	$edit = 0;
	$message = '';	


			if (isset($_POST['record_id']) && $_POST['record_id'] !='')
			{
				$record_id = $_POST['record_id'];
				$edit = 1;
				//on compte le nb d'options

			}

			if (isset($_POST['nom']) && $_POST['nom'] !='')
			{
				$nom = $_POST['nom'];
			}
			else
			{
				$error++;
			}
			if (isset($_POST['description']) && $_POST['description'] !='')
			{
				$description = $_POST['description'];
			}
			$timbre = time();
			if (isset($_POST['timbre']) && $_POST['timbre'] !='')
			{
				$timbre = $_POST['timbre'];
			}
			
			if (isset($_POST['ext']) && $_POST['ext'] !='')
			{
				$ext = $_POST['ext'];
			}
			
			$date_limite = mktime($_POST['limite_Hour'], $_POST['limite_Minute'], $_POST['limite_Second'],$_POST['limite_Month'], $_POST['limite_Day'], $_POST['limite_Year']);
			$date_debut = mktime($_POST['debut_Hour'], $_POST['debut_Minute'], $_POST['debut_Second'],$_POST['debut_Month'], $_POST['debut_Day'], $_POST['debut_Year']);
			$date_fin = mktime($_POST['fin_Hour'], $_POST['fin_Minute'], $_POST['fin_Second'],$_POST['fin_Month'], $_POST['fin_Day'], $_POST['fin_Year']);
			$start_collect = mktime($_POST['collect_Hour'], $_POST['collect_Minute'], $_POST['collect_Second'],$_POST['collect_Month'], $_POST['collect_Day'], $_POST['collect_Year']);
			$end_collect = mktime($_POST['end_collect_Hour'], $_POST['end_collect_Minute'], $_POST['end_collect_Second'],$_POST['end_collect_Month'], $_POST['end_collect_Day'], $_POST['end_collect_Year']);
			
		
			if ($date_fin < $date_debut)
			{
				$date_fin = $date_debut;
				$message.=" Date de fin modifiée.";
			}
		
			if ($date_limite > $date_fin)
			{
				$date_limite = $date_debut;
				$message.= " Date limite modifiée.";
			}
			
			if($end_collect > $date_limite)
			{
				$end_collect = $date_limite;
				$message.=" Date de fin de prospection modifiée. Merci de vérifier.";
			}
			 if ($start_collect > $date_limite)
			{
				$start_collect = $date_debut;
				$message.=" Date de fin de prospection modifiée. Merci de vérifier.";
			}

			
			if (isset($_POST['actif']) && $_POST['actif'] !='')
			{
				$actif = $_POST['actif'];
			}
			if (isset($_POST['partners']) && $_POST['partners'] !='')
			{
				$partners = $_POST['partners'];
			}
			if (isset($_POST['collect_mode']) && $_POST['collect_mode'] !='')
			{
				$collect_mode = $_POST['collect_mode'];
			}
			if (isset($_POST['groupe']) && $_POST['groupe'] !='')
			{
				$groupe = $_POST['groupe'];
			}
			if (isset($_POST['group_notif']) && $_POST['group_notif'] !='')
			{
				$group_notif = $_POST['group_notif'];
			}

			if (isset($_POST['choix_multi']) && $_POST['choix_multi'] !='')
			{
				$choix_multi = $_POST['choix_multi'];
			}
			if(isset($_POST['result']) && $_POST['result'] !='')
			{
				$result = $_POST['result'];
			}
			if(isset($_POST['unite']) && $_POST['unite'] !='')
			{
				$unite = $_POST['unite'];
			}
			if($unite == 'Heures')
			{
				$coeff = 3600;
			}
			else
			{
				$coeff = 3600*24;
			}
			$occurence = $coeff*$result;

			if($error < 1)
			{
				if($edit == 0)
				{
					$add = $insc_ops->add_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi,$timbre, $occurence, $ext, $start_collect, $collect_mode, $end_collect, $partners);
					if(true === $add)
					{
						
						$message.= " Inscription ajoutée.";
						$this->SetMessage($message);
					}
					else
					{
						var_dump($add);
						$message.= " Inscription non ajoutée.";
						$this->SetMessage($message);
					}
				}
				else
				{
					$edit = $insc_ops->edit_inscription($record_id,$nom, $description, $date_limite, $date_debut, $date_fin, $actif,$groupe,$group_notif, $choix_multi,$timbre,$occurence, $ext, $start_collect, $collect_mode, $end_collect, $partners);
					if(true === $edit)
					{
						$message.= " Inscription modifiée.";
						$this->SetMessage($message);
					}
					else
					{
						$message.= " Inscription non modifiée.";
						$this->SetMessage($message);
					}
				}
			}
			else
			{
				$this->SetMessage('Il manque le nom !');
			}


	$this->RedirectToAdminTab('inscriptions');//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));
	
}
else
{
	//debug_display($params, 'Parameters');
	$gp_ops = new groups;
	$liste_groupes = $gp_ops->liste_groupes_dropdown();
	
	$record_id = '';
	$edit = 0;
	//ON VA CHERCHER l'enregistrement en question
	$nom = '';
	$description = '';
	$date_limite = time();
	$date_debut = time();
	$date_fin = time();
	$ext = 0;
	$start_collect = time();
	$end_collect = time();
	$actif = 1;
	$groupe = 1;
	$collect_mode = (int) $this->GetPreference('collect_mode');
	$group_notif = 1;
	$choix_multi = 1;
	$timbre = time();
	$occurence = 0;
	$result = 0;
	$unite = 'Jours';
	$partners = 0;
	$liste_unite = array('Heures'=>'Heures', 'Jours'=>'Jours');
	
	if(isset($params['record_id']) && $params['record_id'] !="")
	{
			$record_id = $params['record_id'];
			$edit = 1;//on est bien en train d'éditer un enregistrement
			//ON VA CHERCHER l'enregistrement en question
			$details = $insc_ops->details_inscriptions($record_id);
			$nom = $details['nom'];
			$description = $details['description'];
			$date_limite = $details['date_limite'];
			$date_debut = $details['date_debut'];
			$date_fin = $details['date_fin'];
			$actif = $details['actif'];
			$groupe = $details['groupe'];
			$group_notif = $details['group_notif'];
			$choix_multi = $details['choix_multi'];
			$timbre = $details['timbre'];
			$occurence = $details['occurence'];
			$ext = $details['ext'];
			$start_collect = $details['start_collect'];
			$end_collect = $details['end_collect'];
			$collect_mode = (int) $details['collect_mode'];
			$partners = (int) $details['partners'];

	}
	
	if($occurence >0)
	{
		if(true == is_float($occurence/86400))
		{
			//on met le résultat en heures
			$result = $occurence/3600;
			$unite = 'Heures';
		
		}
		else
		{
			//on met le résultat en jours
			$result = $occurence/86400;
			$unite = 'Jours';
		
		}
	}
	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_inscription.tpl'), null, null, $smarty);
	$tpl->assign('record_id', $record_id);
	$tpl->assign('edit', $edit);
	$tpl->assign('actif', $actif);
	$tpl->assign('nom', $nom);
	$tpl->assign('description', $description);
	$tpl->assign('date_limite', $date_limite);
	$tpl->assign('date_debut', $date_debut);
	$tpl->assign('start_collect', $start_collect);
	$tpl->assign('end_collect', $end_collect);
	$tpl->assign('collect_mode', $collect_mode);
	$tpl->assign('date_fin', $date_fin);
	$tpl->assign('groupe', $groupe);
	$tpl->assign('group_notif', $group_notif);
	$tpl->assign('timbre', $timbre);
	$tpl->assign('liste_groupes', $liste_groupes);
	$tpl->assign('choix_multi', $choix_multi);
	$tpl->assign('occurence', $occurence);
	$tpl->assign('partners', $partners);
	$tpl->assign('ext', $ext);
	$tpl->assign('result', $result);
	$tpl->assign('unite', $unite);
	$tpl->assign('liste_unite', $liste_unite);
	$tpl->display();
	
}

#
# EOF
#
?>
