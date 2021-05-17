<?php
if( !isset($gCms) ) exit;
$db = cmsms()->GetDb();
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		//$this->RedirectToAdminTab();
	}
	$message_final = '';
	//debug_display($_POST, 'Parameters');
	$ass = new Asso_adherents;
	$gp_ops = new groups;
	$cont_ops = new contact;
	
	
	$validation = true;
	$success = 0;
	
	$error = 0;
	
	if(isset($_POST['id_inscription']) && $_POST['id_inscription'] != '')
	{
		$id_inscription = $_POST['id_inscription'];
		$insc_ops = new T2t_inscriptions;
		$details = $insc_ops->details_inscriptions($id_inscription);
		$id_group = $details['groupe'];
	}
	else
	{
		$error++;
		$message_final.=" Pas d'inscription indiquée !!";
		$success++;
	}
	if($error <1)
	{
	
		if(isset($_POST['licence']) && $_POST['licence'] != '')
		{
			$licence = $_POST['licence'];
			//la licence a été saisie, on fait une recherche
			$user_exists = $ass->already_exists($licence);
			if(true == $user_exists)
			{
				//il faut récuperer son genid
				$details = $ass->details_adherent_by_licence($licence);
				$genid = $details['genid'];
				//var_dump($genid);
				$member = $gp_ops->is_member($genid, $id_group);
				//on regarde s'il appartient bien au groupe
				if(false == $member)
				{	
					//si non on l'enregistre ds le groupe
					$assign = $gp_ops->assign_user_to_group($id_group, $genid);
					if(true == $assign)
					{
						$message_final.=" Utilisateur ajouté au groupe";
					}
					else
					{
						$message_final.=" Gulp ! L'utilisateur n'a pas été ajouté";
					}
				}
				//on renvoit un mail avec ce qu'il faut dedans
				//on va d'abord chercher son mail
				
				
				$email_contact = $cont_ops->email_address($genid);
				if(!is_null($email_contact))
				{
							
							$cg_ops = new CGExtensions;
							//var_dump($adherents);
							$retourid = $this->GetPreference('pageid_inscriptions');
							$page = $cg_ops->resolve_alias_or_id($retourid);
							
							
							$senttouser = 1;
							$status = "Email Ok";
							
							$ar = 0;
							//on consruit une url
							
							$lien = $this->create_url('cntnt01','default',$page, array("id_inscription"=>$id_inscription, "genid"=>$genid));
							
							$subject = "Lien d'activation";
							$montpl = $this->GetTemplateResource('relanceemail.tpl');						
							$smarty = cmsms()->GetSmarty();
							// do not assign data to the global smarty
							$tpl = $smarty->createTemplate($montpl);
							$tpl->assign('lien',$lien);
							$tpl->assign('titre',$titre);
							$tpl->assign('description',$description);
						 	$output = $tpl->fetch();
						
							$cmsmailer = new \cms_mailer();
							
							//$cmsmailer->SetSMTPDebug($flag = TRUE);
							$cmsmailer->AddAddress($email_contact, $name='');
							$cmsmailer->AddBCC('claude.siohan@gmail.com', $name="Webmaster");
							$cmsmailer->IsHTML(true);
							$cmsmailer->SetPriority($priority);
							$cmsmailer->SetBody($output);
							$cmsmailer->SetSubject($subject);
							
							
					                if( !$cmsmailer->Send()  ) 
							{			
					                    	//return false;
								if($mess_ok == 1)
								{
									$senttouser = 0;
									$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $genid, $email_contact,$output,$senttouser,$status, $ar);
								}
					                }
							else
							{
								if($mess_ok == 1)
								{
									$senttouser = 1;
									$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $genid, $email_contact, $output, $senttouser,$status, $ar);
								}
							}
						$cmsmailer->reset();
						
				}
				else
				{
					//on indique l'erreur : pas d'email disponible !
					$senttouser = 0;
					$success++;
					$message_final.= "Email absent";
					$ar = 0;
					$email_contact = "rien";
				}
					
				$message_final.= " Vous allez recevoir un mail avec un lien pour vous inscrire.";
			
			}
			else
			{
				//l'utilisateur n'existe pas ! Intrusion ?
				$message_final.=" Votre licence est introuvable !";
				$success++;
			}
		}
		else
		{
			$message_final.= "Licence absente !";
			$success++;	
		}
		/*
		if(isset($_POST['genid']) && $_POST['genid'] != '')
		{
			$genid = $_POST['genid'];
		}
		if(isset($_POST['identifiant']) && $_POST['identifiant'] != '')
		{
			$identifiant = $_POST['identifiant'];
		}
		*/
	}
	else
	{
		$message_final.= "Il y a des erreurs !";
		$success++;	
	}
	$smarty->assign('validation', true);
	$smarty->assign('success', $success);
	$smarty->assign('message_final', $message_final);	


}
else
{
	
	//debug_display($params, 'Parameters');
	$validation = false;
	
	if(isset($params['id_inscription']) && $params['id_inscription'] != '')
	{
		$id_inscription = $params['id_inscription'];
		$error++;
	}
	


}
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('auth_by_number.tpl'), null, null, $smarty);
$tpl->assign('licence', $licence);
$tpl->assign('id_inscription', $id_inscription);
$tpl->assign('validation', $validation);
$tpl->assign('success', $success);

//$tpl->assign('genid', $genid);
//$tpl->assign('identifiant', $identifiant); //username FEU
$tpl->display();



?>