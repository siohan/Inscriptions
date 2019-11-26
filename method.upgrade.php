<?php
#-------------------------------------------------------------------------
# Module: Inscriptions
# Version: 0.2
# Method: Upgrade
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------

/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
*/ 
if (!isset($gCms)) exit;

$db = $this->GetDb();			/* @var $db ADOConnection */
$dict = NewDataDictionary($db); 	/* @var $dict ADODB_DataDict */
/**
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Upgrade() method in the module body.
 */
$now = trim($db->DBTimeStamp(time()), "'");
$current_version = $oldversion;
switch($current_version)
{
  // we are now 1.0 and want to upgrade to latest
 
	
	case "0.1" : 	
	
	{
		//on créé un nouveau champ genid I(11) pour la table inscriptions_belongs
		$dict = NewDataDictionary( $db );
		$flds = "genid I(11)";
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_inscriptions_belongs", $flds);
		$dict->ExecuteSQLArray($sqlarray);
		
		//on remplace les clients par le genid
		$query = "SELECT adh.genid, cont.licence FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_inscriptions_belongs AS cont WHERE adh.licence = cont.licence";
		$dbresult = $db->Execute($query);
		if($dbresult)
		{
			while($row = $dbresult->FetchRow())
			{
				$genid = $row['genid'];
				$query2 = "UPDATE ".cms_db_prefix()."module_inscriptions_belongs SET genid = ? WHERE licence = ?";
				$dbresult2 = $db->Execute($query2, array($genid, $row['licence']));
			}
		}
	}
	case "0.2":
	{
	
		//suppression de la table categ
		//Ds la table inscription, le chp groupe devient integer 2 (au lieu de 1) et on ajoute un chp date_limite
		
		$dict = NewDataDictionary( $db );
		//on supprime une table inutile
		$sqlarray = $dict->DropTableSQL( cms_db_prefix()."module_inscriptions_options_categ");
		$dict->ExecuteSQLArray($sqlarray);
		
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_inscriptions_inscriptions", "date_limite D");
		$dict->ExecuteSQLArray( $sqlarray );
		
		$sqlarray = $dict->AlterColumnSQL(cms_db_prefix()."module_inscriptions_inscriptions",
						     "groupe I(3) ");
	 	$dict->ExecuteSQLArray( $sqlarray );
		
		$dict = NewDataDictionary( $db );
		$sqlarray = $dict->AddColumnSQL(cms_db_prefix()."module_inscriptions_belongs", "timbre I(11)");
		$dict->ExecuteSQLArray( $sqlarray );
		
		$query = "UPDATE ".cms_db_prefix()."module_inscriptions_belongs set timbre = UNIX_TIMESTAMP()";
		$db->Execute($query);
		
		
		
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_relanceemailtemplate.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('relanceemail',$template);
		}
		/*
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_send_email.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('send_email',$template);
		}
		*/
		# Les index
		//on créé un index sur la table
		$idxoptarray = array('UNIQUE');
		$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'choix_multi',
				    		cms_db_prefix().'module_inscriptions_belongs', 'id_option,genid',$idxoptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
		# Les préférences 
		$this->SetPreference('admin_email', 'root@localhost.com');		
		$this->SetPreference('last_updated', time());
		$this->SetPreference('default_group', 0);
	}
	case "0.3" :
	{
		$dict = NewDataDictionary( $db );
		//on supprime une colonne inutile
		$sqlarray = $dict->DropColumnSQL( cms_db_prefix()."module_inscriptions_inscriptions", "statut");
		$dict->ExecuteSQLArray($sqlarray);
		
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_inscriptions_inscriptions", "timbre I(11)");
		$dict->ExecuteSQLArray($sqlarray);
		
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_inscriptions_options", "timbre I(11)");
		$dict->ExecuteSQLArray($sqlarray);
		
		//changement de types de données on passe en int(11)
		$sqlarray = $dict->AlterColumnSQL( cms_db_prefix()."module_inscriptions_options", "tarif F");
		$dict->ExecuteSQLArray($sqlarray);
		
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_inscriptions_belongs", "timbre I(11)");
		$dict->ExecuteSQLArray($sqlarray);
		
		//changement de types de données on passe en int(11)
		$sqlarray = $dict->AlterColumnSQL( cms_db_prefix()."module_inscriptions_inscriptions", "date_limite I(11), date_debut I(11), date_fin I(11)");
		$dict->ExecuteSQLArray($sqlarray);
		
		//changement de types de données on passe en int(11)
		$sqlarray = $dict->AlterColumnSQL( cms_db_prefix()."module_inscriptions_options", "date_debut I(11), date_fin I(11)");
		$dict->ExecuteSQLArray($sqlarray);
		
		$sqlarray = $dict->DropColumnSQL( cms_db_prefix()."module_inscriptions_options", "heure_debut, heure_fin");
		$dict->ExecuteSQLArray($sqlarray);
		
		$this->SetPreference('pageid_inscriptions', '');
	}
	
	case "0.4" :
	{
		
		$fn = cms_join_path(dirname(__FILE__),'templates','orig_send_email.tpl');
		if( file_exists( $fn ) )
		{
			$template = file_get_contents( $fn );
			$this->SetTemplate('send_email',$template);
		}
		
		
	
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_inscriptions_inscriptions", "group_notif I(3)");
		$dict->ExecuteSQLArray($sqlarray);
		
	}

}
// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>