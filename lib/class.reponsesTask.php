<?php
class reponsesTask implements CmsRegularTask
{

   public function get_name()
   {
      return get_class();
   }

   public function get_description()
   {
      return 'Récuperation des inscriptions.';
   }

   
	public function test($time = '')
   {

      // Instantiation du module
      $insc = \cms_utils::get_module('Inscriptions');

      // Récupération de la dernière date d'exécution de la tâche
      if (!$time)
      {
         $time = time();
      }

      $last_execute = (int) $insc->GetPreference('last_updated');
     	

      // Définition de la périodicité de la tâche (24h ici)
      	if( $time - $last_execute >= 86400 )
	{
		return TRUE; // hardcoded to 15 minutes
	}
	else
	{
		 return FALSE;
	}
     
     
      
      
   }


   public function execute($time = '')
   {

      $db = \CmsApp::get_instance()->GetDb();
      if (!$time)
      {
         $time = time();
      }

      $insc = \cms_utils::get_module('Inscriptions');
     //var_dump($insc);
	// Ce qu'il y a à exécuter ici
			
	$last_updated = $insc->GetPreference('last_updated');
	$admin_email = $insc->GetPreference('admin_email');
	
	$query = "SELECT id, group_notif FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE actif = 1 AND date_limite > UNIX_TIMESTAMP()";
//	$query = "SELECT be.id_inscription, be.id_option, be.genid, be.timbre FROM ".cms_db_prefix()."module_inscriptions_belongs AS be, ".cms_db_prefix()."module_inscriptions_inscriptions AS ins WHERE ins.id_inscription = be.id_inscription AND be.timbre > ? ORDER BY be.id_inscription ASC, be.id_option ASC";
	$dbresult = $db->Execute($query);
	if($dbresult)
	{
		if($dbresult->RecordCount()>0)
		{
			
			while($row = $dbresult->FetchRow())
			{
				$id_inscription = $row['id'];
				$group = $row['group_notif'];
				$query2 = "SELECT id_inscription, id_option, genid, timbre FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE timbre > ? AND id_inscription = ?";
				$dbresult2 = $db->Execute($query2, array($last_updated, $id_inscription));
				if($dbresult2 && $dbresult2->RecordCount()>0)
				{
					$destinataires = array();
					if($group != 0)
					{
						//on récupére les genid du group
						$gp_ops = new groups;
						$genids = $gp_ops->liste_licences_from_group($group);
						if(false !== $genids)
						{
							//maintenant on récupère les adresses emails
							foreach($genids as $sels)
							{
								//avant on envoie dans le module emails pour tous les utilisateurs et sans traitement

								$query3 = "SELECT contact FROM ".cms_db_prefix()."module_adherents_contacts WHERE genid = ? AND type_contact = 1 LIMIT 1";
								$dbresult3 = $db->Execute($query3, array($sels));
								$row3 = $dbresult3->FetchRow();

								$email_contact = $row3['contact'];
								//var_dump($email_contact);

								if(!is_null($email_contact))
								{
									$destinataires[] = $email_contact;
								}

							}
						}
					}
					$cg_ops = new CGExtensions;
					//on créé une url pour voir le résultat des inscriptions
					$retourid = $insc->GetPreference('pageid_inscriptions');
					$page = $cg_ops->resolve_alias_or_id($retourid);
					$lien_recap = $insc->create_url($id,'default',$page, array("id_inscription"=>$id_inscription, "genid"=>$sels,"recap"=>"1"));

					$tt_ops = new T2t_inscriptions;
					$adh_ops = new Asso_adherents;

					$rowarray= array();
					while ($row= $dbresult2->FetchRow())
					{
						$onerow= new StdClass();
						$inscription = $tt_ops->details_inscriptions($row['id_inscription']);
						$onerow->nom = $inscription['nom'];
						$option = $tt_ops->details_option($row['id_option']);
						$onerow->nom_option = $option['nom'];
						$details_adh = $adh_ops->details_adherent_by_genid($row['genid']);
						$onerow->nom_genid = $details_adh['nom'].' '.$details_adh['prenom'];
						$onerow->timbre = date('d-m-Y H:i:s',$row['timbre']);
						$rowarray[]= $onerow;
					}
					$subject = "Inscriptions ajoutées ou modifiées";
					$montpl = $insc->GetTemplateResource('orig_send_email.tpl');						
					$smarty = cmsms()->GetSmarty();
					// do not assign data to the global smarty
					$tpl = $smarty->createTemplate($montpl);
					$tpl->assign('items',$rowarray);
					$tpl->assign('itemcount',count($rowarray));
					$tpl->assign('lien_recap',$lien_recap);
					$output = $tpl->fetch();

					//et on envoie
					$cmsmailer = new \cms_mailer();
					foreach($destinataires as $value)
					{
						
						$cmsmailer->reset();

						$cmsmailer->AddAddress($value,$name='');
						$cmsmailer->IsHTML(true);
						$cmsmailer->SetPriority('1');
						$cmsmailer->SetBody($output);
						$cmsmailer->SetSubject($subject);
						$cmsmailer->Send();
				                if( !$cmsmailer->Send() ) 
						{			
				                    	//$mess_ops->not_sent_emails($message_id, $recipients);
							$this->Audit('',$this->GetName(),'Problem sending email to '.$item);

				                }
						unset($value);
					}
					unset($destinataires);


				
				}
			}//maintenant on va chercher s'il y a des nouvelles réponses
						
			return true;
			
			
		
		
			
		}
		else
		{
			return false;//echo 'Pas de results';
		}

	}  
	else
	{
		return false;//echo $db->ErrorMsg();//return FALSE;
	}

      
      

   }

   public function on_success($time = '')
   {

      if (!$time)
      {
         $time = time();
      }
      
      $insc = cms_utils::get_module('Inscriptions');
      $insc->SetPreference('last_updated', $time);
      $insc->Audit('','Inscriptions','Réponses envoyées');
      //$pong = cms_utils::get_module
      
   }

   public function on_failure($time = '')
   {
      $insc = \cms_utils::get_module('Inscriptions');
$insc->Audit('','Inscriptions','Pas de réponses');
   }

}
?>