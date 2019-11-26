<?php

if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Inscriptions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

if( isset($params['cancel']) )
{
    $this->RedirectToAdminTab('cotisations');
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
			if (isset($_POST['description']) && $_POST['description'] !='')
			{
				$description = $_POST['description'];
			}
			$date_limite = mktime($_POST['limite_Hour'], $_POST['limite_Minute'], $_POST['limite_Second'],$_POST['limite_Month'], $_POST['limite_Day'], $_POST['limite_Year']);
			$timbre = mktime($_POST['envoi_Hour'], $_POST['envoi_Minute'], $_POST['envoi_Second'],$_POST['envoi_Month'], $_POST['envoi_Day'], $_POST['envoi_Year']);
			$date_debut = mktime($_POST['debut_Hour'], $_POST['debut_Minute'], $_POST['debut_Second'],$_POST['debut_Month'], $_POST['debut_Day'], $_POST['debut_Year']);
			$date_fin = mktime($_POST['fin_Hour'], $_POST['fin_Minute'], $_POST['fin_Second'],$_POST['fin_Month'], $_POST['fin_Day'], $_POST['fin_Year']);
			
		
			if ($date_fin < $date_debut)
			{
				$date_fin = $date_debut;
			}
		
			if ($date_limite < $date_fin)
			{

				$date_limite = $date_debut;
			}


			
			if (isset($_POST['actif']) && $_POST['actif'] !='')
			{
				$actif = $_POST['actif'];
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

			if($error < 1)
			{
				if($edit == 0)
				{
					$add = $insc_ops->add_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi,$timbre);
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
					$edit = $insc_ops->edit_inscription($record_id,$nom, $description, $date_limite, $date_debut, $date_fin, $actif,$groupe,$group_notif, $choix_multi,$timbre);
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
	
}
else
{
	$gp_ops = new groups;
	$liste_groupes = $gp_ops->liste_groupes_dropdown();
	
	$record_id = '';
	$edit = 0;//on est bien en train d'éditer un enregistrement
	//ON VA CHERCHER l'enregistrement en question
	$nom = '';
	$description = '';
	$date_limite = time();
	$date_debut = time();
	$date_fin = time();
	$actif = 1;
	$groupe = 1;
	$group_notif = 1;
	$choix_multi = 1;
	$timbre = time()
;	
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
			

	}
	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_inscription.tpl'), null, null, $smarty);
	$tpl->assign('record_id', $record_id);
	$tpl->assign('edit', $edit);
	$tpl->assign('actif', $actif);
	$tpl->assign('nom', $nom);
	$tpl->assign('description', $description);
	$tpl->assign('date_limite', $date_limite);
	$tpl->assign('date_debut', $date_debut);
	$tpl->assign('date_fin', $date_fin);
	$tpl->assign('groupe', $groupe);
	$tpl->assign('group_notif', $group_notif);
	$tpl->assign('timbre', $timbre);
	$tpl->assign('liste_groupes', $liste_groupes);
	$tpl->assign('choix_multi', $choix_multi);
	$tpl->display();
	
}

#
# EOF
#
?>
