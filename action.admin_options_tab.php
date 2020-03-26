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
	
	$result1 = $_POST['result1'];
	$unite1 = $_POST['unite1'];
	if($unite1 == 'Heures')
	{
		$coeff1 = 3600;
	}
	else
	{
		$coeff1 = 60;
	}
	$dupli1 = $coeff1*$result1;
	$this->SetPreference('pageid_inscriptions', $_POST['pageid_inscriptions']);
	$this->SetPreference('Interval', $dupli1);
	$this->SetPreference('duplication_time', $dupli);
	$this->SetPreference('collect_mode', $_POST['collect_mode']);

	
	//on redirige !
	$this->RedirectToAdminTab('config');
}
else
{
	//on recalcule la duplication pour le formulaire
	$duplication_time = (int) $this->GetPreference('duplication_time');
	
	$collect_time = (int) $this->GetPreference('Interval');
	//var_dump($duplication_time);
	$collect_mode = (int) $this->GetPreference('collect_mode');
	$liste_unite = array('Heures'=>'Heures', 'Jours'=>'Jours');
	$liste_unite1 = array('Heures'=>'Heures', 'Minutes'=>'Minutes');
	
	
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
	
	if(true == is_float($collect_time/3600))
	{
		//on met le résultat en heures
		$result1 = $collect_time/60;
		$unite1 = 'Minutes';
		
	}
	else
	{
		//on met le résultat en jours
		$result1 = $collect_time/86400;
		$unite1 = 'Jours';
		
	}
	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('config.tpl'), null, null, $smarty);
	$tpl->assign('Interval', $this->GetPreference('Interval'));
	$tpl->assign('liste_unite', $liste_unite);
	$tpl->assign('liste_unite1', $liste_unite1);
	$tpl->assign('result', $result);
	$tpl->assign('unite', $unite);
	$tpl->assign('result1', $result1);
	$tpl->assign('unite1', $unite1);
	$tpl->assign('collect_mode', $collect_mode);
	$tpl->assign('pageid_inscriptions', $this->GetPreference('pageid_inscriptions'));
	$tpl->assign('duplication_time', $this->GetPreference('duplication_time'));
	$tpl->display();
		
}
#
# EOF
#
?>