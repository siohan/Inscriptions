<?php
#CMS - CMS Made Simple



class T2t_inscriptions
{
  function __construct() {}

function details_inscriptions($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,nom, description, date_limite, date_debut, date_fin, heure_debut, heure_fin, actif, statut, groupe, choix_multi FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE id = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	$details = array();
	if($dbresult)
	{
		while($row = $dbresult->FetchRow())
		{
			$details['id_inscription'] = $row['id'];
			$details['nom'] = $row['nom'];
			$details['description'] = $row['description'];
			$details['date_limite'] = $row['date_limite'];
			$details['date_debut'] = $row['date_debut'];
			$details['date_fin'] = $row['date_fin'];
			$details['heure_debut'] = $row['heure_debut'];
			$details['heure_fin'] = $row['heure_fin'];
			$details['actif'] = $row['actif'];
			$details['statut'] = $row['statut'];
			$details['groupe'] = $row['groupe'];
			$details['choix_multi'] = $row['choix_multi'];
		}
	}
		return $details;
	

}
//ajoute une inscription
function add_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut, $groupe, $choix_multi)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_inscriptions (nom, description, date_limite, date_debut, date_fin, heure_debut, heure_fin, actif, statut, groupe, choix_multi) VALUES (?, ?,  ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($nom, $description, $date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut, $groupe, $choix_multi));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
##
function edit_inscription($record_id,$nom, $description,$date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut,$groupe, $choix_multi)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET nom = ?, description = ?, date_limite = ?, date_debut = ?, date_fin = ?, heure_debut = ?, heure_fin = ?, actif = ?, statut = ?, groupe = ?, choix_multi = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($nom, $description, $date_limite, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut,$groupe, $choix_multi, $record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//Supprime une inscription
function delete_inscription($record_id)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//active ou désactive une inscription
function activate_desactivate_inscription($id_inscription,$action)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET actif = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($action, $id_inscription));
}
//renvoie le nb de joueurs dans une option donnée
function count_users_in_inscription($id_inscription)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$nb = $row['nb'];
	}
	else
	{
		$nb = 0;
	}
	return $nb;
}
//supprime un adhérent d'une inscription !
function delete_users_in_inscription($id_inscription, $genid)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $genid));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
	
}
//indique si un joueur est inscrit à une inscription
function is_inscrit($id_inscription, $genid)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id_inscription, genid FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $genid));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//LES OPTIONS
//
//détails d'une option
function details_option($record_id)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,id_inscription,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif, tarif, groupe FROM ".cms_db_prefix()."module_inscriptions_options WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	$details = array();
	if($dbresult)
	{
		while($row = $dbresult->FetchRow())
		{
			$details['id_inscription'] = $row['id'];
			$details['nom'] = $row['nom'];
			$details['description'] = $row['description'];
			$details['date_debut'] = $row['date_debut'];
			$details['date_fin'] = $row['date_fin'];
			$details['heure_debut'] = $row['heure_debut'];
			$details['heure_fin'] = $row['heure_fin'];
			$details['actif'] = $row['actif'];
			$details['tarif'] = $row['tarif'];
			$details['groupe'] = $row['groupe'];
		}
	}
		return $details;
	


}
//ajoute une option à une inscription
function add_option($id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $tarif)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_options (id_inscription,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif,tarif) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $tarif));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//edite une option
function edit_option($record_id,$id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif,$tarif)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_options SET id_inscription = ?, nom = ?, description = ?, date_debut = ?, date_fin = ?, heure_debut = ?, heure_fin = ?, actif = ?,tarif = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif,$tarif, $record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//supprime toutes les options d'une inscription
function delete_options($record_id)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//supprime une seule option d'une inscription
function delete_option($record_id)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_options WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//compte le nb d'options d'une inscription
function count_options_per_inscription($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT COUNT(*) AS nb FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ? AND actif = 1";
	$dbresult = $db->Execute($query, array($id_inscription));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$nb = $row['nb'];
	}
	else
	{
		$nb = 0;
	}
	return $nb;
}
//supprime tous les adhérents d'une option
function delete_users_in_option($id_option)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ?";
	$dbresult = $db->Execute($query, array($id_option));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//supprime un seul adhérent d'une seule option
function delete_user_in_option($id_option, $genid)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_option, $genid));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//supprime toutes les adhésions d'une inscription
function delete_inscription_belongs($record_id)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//indique si un joueur est inscrit à une option particulière d'une manifestation
function is_inscrit_opt($id_option, $genid)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id_option, genid FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_option, $genid));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//cherche l'id d'une option pour le formulaire
function search_id_option($id_inscription, $nom)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ? AND nom = ? LIMIT 1";
	$dbresult = $db->Execute($query, array($id_inscription, $nom));
	if($dbresult && $dbresult->recordCount()>0)
	{
		$row = $dbresult->FetchRow();
		$id_option = $row['id'];
		return $id_option;
	}
	else
	{
		return false;
	}
		
	
}
//les réponses
//ajoute une réponse
function add_reponse($id_inscription, $id_option, $genid)
{
	$db = cmsms()->GetDb();
	$timbre = time();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_belongs (id_inscription,id_option,genid, timbre) VALUES ( ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($id_inscription,$id_option,$genid, $timbre));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//supprime tous les choix d'un adhérent (id_option) à une inscription
function delete_user_choice($id_inscription, $genid)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $genid));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//détermine si un utilisateur a répondu ou non
function has_expressed($id_inscription, $genid)
{
	$db = cmsms()->GetDb();
	$query = "SELECT genid, id_option FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $genid));
	if($dbresult)
	{
		if($dbresult->RecordCount()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
//récupère la (ou les) réponse(s) d'un adhérent
function user_choice($id_inscription,$id_option, $genid)
{
	$db = cmsms()->GetDb();
	$query = "SELECT be.id_option,opt.nom FROM ".cms_db_prefix()."module_inscriptions_belongs AS be, ".cms_db_prefix()."module_inscriptions_options AS opt  WHERE be.id_inscription = opt.id_inscription AND be.id_option = opt.id  AND be.id_inscription = ? AND be.id_option = ? AND be.genid = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $id_option,$genid));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$id_option = $row['id_option'];
			$nom = $row['nom'];
			
		}
		return $nom;
	}
	else
	{
		return false;
	}
}

//renvoie le nb de joueurs dans une option donnée
function count_users_in_option($id_option)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ?";
	$dbresult = $db->Execute($query, array($id_option));
	if($dbresult)
	{
		$row = $dbresult->FetchRow();
		$nb = $row['nb'];
	}
	else
	{
		$nb = 0;
	}
	
	return $nb;
}
//Relances
//collecte les genid des personnes ayant déjà répondues à une inscription
function relance_email_licence($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT genid FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	$liste_licences = array();
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$liste_licences[] = $row['genid'];
		}
		return $liste_licences;
	}
	else
	{
		return FALSE;
	}
}
//liste les options disponibles pour une inscription donnée
function liste_options($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id, nom FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ? AND actif = 1";
	$dbresult = $db ->Execute($query, array($id_inscription));
	if($dbresult)
	{
		if($dbresult->RecordCount()>0)
		{
			$liste_options = array();
			$i=0;
			while($row = $dbresult->FetchRow())
			{
				$i++;
				$liste_options[$i]['id'] = $row['id'];
				$liste_options[$i]['nom'] = $row['nom'];
			}
			return $liste_options;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}
//emails
//envoie un email normal
function send_normal_email($sender, $recipient,$subject, $priority, $lien)
{
		
		$insc_ops = new inscriptions;
	
		$body = $insc_ops->GetTemplate('relanceemail');
		
		$body = $insc_ops->ProcessTemplateFromData($body);
		$cmsmailer = new \cms_mailer();
		$cmsmailer->reset();
	//	$cmsmailer->SetFrom($sender);//$this->GetPreference('admin_email'));
		$cmsmailer->AddAddress($recipient,$name='');
		$cmsmailer->IsHTML(true);
		$cmsmailer->SetPriority($priority);
		
		$cmsmailer->SetBody($body);
		$cmsmailer->SetSubject($subject);
		$cmsmailer->Send();
                if( !$cmsmailer->Send() ) 
		{			
                    	return false;
                }
}
function strtodate($rss_time) {
        $day = substr($rss_time, 5, 2);
        $month = substr($rss_time, 8, 3);
      //  $month = date('m', strtotime("$month 1 2011"));
        $year = substr($rss_time, 12, 4);
       

        $timestamp = mktime($month, $day, $year);

        date_default_timezone_set('UTC');

        return $timestamp;
}
//modifie une date de format YYYY-mm-dd en format unix timestamp
function datetotimestamp($date_limite)
{
	$day = substr($date_limite, 8, 2);
        $month = substr($date_limite, 5, 2);
      //  $month = date('m', strtotime("$month 1 2011"));
        $year = substr($date_limite, 0, 4);
       

        $timestamp = mktime($month, $day, $year);

        date_default_timezone_set('UTC');

        return $timestamp;
}
##
#END OF CLASS
}
