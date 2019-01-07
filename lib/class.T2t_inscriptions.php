<?php
#CMS - CMS Made Simple



class T2t_inscriptions
{
  function __construct() {}

function details_inscriptions($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif, statut, groupe, choix_multi FROM ".cms_db_prefix()."module_inscriptions_inscriptions WHERE id = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
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
			$details['statut'] = $row['statut'];
			$details['groupe'] = $row['groupe'];
			$details['choix_multi'] = $row['choix_multi'];
		}
	}
		return $details;
	

}
//ajoute une inscription
function add_inscription($nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut, $groupe, $choix_multi)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_inscriptions (nom, description, date_debut, date_fin, heure_debut, heure_fin, actif, statut, groupe, choix_multi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut, $groupe, $choix_multi));
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
function edit_inscription($record_id,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut,$groupe, $choix_multi)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_inscriptions SET nom = ?, description = ?, date_debut = ?, date_fin = ?, heure_debut = ?, heure_fin = ?, actif = ?, statut = ?, groupe = ?, choix_multi = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $statut,$groupe, $choix_multi, $record_id));
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
//supprime tous les joueurs d'une inscription !
function delete_users_in_inscription($id_inscription)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ?";
	$dbresult = $db->Execute($query, array($id_inscription));
	
}
//indique si un joueur est inscrit à une manifestation
function is_inscrit($id_inscription, $licence)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id_inscription, licence FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_inscription = ? AND licence = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $licence));
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
function add_option($id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $tarif, $groupe, $categ)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_options (id_inscription,nom, description, date_debut, date_fin, heure_debut, heure_fin, actif,tarif, groupe, categ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$dbresult = $db->Execute($query, array($id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif, $tarif, $groupe, $categ));
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
function edit_option($record_id,$id_inscription,$nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif,$tarif, $groupe, $categ)
{
	$db = cmsms()->GetDb();
	$query = "UPDATE ".cms_db_prefix()."module_inscriptions_options SET id_inscription = ?, nom = ?, description = ?, date_debut = ?, date_fin = ?, heure_debut = ?, heure_fin = ?, actif = ?,tarif = ?, groupe = ?, categ = ? WHERE id = ?";
	$dbresult = $db->Execute($query, array($id_inscription, $nom, $description, $date_debut, $date_fin, $heure_debut, $heure_fin, $actif,$tarif,$groupe,$categ, $record_id));
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
		return $nb;
	}
	else
	{
		return false;
	}
}
//supprime les joueurs d'une option
function delete_users_in_option($id_option)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ?";
	$dbresult = $db->Execute($query, array($id_option));
	
}
//indique si un joueur est inscrit à une option particulière d'une manifestation
function is_inscrit_opt($id_option, $licence)
{
	$db = cmsms()->GetDb();
	$query = "SELECT id_option, licence FROM ".cms_db_prefix()."module_inscriptions_belongs WHERE id_option = ? AND licence = ?";
	$dbresult = $db->Execute($query, array($id_option, $licence));
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
//LES CATEGORIES
function delete_options_categ($record_id)
{
	$db = cmsms()->GetDb();
	$query = "DELETE FROM ".cms_db_prefix()."module_inscriptions_options_categ WHERE id_option = ?";
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
//ajoute une catégorie pour une cotisation
function add_options_categ($id_option, $categ)
{
	$db = cmsms()->GetDb();
	$query = "INSERT INTO ".cms_db_prefix()."module_inscriptions_options_categ (id_option, categ) VALUES (?, ?)";
	$dbresult = $db->execute($query, array($id_option, $categ));
	if(!$dbresult)
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
//sélectionne les catégories pour une option précise
function categ_per_option($id_option)
{
	$db = cmsms()->GetDb();
	$query = "SELECT categ FROM ".cms_db_prefix()."module_inscriptions_options_categ WHERE id_option = ?";
	$dbresult = $db->Execute($query, array($id_option));
	if($dbresult)
	{
		if($dbresult->recordCount()>0)
		{
			while($row = $dbresult->fetchRow())
			{
				$tab[] = "'".$row['categ']."'";
			}
		//	var_dump($tab);
			return $tab;
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
