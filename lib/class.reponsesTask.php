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
      $ping = \cms_utils::get_module('Inscriptions');

      // Récupération de la dernière date d'exécution de la tâche
      if (!$time)
      {
         $time = time();
      }

      $last_execute = (int) $ping->GetPreference('last_updated');
     	

      // Définition de la périodicité de la tâche (24h ici)
      	if( $last_execute >= ($time - 10) ) 
	{
		return FALSE; // hardcoded to 15 minutes
	}
	else
	{
		 return TRUE;
	}
     
     
      
      
   }


   public function execute($time = '')
   {

      $db = \CmsApp::get_instance()->GetDb();
      if (!$time)
      {
         $time = time();
      }

      $ping = \cms_utils::get_module('Inscriptions');
     
	// Ce qu'il y a à exécuter ici
			
	$last_updated = $ping->GetPreference('last_updated');
	$admin_email = $ping->GetPreference('admin_email');
	$query = "SELECT id_inscription, id_option, genid, timbre FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE timbre > ? ORDER BY id_inscription ASC, id_option ASC";
	$dbresult = $db->Execute($query, array($last_updated));
	if($dbresult)
	{
		if($dbresult->RecordCount() > 0)
		{

			//on instancie la classe et on va commencer à boucler
			$group = $ping->GetPreference('default_group');
			
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

						$query = "SELECT contact FROM ".cms_db_prefix()."module_adherents_contacts WHERE genid = ? AND type_contact = 1 LIMIT 1";
						$dbresult = $db->Execute($query, array($sels));
						$row = $dbresult->FetchRow();

						$email_contact = $row['contact'];
						//var_dump($email_contact);

						if(!is_null($email_contact))
						{
							$destinataires[] = $email_contact;
						}

					}
				}
			}
			$tt_ops = new T2t_inscriptions;
			$adh_ops = new Asso_adherents;
			$rowarray= array();
			while ($row= $dbresult->FetchRow())
			{
				$onerow= new StdClass();
				$inscription = $tt_ops->details_inscriptions($row['id_inscription']);
				$onerow->nom = $inscription['nom'];
				$option = $tt_ops->details_option($row['id_option']);
				$onerow->nom_option = $option['nom'];
				$details_adh = $adh_ops->details_adherent_by_genid($row['genid']);
				$onerow->nom_genid = $details_adh['nom'];
				$rowarray[]= $onerow;
			}
			$smarty->assign('items', $rowarray);//nom_genid
			$smarty->assign('itemcount', count($rowarray));
			//on prépare le message
			$subject = "Inscriptions ajoutées ou modifiées";
			$message = $this->GetTemplate('send_email');
			$body = $this->ProcessTemplateFromData($message);
		//	$smarty->assign('items', $rowarray);//nom_genid
			
			//on rajoute le mail de l'admin
			$destinataires[] = $admin_email;
			foreach($destinataires as $item=>$v)
			{

			//var_dump($v);

				$cmsmailer = new \cms_mailer();
				$cmsmailer->reset();
			//	$cmsmailer->SetFrom($sender);//$this->GetPreference('admin_email'));
				$cmsmailer->AddAddress($v,$name='');
				$cmsmailer->IsHTML(true);
				$cmsmailer->SetPriority('1');
				$cmsmailer->SetBody($body);
				$cmsmailer->SetSubject($subject);
				$cmsmailer->Send();
		                if( !$cmsmailer->Send() ) 
				{			
		                    	//$mess_ops->not_sent_emails($message_id, $recipients);
					$this->Audit('',$this->GetName(),'Problem sending email to '.$item);

		                }
			}



			return TRUE; // Ou false si ça plante
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
      
      $ping = cms_utils::get_module('Inscriptions');
      $ping->SetPreference('last_updated', $time);
      $ping->Audit('','Inscriptions','Réponses envoyées');
      //$pong = cms_utils::get_module
      
   }

   public function on_failure($time = '')
   {
      $ping = \cms_utils::get_module('Inscriptions');
$ping->Audit('','Inscriptions','Pas de réponses');
   }

}
?>