<?php
#-------------------------------------------------------------------------
# Module: Inscriptions
# Version: 0.5, Claude SIOHAN
# Method: Install
#-------------------------------------------------------------------------


/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
 */ 
if (!isset($gCms)) exit;


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */

$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	nom C(255),
	description X,
	date_limite I(11),
	date_debut I(11),
	date_fin I(11),
	actif I(1) DEFAULT 0,
	groupe I(3) DEFAULT 0,
	choix_multi I(1),
	group_notif I(3),
	timbre I(11)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_inscriptions", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	id_inscription I(11),
	nom C(255),
	description X,
	date_debut I(11),
	date_fin I(11),
	actif I(1),
	tarif F,
	groupe I(1) DEFAULT 0,
	timbre I(11)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_options", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//


// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	id_inscription I(11),
	id_option I(11),
	genid I(11),
	timbre I(11)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_belongs", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

//Permissions
$this->CreatePermission('Inscriptions use', 'Utiliser le module Inscriptions');

//mails templates
# Mails templates
$fn = cms_join_path(dirname(__FILE__),'templates','orig_relanceemailtemplate.tpl');
if( file_exists( $fn ) )
{
	$template = file_get_contents( $fn );
	$this->SetTemplate('relanceemail',$template);
}

$fn = cms_join_path(dirname(__FILE__),'templates','orig_send_email.tpl');
if( file_exists( $fn ) )
{
	$template = file_get_contents( $fn );
	$this->SetTemplate('send_email',$template);
}

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
$this->SetPreference('pageid_inscriptions', '');
$this->SetPreference('LastSendNotification', time());

// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>