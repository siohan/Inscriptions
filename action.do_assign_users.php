<?php
if (!isset($gCms)) exit;
//require_once(dirname(__FILE__).'/include/prefs.php');
debug_display($params, 'Parameters');
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
if(isset($params['cancel']) && $params['cancel'] !='')
{
	$this->Redirect($id, 'defaultadmin', $returnid);
}
$insc_ops = new T2t_inscriptions;
$message = '';
$annee = date('Y');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		$id_option = '';
		if (isset($params['id_option']) && $params['id_option'] != '')
		{
			$id_option = $params['id_option'];
		}
		else
		{
			$error++;
		}
		$id_inscription = '';
		if (isset($params['id_inscription']) && $params['id_inscription'] != '')
		{
			$id_inscription = $params['id_inscription'];
			$details = $insc_ops->details_inscriptions($id_inscription);
			$choix_multi = $details['choix_multi'];
		}
		else
		{
			$error++;
		}
	
		if($error ==0)
		{
					
			
				if (isset($params['genid']) && $params['genid'] != '')
				{
					$genid = $params['genid'];
					$del = $insc_ops->delete_users_in_option($id_option);
					foreach($genid as $key=>$value)
					{
						$add_rep = $insc_ops->add_reponse($id_inscription,$id_option,$key);
					}					
				}
				else
				{
					$del_users_option = $insc_ops->delete_users_in_option($id_option);
				}
				
				
			
				
			$this->SetMessage($message);
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		


$this->RedirectToAdminTab('default');

?>