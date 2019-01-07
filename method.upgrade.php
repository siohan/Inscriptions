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
	

}
// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>