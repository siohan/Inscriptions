<?php

if( !isset($gCms) ) exit;


//debug_display($params, 'Parameters');
$db =& $this->GetDb();
global $themeObject;
$insc_ops = new T2t_inscriptions;
if(isset($params['id_inscription']) && $params['id_inscription'] != '')
{
	$id_inscription = $params['id_inscription'];
	//on va chercher les détails de l'inscription
	$details_insc = $insc_ops->details_inscriptions($id_inscription);
	$smarty->assign('id_inscription', $this->CreateInputHidden($id, 'id_inscription', $id_inscription));
}

if(isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];

	
	$details_options = $insc_ops->details_option($record_id);
	$smarty->assign('record_id',
			$this->CreateInputHidden($id, 'record_id', $record_id));
}
$smarty->assign('formstart',
		$this->createFormStart($id, 'do_add_edit_option', $returnid));
$liste_values = array("Oui"=>"1", "Non"=>"0");
	$smarty->assign('nom',
			$this->CreateInputText($id, 'nom',(isset($details_options['nom'])?$details_options['nom']:""),50, 350));
	$smarty->assign('description',
			$this->CreateInputText($id, 'description',(isset($details_options['description'])?$details_options['description']:""),50, 350));	
	
	$smarty->assign('date_debut',
			$this->CreateInputDate($id, 'date_debut',(isset($details_options['date_debut'])?$details_options['date_debut']:$details_insc['date_debut']),50, 350));
	$smarty->assign('date_fin',
			$this->CreateInputDate($id, 'date_fin',(isset($details_options['date_fin'])?$details_options['date_fin']:$details_insc['date_fin']),50, 350));
	$smarty->assign('heure_debut',
			$this->CreateInputText($id, 'heure_debut',(isset($details_options['heure_debut'])?$details_options['heure_debut']:$details_insc['heure_debut']),15, 35));
	$smarty->assign('heure_fin',
			$this->CreateInputText($id, 'heure_fin',(isset($details_options['heure_fin'])?$details_options['heure_fin']:$details_insc['heure_fin']),15, 35));
	$smarty->assign('actif',
			$this->CreateInputDropdown($id, 'actif',$liste_values));
	$smarty->assign('tarif',
			$this->CreateInputText($id, 'tarif',(isset($details_options['tarif'])?$details_options['tarif']:""),50, 350));
	
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
								$this->Lang('cancel')));
$smarty->assign('formend', $this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_option.tpl');

#
# EOF
#
?>