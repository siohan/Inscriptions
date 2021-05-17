<?php
#CMS - CMS Made Simple



class T2t_inscriptions
{
  function __construct() {}

function details_inscriptions($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,nom, description, date_limite, date_debut, date_fin, actif, groupe, choix_multi,group_notif, timbre, occurence,ext, start_collect, collect_mode, end_collect, tag, partners FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE id = ?";
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
			$details['actif'] = $row['actif'];
			$details['groupe'] = $row['groupe'];
			$details['group_notif'] = $row['group_notif'];
			$details['choix_multi'] = $row['choix_multi'];
			$details['timbre'] = $row['timbre'];
			$details['occurence'] = $row['occurence'];
			$details['ext'] = $row['ext'];
			$details['start_collect'] = $row['start_collect'];
			$details['collect_mode'] = $row['collect_mode'];
			$details['end_collect'] = $row['end_collect'];
			$details['tag'] = $row['tag'];
			$details['partners'] = $row['partners'];
		}
	}
		return $details;
	

}
//ajoute une inscription
function add_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi,$timbre, $occurence,$ext, $start_collect, $collect_mode, $end_collect, $partners)
{
	$db = cmsms()->GetDb();

	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_inscriptions (nom, description, date_limite, date_debut, date_fin, actif, groupe,group_notif, choix_multi, timbre, occurence, ext, start_collect, collect_mode, end_collect, partners) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi, $timbre, $occurence, $ext, $start_collect, $collect_mode, $end_collect,  $partners));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return $db->ErrorMsg();
	}
}
//duplique une inscription et renvoie le last_insert
function duplicate_inscription($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe, $group_notif, $choix_multi, $timbre,  $occurence, $ext, $start_collect, $collect_mode, $end_collect, $tag, $partners)
{
	$db = cmsms()->GetDb();

	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_inscriptions (nom, description, date_limite, date_debut, date_fin, actif, groupe, group_notif, choix_multi, timbre, occurence, ext,start_collect, collect_mode, end_collect, tag, partners) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi, $timbre, $occurence, $ext,$start_collect, $collect_mode, $end_collect, $tag, $partners));
	if($dbresult)
	{
		return $db->insert_Id();
	}
	else
	{
		return false;
	}
}
##
function edit_inscription($record_id,$nom, $description,$date_limite, $date_debut, $date_fin, $actif,$groupe,$group_notif, $choix_multi, $timbre,$occurence, $ext, $start_collect, $collect_mode, $end_collect, $partners)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET nom = ?, description = ?, date_limite = ?, date_debut = ?, date_fin = ?, actif = ?, groupe = ?, group_notif = ?, choix_multi = ?, timbre = ?, occurence = ?, ext = ?, start_collect = ?, collect_mode = ?, end_collect = ?, partners = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($nom, $description, $date_limite, $date_debut, $date_fin, $actif, $groupe,$group_notif, $choix_multi, $timbre, $occurence, $ext, $start_collect,$collect_mode,$end_collect, $partners, $record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//met à jour le timbre pour les tâches automatiques
function update_timbre($id_inscription, $timbre)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET timbre = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($timbre, $id_inscription));
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
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//renvoie le nb de personnes total dans une inscription donnée
function count_users_in_inscription($id_inscription)
{
	global $gCms;
	$db = cmsms()->GetDb();
	$query = "SELECT count(DISTINCT genid) AS nb FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";//" GROUP BY genid";
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
//supprime un seul adhérent d'une inscription !
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
//supprime tous les adhérents d'une inscription !
function delete_all_responses($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
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
	$query = "SELECT id,id_inscription,nom, description, date_debut, date_fin, actif, tarif, groupe, timbre, jauge FROM ".cms_db_prefix()."module_inscriptions_options WHERE id = ?";
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
			$details['actif'] = $row['actif'];
			$details['tarif'] = $row['tarif'];
			$details['groupe'] = $row['groupe'];
			$details['timbre'] = $row['timbre'];
			$details['jauge'] = $row['jauge'];
		}
	}
		return $details;
	


}

//ajoute une option à une inscription
function add_option($id_inscription,$nom, $description, $date_debut, $date_fin, $actif, $tarif, $timbre, $jauge)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_options (id_inscription,nom, description, date_debut, date_fin, actif,tarif,timbre, jauge) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($id_inscription,$nom, $description, $date_debut, $date_fin, $actif, $tarif, $timbre, $jauge));
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
function edit_option($record_id,$id_inscription,$nom, $description, $date_debut, $date_fin, $actif,$tarif, $jauge)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_options SET id_inscription = ?, nom = ?, description = ?, date_debut = ?, date_fin = ?, actif = ?,tarif = ?, jauge = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $nom, $description, $date_debut, $date_fin, $actif,$tarif, $jauge, $record_id));
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
//Active ou désactive une option dans une inscription
function activate_desactivate_option($record_id,$action)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_options SET actif = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($action, $record_id));
	if($dbresult)
	{
		return true;
	}
	else
	{
		return false;
	}
}
//compte le nb d'options actives d'une inscription
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
//pour dupliquer toutes les options d'une inscription (même inactives) 
function duplicate_options($id_inscription, $last_insert,$duplication_time)
{
	//on récupère d'abord les options de l'inscription qu'on copie
	$db = cmsms()->GetDb();
	$query = "SELECT nom, description, date_debut, date_fin, actif, tarif, timbre, jauge FROM ".cms_db_prefix()."module_inscriptions_options WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	$timbre = time();
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$date_debut = $row['date_debut'] + $duplication_time;
			$date_fin = $row['date_fin'] + $duplication_time;
			$add_opts = $this->add_option($last_insert,$row['nom'], $row['description'], $date_debut, $date_fin, $row['actif'], $row['tarif'], $timbre, $row['jauge']);
		}
	}
	
}
//pour dupliquer une seule option ! 
function duplicate_option($record_id,$duplication_time)
{
	//on récupère d'abord les options de l'inscription qu'on copie
	$db = cmsms()->GetDb();
//	$duplication_time = $this->GetPreference('duplication_time');
	$query = "SELECT id_inscription,nom, description, date_debut, date_fin, actif, tarif, timbre, jauge FROM ".cms_db_prefix()."module_inscriptions_options WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$date_debut = $row['date_debut'] + $duplication_time;
			$date_fin = $row['date_fin'] + $duplication_time;
			$add_opts = $this->add_option($row['id_inscription'],$row['nom'], $row['description'], $date_debut, $date_fin, $row['actif'], $row['tarif'], $row['timbre'], $row['jauge']);
		}
		return true;
	}
	else
	{
		return false;
	}
	
}
//les réponses
//ajoute le choix d'une option pour un utilisateur
function add_reponse($id_inscription, $id_option, $genid, $referent)
{
	$db = cmsms()->GetDb();
	$timbre = time();
	$checked = 1;
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_belongs (id_inscription,id_option,genid,referent,checked, timbre) VALUES (?,?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($id_inscription,$id_option,$genid,$referent,$checked, $timbre));
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

//renvoie le nb de réponses dans une option donnée
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
//indique que les options de cet utilisateur sont validées selement dans une inscription donnée
function set_to_checked($genid, $id_id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_belongs SET checked = 1 WHERE genid = ? AND id_inscription = ?";
	$dbresult = $db->Execute($query, array($genid,$id_inscription));
}
////collecte tous les genid des personnes ayant déjà répondues à une inscription
function already_subscribed($id_inscription)
{
	$db = cmsms()->GetDb();
	$ass = new Asso_adherents;
	$query = "SELECT genid FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	$liste_licences = array();
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$nom = $ass->get_name($row['genid']);
			$liste_licences[$row['genid']] = $nom;
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

	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('relanceemail.tpl'), null, null, $smarty);
		$tpl->assign('lien', $lien);
		
		
		$cmsmailer = new \cms_mailer();
		$cmsmailer->reset();
	//	$cmsmailer->SetFrom($sender);//$this->GetPreference('admin_email'));
		$cmsmailer->AddAddress($recipient);
		$cmsmailer->IsHTML(true);
		$cmsmailer->SetPriority($priority);
	
		$cmsmailer->SetBody($tpl);
		$cmsmailer->SetSubject($subject);
		
                if( !$cmsmailer->Send() ) 
		{			
                    	return false;
                }
		
}
function sofar()
{
	$mktime = function( array $in, string $prefix, $is_start = false ) {
                $mo = (int) $in[$prefix.'Month'];
                $dd = (int) $in[$prefix.'Day'];
                $yr = (int) $in[$prefix.'Year'];
                $hh = (int) $in[$prefix.'Hour'];
                $mm = (int) $in[$prefix.'Minute'];
                $ss = $is_start ? 00 : 59;

                // if the date is before jan-1-1970 ... that's no date.
                if( $yr <= 1970 && $mo == 1 && $dd == 1 ) return;
                return mktime( $hh, $mm, $ss, $mo, $dd, $yr );
            };
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
//convertit une date de la forme Y-m-d H:i vers un int temps unix
function datetotimeunix($senddate, $sendtime)
{
	$day = (int) substr($senddate, 8,2);
	$month = (int) substr($senddate, 5,2);
	$year = (int) substr($senddate, 0,4);
	$hour = (int) substr($sendtime, 0,2);
	$min = (int) substr($sendtime, 3,2);
	$seconds = 0;
	$intdate = mktime($hour,$min, $seconds, $month, $day, $year);
	return $intdate;
}
//créé un tag pour les inscriptions externes
function create_tag($record_id)
{
	$db = cmsms()->GetDb();
	
}
//détermine si un utilisateur a répondu ou non
function is_checked($id_option, $genid)
{
	$db = cmsms()->GetDb();
	$query = "SELECT checked FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND genid = ?";
	$dbresult = $db->Execute($query, array($id_option, $genid));
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
##
#END OF CLASS
}
