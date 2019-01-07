<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
debug_display($params, 'Parameters');
if (!$this->CheckPermission('Inscriptions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

$rowarray = array();

	
	if(!isset($params['record_id']) || $params['record_id'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('cotis');
	}
	else
	{
		$record_id = $params['record_id'];
	}

	


		$smarty->assign('formstart',
				$this->CreateFormStart( $id, 'do_add_options_categ', $returnid ) );
		$smarty->assign('record_id',
				$this->CreateInputText($id,'record_id',$record_id,10,15));	
		
		$smarty->assign('VM', $this->CreateInputCheckbox($id, 'VM','1',(isset($VM)?"1":"0")));
		$smarty->assign('VF', $this->CreateInputCheckbox($id, 'VF','1',(isset($VF)?"1":"0")));
		$smarty->assign('SM', $this->CreateInputCheckbox($id, 'SM','1',(isset($SM)?"1":"0")));
		$smarty->assign('SF', $this->CreateInputCheckbox($id, 'SF','1',(isset($SF)?"1":"0")));
		$smarty->assign('JM', $this->CreateInputCheckbox($id, 'JM','1',(isset($JM)?"1":"0")));
		$smarty->assign('JF', $this->CreateInputCheckbox($id, 'JF','1',(isset($JF)?"1":"0")));
		$smarty->assign('CM', $this->CreateInputCheckbox($id, 'CM','1',(isset($CM)?"1":"0")));
		$smarty->assign('CF', $this->CreateInputCheckbox($id, 'CF','1',(isset($CF)?"1":"0")));
		$smarty->assign('MM', $this->CreateInputCheckbox($id, 'MM','1',(isset($MM)?"1":"0")));
		$smarty->assign('MF', $this->CreateInputCheckbox($id, 'MF','1',(isset($MF)?"1":"0")));
		$smarty->assign('BM', $this->CreateInputCheckbox($id, 'BM','1',(isset($BM)?"1":"0")));
		$smarty->assign('BF', $this->CreateInputCheckbox($id, 'BF','1',(isset($BF)?"1":"0")));
		$smarty->assign('PM', $this->CreateInputCheckbox($id, 'PM','1',(isset($PM)?"1":"0")));
		$smarty->assign('PF', $this->CreateInputCheckbox($id, 'PF','1',(isset($PF)?"1":"0")));
	
		
		

	
		$smarty->assign('submit',
				$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
		$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
							$this->Lang('cancel')));
		$smarty->assign('back',
				$this->CreateInputSubmit($id,'back',
							$this->Lang('back')));

		$smarty->assign('formend',
				$this->CreateFormEnd());
	echo $this->ProcessTemplate('add_options_categ.tpl');


#
#EOF
#
?>