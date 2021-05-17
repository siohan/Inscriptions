<?php
if(!isset($gCms)) exit;
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
if(isset($params['id_inscription']) && $params['id_inscription'] !='')
{
	$id_inscription = $params['id_inscription'];
	$insc_ops = new T2t_inscriptions;
	$details = $insc_ops->details_inscriptions($id_inscription);
	$titre = $details['nom'];
	$choix_multi = $details['choix_multi'];
	$description = $details['description'];
	$smarty->assign('titre', $titre);
	$smarty->assign('description', $description);
	$subject = $titre;
	$error = 0;
	
	$group_id = $details['groupe'];
	$sender = $this->GetPreference('admin_email');	
	$priority = $params['priority'] = '1';
	
//	$message = $this->GetTemplate('relanceemail');
//	$body = $this->ProcessTemplateFromData($body);
	$aujourdhui = time();
	

	if(isset($params['senddate']) && $params['senddate'])
	{
		$senddate = $params['senddate'];
	}
	else
	{
		$senddate = date('Y-m-d');
	}
	if(isset($params['sendtime']) && $params['sendtime'])
	{
		$sendtime = $params['sendtime'];
	}
	else
	{
		$sendtime = date("H:i:s");
	}
	
	$time_envoi = $insc_ops->datetotimeunix($senddate, $sendtime);

	$sent = 1;
	if($time_envoi > $aujourdhui)
	{
		$sent = 0;
	}

	if($error >0)
	{
		//pas glop, des erreurs !
		echo "trop d\'erreurs !";
	}
	else
	{
		// on commence le traitement
		//if($aujourdhui <= $senddate = date('Y-m-d');
	//	$timbre = $this->mktime($Time_hour);
		$replyto = $sender;
		$gp_ops = new groups;
		$recipients_number = $gp_ops->count_users_in_group($group_id);
		$mod_messages = \cms_utils::get_module('Messages');
		$mess_ok =0;
		if (is_object($mod_messages) && true == $this->GetPreference('use_messages'))
		{
			$mess_ok = 1;
			$mess_ops = new T2t_messages;
			$insc_ops = new T2t_inscriptions;
			$timbre = time();
			$ar = 0;
			$relance = 0;
			$occurence = 0;
			$mess = $mess_ops->add_message($sender, $senddate, $sendtime, $replyto, $group_id,$recipients_number, $subject, $description, $sent, $priority, $timbre, $ar, $relance, $occurence);
			$message_id =$db->Insert_ID();
		}
		
		
		if ($sent == 1) //pour un envoi immédiat
		{
			//on extrait les utilisateurs (genid) du groupe sélectionné
			//attention, on élimine les utilisateurs ayant déjà répondu
			$licences = $insc_ops->relance_email_licence($id_inscription);
			//var_dump($licences);
			if(is_array($licences) && count($licences) > 0 )
			{
				$tab = implode(', ',$licences);	
			}
		//	$tab = implode(', ',$licences);
			$contacts_ops = new contact;
			$adherents = $contacts_ops->UsersFromGroup($group_id);
			$cg_ops = new CGExtensions;
			//var_dump($adherents);
			$retourid = $this->GetPreference('pageid_inscriptions');
			$page = $cg_ops->resolve_alias_or_id($retourid);
			foreach($adherents as $sels)
			{
				//avant on envoie dans le module emails pour tous les utilisateurs et sans traitement
				if(FALSE === $licences || FALSE === in_array($sels, $licences))
				{
					//on met les valeurs par défaut, on corrige ensuite
					
					$query = "SELECT contact FROM ".cms_db_prefix()."module_adherents_contacts WHERE genid = ? AND type_contact = 1 LIMIT 1";
					$dbresult = $db->Execute($query, array($sels));
					if($dbresult && $dbresult->RecordCount()>0)
					{
						$row = $dbresult->FetchRow();

						$email_contact = $row['contact'];
						var_dump($email_contact);
						$destinataires = array();

						if(!is_null($email_contact))
						{
							
							$senttouser = 1;
							$status = "Email Ok";
							
							$ar = 0;
							//on consruit une url
							
							$lien = $this->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels));
							$lien_recap = $this->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels, "recap"=>"1"));
							
							$montpl = $this->GetTemplateResource('relanceemail.tpl');						
							$smarty = cmsms()->GetSmarty();
							// do not assign data to the global smarty
							$tpl = $smarty->createTemplate($montpl);
							$tpl->assign('lien',$lien);
							$tpl->assign('lien_recap',$lien_recap);
							$tpl->assign('titre',$titre);
							$tpl->assign('description',$description);
						 	$output = $tpl->fetch();
						
							$cmsmailer = new \cms_mailer();
							
							//$cmsmailer->SetSMTPDebug($flag = TRUE);
							$cmsmailer->AddAddress($email_contact, $name='');
					//		$cmsmailer->AddBCC('claude.siohan@gmail.com', $name="Webmaster RPF");
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
									$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$output,$senttouser,$status, $ar);
								}
					        }
							else
							{
								if($mess_ok == 1)
								{
									$senttouser = 1;
									$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact, $output, $senttouser,$status, $ar);
								}
							}
						//$cmsmailer->reset();
						
						}
						else
						{
							//on indique l'erreur : pas d'email disponible !
							$senttouser = 0;
							$status = "Email absent";
							$ar = 0;
							$email_contact = "rien";
						}
						unset($email_contact);

						
					}
					else //pas de résultats à la requete des contacts emails
					{
						//une erreur sur l'email, on fait quoi ?
						//on indique l'erreur : pas d'email disponible !
						$senttouser = 0;
						$status = "Email absent";
						$ar = 0;
						$email_contact = "rien";
						$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$output, $senttouser,$status, $ar);
						
					}
				}
			}//fin du foreach
		}//fin du if sent == 1
		
		$this->SetMessage('Envois effectués');
		$this->RedirectToAdminTab('insc');
	}//fin du if $error
	
}
else
{
	$this->SetMessage('Il manque un paramètre !');
	$this->RedirectToAdminTab('insc');
}

?>
