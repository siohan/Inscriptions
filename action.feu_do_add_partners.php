<?php
if (!isset($gCms)) exit;

//debug_display($_POST, 'Parameters');

$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$insc_ops = new T2t_inscriptions;	
$gp_ops = new groups;		
		
		if (isset($_POST['partners']) && $_POST['partners'] !='')
		{
			$partners = $_POST['partners'];
		}
		else
		{
			$error++;
		}
		if (isset($_POST['id_option']) && $_POST['id_option'] !='')
		{
			$id_option = $_POST['id_option'];
		}
		else
		{
			$error++;
		}
		if (isset($_POST['id_inscription']) && $_POST['id_inscription'] !='')
		{
			$id_inscription = $_POST['id_inscription'];
			$details = $insc_ops->details_inscriptions($id_inscription);
			$choix = $details['choix_multi'];
			$partners = $details['partners'];
			$id_group = $details['groupe'];
			$liste_genids = $gp_ops->liste_nom_genid_from_group($id_group);
		//	$edit = 1;
						
		}
		else
		{
			$error++;
		}
				
		if (isset($_POST['nom']) && $_POST['nom'] !='')
		{
			$nom = $_POST['nom'];		
		}
		else
		{
			$error++;
		}
		
		if (isset($_POST['genid']) && $_POST['genid'] !='')
		{
			$genid = $_POST['genid'];
		}
		else
		{
			$error++;
		}		
		

		if($error < 1)
		{
				//$del_rep = $insc_ops->delete_user_choice($id_inscription, $genid);
				
				
					if(true === is_array($nom) && count($nom)>0)
					{
						foreach($nom as $key =>$value)
						{
							$add = $insc_ops->add_reponse($id_inscription, $id_option,$value, $genid);
						}
					}
					else
					{
						$add = $insc_ops->add_reponse($id_inscription, $id_option, $nom, $genid);
					}
					
					if(true === $add )
					{
						//$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_feu_inscription.tpl'), null, null, $smarty);
						//$tpl->assign('final_msg', $final_msg);	
						echo 'Partenaire(s) ajouté(s) !!';
		
					}
					else
					{
						echo 'Partenaire(s) non ajouté(s) !!';
					}
					
		
		}
		else
		{
			$this->SetMessage('Parametre(s) manquant(s)');
		}
/**/			


?>