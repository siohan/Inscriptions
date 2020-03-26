<?php
if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Inscriptions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
	
//debug_display($params, 'Parameters');
$last_updated = (int) $this->GetPreference('last_updated');
$admin_email = $this->GetPreference('admin_email');
$query = "SELECT id_inscription, id_option, genid, timbre FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE timbre > ? ORDER BY id_inscription ASC, id_option ASC";
//echo $query;
$dbresult = $db->Execute($query, array($last_updated));
if($dbresult)
{
	if($dbresult->RecordCount() > 0)
	{
		//on instancie la classe et on va commencer à boucler
		$group = $this->GetPreference('default_group');
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
					$query = "SELECT contact FROM ".cms_db_prefix()."module_adherents_contacts WHERE genid = ? AND type_contact = 1 LIMIT 1";
					$dbresult = $db->Execute($query, array($sels));
					$row = $dbresult->FetchRow();

					$email_contact = $row['contact'];
					
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
		$smarty->assign('items', $rowarray);
		$smarty->assign('itemcount', count($rowarray));
		
		//on prépare le message
		$subject = "Inscriptions ajoutées ou modifiées";
		$message = $this->GetTemplate('send_email');
		$body = $this->ProcessTemplateFromData($message);
	
		//on rajoute le mail de l'admin
		$destinataires[] = $admin_email;
		var_dump($destinataires);
		
		foreach($destinataires as $item=>$v)
		{

		
			$headers = "From: ".$admin_email."\n";
			$headers .= "Reply-To: ".$admin_email."\n";
			$headers .= "Content-Type: text/html; charset=\"utf-8\"";
			$cmsmailer = new \cms_mailer();
			$cmsmailer->reset();
		//	$cmsmailer->SetFrom($sender);//$this->GetPreference('admin_email'));
			$cmsmailer->AddAddress($v,$name='');
			$cmsmailer->IsHTML(true);
		//	$cmsmailer->SetPriority($priority);
			$cmsmailer->SetBody($body);
			$cmsmailer->SetSubject($subject);
	                if( !$cmsmailer->Send() ) 
			{			
	                    	//$mess_ops->not_sent_emails($message_id, $recipients);
				$this->audit('',$this->GetName(),'Problem sending email to '.$item);

	                }
		}		
		
		//return TRUE; // Ou false si ça plante
	}
	else
	{
		$this->SetMessage('Pas de nouvelles inscriptions');
	}
	
}  
else
{
	$this->SetMessage('Erreur de requete !');
}
$this->Redirect($id, 'defaultadmin', $returnid);
