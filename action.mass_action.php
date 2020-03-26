<?php
//ce fichier fait des actions de masse, il est appelé depuis l'onglet de récupération des infos sur les joueurs
if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Inscription use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$insc_ops = new T2t_inscriptions;
debug_display($params, 'Parameters');
var_dump($params['sel']);
$db =& $this->GetDb();
if (isset($params['submit_massaction']) && isset($params['actiondemasse']) )
  {
     if( isset($params['sel']) && is_array($params['sel']) &&
	count($params['sel']) > 0 )
      	{
        	
		switch($params['actiondemasse'])
		{
			case "activate_inscription" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->activate_desactivate_inscription($record_id,$action='1');
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
			
			case "desactivate_inscription" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->activate_desactivate_inscription($record_id,$action='0');
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
			
			case "delete_inscription" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->delete_inscription($record_id);
					if(true == $ac)
					{
						//on supprime aussi les options et les adhésions 
						$del_options = $insc_ops->delete_options($record_id);
						$del_belongs = $insc_ops->delete_inscription_belongs($record_id);
					}
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
			
			//les options :
			case "activate_option" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->activate_desactivate_option($record_id,$action='1');
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
			
			case "desactivate_option" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->activate_desactivate_option($record_id,$action='0');
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
			//supprime toutes les options d'une inscriptions et les adhésions  avec
			case "delete_option" :			
	  		{
	    			foreach( $params['sel'] as $record_id)
				{
					$ac = $insc_ops->delete_options($record_id);
					if(true == $ac)
					{
						//on supprime aussi les adhésions 						
						$del_belongs = $insc_ops->delete_users_in_option($record_id);
					}
				}
				$this->RedirectToAdminTab('insc');
	  		}			
			break;
	
      		}//fin du switch
  	}
	else
	{
		$this->SetMessage('Erreur ! Pas de sélection active !');
		$this->RedirectToAdminTab('insc');
	}
}
/**/
#
# EOF
#
?>