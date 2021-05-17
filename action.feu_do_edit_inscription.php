<?php
if (!isset($gCms)) exit;

debug_display($_POST, 'Parameters');

$aujourdhui = date('Y-m-d ');
//les valeurs par défaut
$user_crea = 0;//Pour savoir s'il faut confirmer le compte ou pas
$genid = 0;
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$insc_ops = new T2t_inscriptions;	
$gp_ops = new groups;	
		
		if (isset($_POST['id_inscription']) && $_POST['id_inscription'] !='')
		{
			$id_inscription = $_POST['id_inscription'];
			$details = $insc_ops->details_inscriptions($id_inscription);
			$choix = $details['choix_multi'];
			$partners = $details['partners'];
			$id_group = $details['groupe'];
			$titre = $details['nom'];
			$description = $details['description'];
		}
		else
		{
			$error++;
		}
				
		if (isset($_POST['nom']) && $_POST['nom'] !='')
		{
			$id_option = $_POST['nom'];		
		}
		else
		{
			$error++;
		}
		if(isset($_POST['user_name']) && $_POST['user_name'] !='')
		{
			$user_name = $_POST['user_name'];
		}
		else
		{
			$error++;
		}
		if(isset($_POST['user_forename']) && $_POST['user_forename'] !='')
		{
			$user_forename = $_POST['user_forename'];
		}
		else
		{
			$error++;
		}
		if(isset($_POST['user_email']) && $_POST['user_email'] !='')
		{
			$user_email = $_POST['user_email'];
		}
		if(isset($_POST['genid']) && $_POST['genid'] !='')
		{
			$genid = $_POST['genid'];
		}
		
		if($error < 1)
		{
					//il faut créer un genid de session
					//on regarde si on a un genid valable
					if($genid >0)
					{
						$genid = $genid;
					}
					else
					{
						// pas de genid fournit : 
						// 1 - Soit l'utilisateur existe et il faut le chercher
						// 2 - Il n'y a pas de genid correspondant : on créé l'utilisateur
						
						$adh_ops = new Asso_adherents;
						$genid = $adh_ops->search_member_genid($user_name, $user_forename);
						if(false == $genid)//pas de genid, l'utilisateur n'est pas ds la base, on le créé
						{
							//on créé l'utilisateur
							$user_crea = 1;
							$genid = $adh_ops->random_int(9);
							$actif = 0;//doit confirmer via un mail ci-dessous
							$sexe = '';
							$anniversaire = '1970-01-01';
							$licence = '';
							$adresse = '';
							$code_postal = '';
							$ville = '';
							$pays = '';
							$externe = 1;
							$crea = $adh_ops->add_adherent($genid,$actif, $user_name, $user_forename, $sexe, $anniversaire, $licence,$adresse, $code_postal, $ville, $pays,$externe);
							if(true == $crea)
							{
								//on inclus l'email avec le genid
								 $con_ops = new contact;
								 //pour l'email : type_contact = 1
								 $type_contact="1";
								 $description = '';
								 $add_email = $con_ops->add_contact($genid, $type_contact,$user_email,$description);
							}
						}
					}
					if($user_crea = 0)
					{
						$checked = 1;
					}
					else
					{
						$checked = 0;
					}
					
					
					if(true === is_array($id_option) && count($id_option)>0)
					{
						foreach($id_option as $key)
						{
							
							$add = $insc_ops->add_reponse($id_inscription, $key, $genid, $genid, $checked);
						}
					}
					else
					{
						$add = $insc_ops->add_reponse($id_inscription, $id_option, $genid,$genid,$checked);
					}
					
					
					
					
					if(true === $add )
					{
						//$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_feu_inscription.tpl'), null, null, $smarty);
						//$tpl->assign('final_msg', $final_msg);	
						
						//on envoi un email pour récapituler la commande
						//les variables nécessaires :
						//display=crea; id_inscription, $id_group
						
						
						
						
						if($checked == 0)
						{
							//on envoit le mail pour confirmer le compte et donc les inscriptions
							$subject = 'Validation de votre compte';
							$cg_ops = new CGExtensions;
							$mod = \cms_utils::get_module('Adherents');
							$retourid = $mod->GetPreference('pageid_subscription');
							$page = $cg_ops->resolve_alias_or_id($retourid);
							$lien = $mod->create_url($id,'default',$page, array("display"=>"activate","id_inscription"=>$id_inscription,"id_group"=>$id_group, "record_id"=>$genid));
							$montpl = $this->GetTemplateResource('account_validation.tpl');						
							$smarty = cmsms()->GetSmarty();
							// do not assign data to the global smarty
							$tpl = $smarty->createTemplate($montpl);
							$tpl->assign('lien',$lien);
							$tpl->assign('titre',$titre);
							$tpl->assign('description',$description);
						 	$output = $tpl->fetch();
						
							$cmsmailer = new \cms_mailer();
							
							//$cmsmailer->SetSMTPDebug($flag = TRUE);
							$cmsmailer->AddAddress($user_email, $name='');
					//		$cmsmailer->AddBCC('claude.siohan@gmail.com', $name="Webmaster RPF");
							$cmsmailer->IsHTML(true);
							$cmsmailer->SetPriority(3);
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
							
						}
						
						
							$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_partners.tpl'), null, null, $smarty);
							$tpl->assign('genid', $genid);
							$tpl->assign('id_inscription', $id_inscription);
							
							$tpl->display();
							
						
					}
					else
					{
						echo 'Inscription non ajoutée !!';
					}
				
		
		}
		else
		{
			$this->SetMessage('Parametre(s) manquant(s)');
		}
			


?>