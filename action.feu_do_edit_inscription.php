<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$insc_ops = new T2t_inscriptions;	
		
		
		if (isset($params['id_inscription']) && $params['id_inscription'] !='')
		{
			$id_inscription = $params['id_inscription'];
			$details = $insc_ops->details_inscriptions($id_inscription);
			$choix = $details['choix_multi'];
		//	$edit = 1;
						
		}
		else
		{
			$error++;
		}
				
		if (isset($params['nom']) && $params['nom'] !='')
		{
			/*
			if(true === is_array($params['nom']))
			{
				
			}
			else
			{
				$id_option = $params['nom'];
			}
			*/
			$id_option = $params['nom'];
		
		}
		else
		{
			$error++;
		}
		if (isset($params['genid']) && $params['genid'] !='')
		{
			$genid = $params['genid'];
		}
	
		if($error < 1)
		{
				$del_rep = $insc_ops->delete_user_choice($id_inscription, $genid);
				
				
					if(true === is_array($id_option) && count($id_option)>0)
					{
						foreach($id_option as $key)
						{
							$add = $insc_ops->add_reponse($id_inscription, $key, $genid);
						}
					}
					else
					{
						$add = $insc_ops->add_reponse($id_inscription, $id_option, $genid);
					}
					if(true === $add)
					{
						echo '<h2>Inscription ajoutée</h2><p>Ben c\'était pas si compliqué !</p>';
					}
					else
					{
						echo 'Inscription non ajoutée !!';
					}
				
		
		}
		else
		{
			$this->SetMessage('Parametre(s) manquant(s)');
		}
			


?>