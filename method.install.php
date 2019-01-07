<?php
#-------------------------------------------------------------------------
# Module: Inscriptions
# Version: 0.2, Claude SIOHAN
# Method: Install
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
	description C(255),
	date_debut D,
	date_fin D,
	heure_debut T,
	heure_fin T,
	actif I(1) DEFAULT 0,
	statut I(1) DEFAULT 0,
	groupe I(1) DEFAULT 0,
	choix_multi I(1)";
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
	description C(255),
	date_debut D,
	date_fin D,
	heure_debut T,
	heure_fin T,
	actif I(1),
	tarif N(6,2),
	groupe I(1) DEFAULT 0,
	categ I(1) DEFAULT 0";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_options", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	id_option I(11),
	categ C(255)	";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_options_categ", $flds, $taboptarray);
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
	genid I(11)";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_inscriptions_belongs", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );

//Permissions
$this->CreatePermission('Inscriptions use', 'Utiliser le module Inscriptions');
//$this->CreatePermission('Adherents prefs', 'Modifier les données du compte');


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>