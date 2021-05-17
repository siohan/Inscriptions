<?php

#-------------------------------------------------------------------------
# Module : Inscriptions - 
# Version : 0.7, Sc
# Auteur : AssoSimple
#-------------------------------------------------------------------------
/**
 *
 * @author AssoSimple 
 * @since 0.1
 * @version $Revision: 1 $
 * @modifiedby $LastChangedBy: Claude
 * @lastmodified $Date: 2017-03-26 11:56:16 +0200 (Mon, 28 Juil 2015) $
 * @license GPL
 **/

class Inscriptions extends CMSModule
{
  
  function GetName() { return 'Inscriptions'; }   
  function GetFriendlyName() { return $this->Lang('friendlyname'); }   
  function GetVersion() { return '0.7'; }  
  function GetHelp() { return $this->Lang('help'); }   
  function GetAuthor() { return 'AssoSimple'; } 
  function GetAuthorEmail() { return 'contact@asso-simple.fr'; }
  function GetChangeLog() { return $this->Lang('changelog'); }
    
  function IsPluginModule() { return true; }
  function HasAdmin() { return true; }   
  function GetAdminSection() { return 'content'; }
  function GetAdminDescription() { return $this->Lang('moddescription'); }
 
  function VisibleToAdminUser()
  {
    	return 
		$this->CheckPermission('Inscriptions use');
	
  }
  
  
  function GetDependencies()
  {
	return array('Adherents'=>'0.4');
  }

  

  function MinimumCMSVersion()
  {
    return "2.0";
  }

  
  function SetParameters()
  { 
  	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();
	$this->SetParameterType('display',CLEAN_STRING);
	$this->SetParameterType('action',CLEAN_STRING);
	$this->SetParameterType('record_id', CLEAN_INT);
	$this->SetParameterType('genid', CLEAN_INT);
	$this->SetParameterType('id_inscription', CLEAN_INT);
	$this->SetParameterType('nom', CLEAN_NONE);
	$this->SetParameterType('id_option', CLEAN_INT);
	$this->SetParameterType('obj', CLEAN_NONE);
	$this->SetParameterType('choix_multi', CLEAN_INT);
	$this->SetParameterType('groupe', CLEAN_INT);
	$this->SetParameterType('recap', CLEAN_INT);
	$this->SetParameterType('user_name',CLEAN_STRING);
	$this->SetParameterType('user_forename',CLEAN_STRING);
	
	//form parameters
	$this->SetParameterType('submit',CLEAN_STRING);
	//$this->SetParameterType('tourlist',CLEAN_INT);
	

}

function InitializeAdmin()
{
  	return parent::InitializeAdmin();
	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
}

public function HasCapability($capability, $params = array())
{
   if( $capability == 'tasks' ) return TRUE;
   return FALSE;
}

public function get_tasks()
{
   $obj = array();
	$obj[0] = new reponsesTask();
   	$obj[1] = new RelanceInscriptionsTask();  
	
return $obj; 
}

  function GetEventDescription ( $eventname )
  {
    return $this->Lang('event_info_'.$eventname );
  }
     
  function GetEventHelp ( $eventname )
  {
    return $this->Lang('event_help_'.$eventname );
  }

  function InstallPostMessage() { return $this->Lang('postinstall'); }
  function UninstallPostMessage() { return $this->Lang('postuninstall'); }
  function UninstallPreMessage() { return $this->Lang('really_uninstall'); }
  function random_string($car) {
	$string = "";
	$chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
  }

  
  function _SetStatus($oid, $status) {
    //...
  }




} //end class
?>
