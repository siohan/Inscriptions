<?php

if( !isset($gCms) ) exit;


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
	debug_display($params, 'Parameters');
	
	if(isset($params['genid']) && $params['genid'] !="")
	{
			$genid = (int) $params['genid'];
	}
	if(isset($params['id_inscription']) && $params['id_inscription'] !="")
	{
			$id_inscription = (int) $params['id_inscription'];
	}
	
	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('feu_auth.tpl'), null, null, $smarty);
	$tpl->assign('genid', $genid);
	$tpl->assign('id_inscription', $id_inscription);
	$tpl->display();
	
}

#
# EOF
#
?>
