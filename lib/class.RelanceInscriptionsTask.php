<?php
class RelanceInscriptionsTask implements CmsRegularTask
{
//cette classe relance les inscriptions actives et automatiques dans l'intervalle de prospection et dont la date limite n'est pas dépassée
	
	
	public function get_name()
   	{
      		return get_class();
   	}

   public function get_description() {}
   

   public function test($time = '')
   {

      // Instantiation du module
      	$mess = cms_utils::get_module('Inscriptions');
	$interval =  (int) $mess->GetPreference('Interval');

      // Récupération de la dernière date d'exécution de la tâche
      if (!$time)
      {
         $time = time();
      }

      $last_execute = (int) $mess->GetPreference('last_updated');
      
      // Définition de la périodicité de la tâche (24h ici)
      if( $time > $last_execute + $interval ) return true; // hardcoded to 15 minutes
      return false;
      
   }

   public function execute($time = '')
   {

      	if (!$time)
      	{
         	$time = time();
      	}

      	$mess = cms_utils::get_module('Inscriptions');
      
      	// Ce qu'il y a à exécuter ici
	
	$db = cmsms()->GetDb();
	
	$query = "SELECT id AS id_inscription, groupe, nom FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE actif = 1 AND collect_mode = 1 AND start_collect <= UNIX_TIMESTAMP() AND end_collect > UNIX_TIMESTAMP() AND date_limite > UNIX_TIMESTAMP() AND occurence > 0 AND UNIX_TIMESTAMP() > timbre + occurence";
	
      	$dbresult = $db->Execute($query);
	//on a donc les n licences pour faire la deuxième requete
	//on commence à boucler
	if($dbresult && $dbresult->RecordCount()>0)  //la requete est ok et il y a des résultats
	{
		//on instancie la classe
		
		
		$mess_ops = new T2t_inscriptions;
		$retourid = $mess->GetPreference('pageid_inscriptions');
		$cg_ops = new CGExtensions;
		$page = $cg_ops->resolve_alias_or_id($retourid);
		
		while($row = $dbresult->FetchRow())
		{	
			$id_inscription = $row['id_inscription'];
			$group_id = $row['groupe'];
			$subject = $row['nom'];
			//on démarre la seconde requete
				//on extrait les utilisateurs (genid) du groupe sélectionné
				//attention, on élimine les utilisateurs ayant déjà répondu
				$licences = $mess_ops->relance_email_licence($id_inscription);
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
				$retourid = $mess->GetPreference('pageid_inscriptions');
				$page = $cg_ops->resolve_alias_or_id($retourid);
				foreach($adherents as $sels)
				{
					//avant on envoie dans le module emails pour tous les utilisateurs et sans traitement
					if(FALSE === $licences || FALSE === in_array($sels, $licences))
					{
						//on met les valeurs par défaut, on corrige ensuite
					//	$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$senttouser,$status, $ar);
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

								$lien = $mess->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels));
								$lien_recap = $mess->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels, "recap"=>"1"));

								$montpl = $mess->GetTemplateResource('relanceemail.tpl');						
								$smarty = cmsms()->GetSmarty();
								// do not assign data to the global smarty
								$tpl = $smarty->createTemplate($montpl);
								$tpl->assign('lien',$lien);
								$tpl->assign('lien_recap',$lien_recap);
								$tpl->assign('titre',$titre);
								$tpl->assign('description',$description);
							 	$output = $tpl->fetch();

								$cmsmailer = new \cms_mailer();

								$cmsmailer->SetSMTPDebug($flag = TRUE);
								$cmsmailer->AddAddress($email_contact, $name='');
								$cmsmailer->IsHTML(true);
								$cmsmailer->SetPriority($priority);
								$cmsmailer->SetBody($output);
								$cmsmailer->SetSubject($subject);


						                if( !$cmsmailer->Send() ) 
								{			
						                    	//return false;
								//	$mess_ops->not_sent_emails($message_id, $recipients);
						                }
							$cmsmailer->reset();

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
							$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $email_contact,$senttouser,$status, $ar);

						}
					}
				}//fin du foreach
			
				//on change le timbre de cette inscription 
				$timbre = time();
				$mess_ops->update_timbre($id_inscription, $timbre);
			
		}// fin du while
		
	
		
			
	}
	else
	{
		return false;
	}
	//return true; // Ou false si ça plante
	
	
	

      

   }

   public function on_success($time = '')
   {

      if (!$time)
      {
         $time = time();
      }
      
      $mess = cms_utils::get_module('Inscriptions');
      $mess->SetPreference('LastSendNotification', $time);
      $mess->Audit('','Inscriptions','Relance Inscriptions Ok');
      
   }

   public function on_failure($time = '')
   {
      $mess = cms_utils::get_module('Inscriptions');
	$mess->Audit('','Inscriptions','Pas de nouvelles relances des inscriptions');
   }

}
?>