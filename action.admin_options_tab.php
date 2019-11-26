<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($_POST, 'Parameters');
if( !empty($_POST) ) {
        if( isset($_POST['cancel']) ) {
            $this->RedirectToAdminTab();
        }
	//on sauvegarde ! Ben ouais !
	//on construit l'intervalle de temps
	$result = $_POST['result'];
	$unite = $_POST['unite'];
	if($unite == 'Heures')
	{
		$coeff = 3600;
	}
	else
	{
		$coeff = 3600*24;
	}
	$dupli = $coeff*$result;
	$this->SetPreference('pageid_inscriptions', $_POST['pageid_inscriptions']);
//	$this->SetPreference('interval', $_POST['interval']);
	$this->SetPreference('duplication_time', $dupli);

	
	//on redirige !
	$this->RedirectToAdminTab('config');
}
else
{
	//on recalcule la duplication pour le formulaire
	$duplication_time = (int) $this->GetPreference('duplication_time');
	//var_dump($duplication_time);
	
	$liste_unite = array('Heures'=>'Heures', 'Jours'=>'Jours');
	if(true == is_float($duplication_time/86400))
	{
		//on met le résultat en heures
		$result = $duplication_time/3600;
		$unite = 'Heures';
		
	}
	else
	{
		//on met le résultat en jours
		$result = $duplication_time/86400;
		$unite = 'Jours';
		
	}
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('config.tpl'), null, null, $smarty);
	$tpl->assign('interval', $this->GetPreference('interval'));
	$tpl->assign('liste_unite', $liste_unite);
	$tpl->assign('result', $result);
	$tpl->assign('unite', $unite);
	$tpl->assign('pageid_inscriptions', $this->GetPreference('pageid_inscriptions'));
	$tpl->assign('duplication_time', $this->GetPreference('duplication_time'));
	$tpl->display();
		
}
#
# EOF
#
?>