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
	$mess_ops = new T2t_messages;
	$time_envoi = $mess_ops->datetotimeunix($senddate, $sendtime);

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
		$mess_ops = new T2t_messages;
		$insc_ops = new T2t_inscriptions;
		$timbre = time();
		$mess = $mess_ops->add_message($sender, $senddate, $sendtime, $replyto, $group_id,$recipients_number, $subject, $description, $sent, $priority, $timbre);
		$message_id =$db->Insert_ID();
		
		
		
		if ($sent == 1)
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
	
			foreach($adherents as $sels)
			{
				//avant on envoie dans le module emails pour tous les utilisateurs et sans traitement
				if(FALSE === $licences || FALSE === in_array($sels, $licences))
				{
					$query = "SELECT contact FROM ".cms_db_prefix()."module_adherents_contacts WHERE genid = ? AND type_contact = 1 LIMIT 1";
					$dbresult = $db->Execute($query, array($sels));
					if($dbresult && $dbresult->RecordCount()>0)
					{
						$row = $dbresult->FetchRow();

						$email_contact = $row['contact'];
						$destinataires = array();

						if(!is_null($email_contact))
						{
							$destinataires['emails'] = $email_contact;
							$destinataires['genid'] = $sels;
							$senttouser = 1;
							$status = "Email Ok";
							
							$ar = 0;
							//on consruit une url
							$retourid = $this->GetPreference('pageid_inscriptions');
							$page = $cg_ops->resolve_alias_or_id($retourid);
							$lien = $this->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels));
							$montpl = $this->GetTemplateResource('relanceemail.tpl');						
							$smarty = cmsms()->GetSmarty();
							// do not assign data to the global smarty
							$tpl = $smarty->createTemplate($montpl);
							$tpl->assign('lien',$lien);
							$tpl->assign('titre',$titre);
							$tpl->assign('description',$description);
						 	$output = $tpl->fetch();
						
							$cmsmailer = new \cms_mailer();
							$cmsmailer->reset();
							$cmsmailer->AddAddress($email_contact);
							$cmsmailer->IsHTML(true);
							$cmsmailer->SetPriority($priority);
							$cmsmailer->SetBody($output);
							$cmsmailer->SetSubject($subject);
							$cmsmailer->Send();
					                if( !$cmsmailer->Send() ) 
							{			
					                    	return false;
								$mess_ops->not_sent_emails($message_id, $recipients);
					                }
					
						//	$envoi = $insc_ops->send_normal_email($sender, $email_contact,$subject, $priority, $lien);
						/*	if(FALSE === $envoi)
							{
								$mess_ops->not_sent_emails($message_id, $recipients);
							}
						*/	
						}
						else
						{
							//on indique l'erreur : pas d'email disponible !
							$senttouser = 0;
							$status = "Email absent";
							$ar = 0;
							$email_contact = "rien";
						}

						$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$senttouser,$status, $ar);
					}
					else
					{
						//une erreur sur l'email, on fait quoi ?
						//on indique l'erreur : pas d'email disponible !
						$senttouser = 0;
						$status = "Email absent";
						$ar = 0;
						$email_contact = "rien";
						$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$senttouser,$status, $ar);
						
					}
				}
			}
				
				
			
		
		}
		$this->SetMessage('Résultats des envois dans le module Asso Messages');
		$this->RedirectToAdminTab('pres');
	}
	
}
else
{
	$this->SetMessage('Il manque un paramètre !');
	$this->RedirectToAdminTab('pres');
}

?>
