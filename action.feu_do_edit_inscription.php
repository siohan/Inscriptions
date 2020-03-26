<?php
if (!isset($gCms)) exit;

//debug_display($_POST, 'Parameters');

$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$insc_ops = new T2t_inscriptions;	
		
		
		if (isset($_POST['id_inscription']) && $_POST['id_inscription'] !='')
		{
			$id_inscription = $_POST['id_inscription'];
			$details = $insc_ops->details_inscriptions($id_inscription);
			$choix = $details['choix_multi'];
		//	$edit = 1;
						
		}
		else
		{
			$error++;
		}
				
		if (isset($_POST['nom']) && $_POST['nom'] !='')
		{
			$id_option = $_POST['nom'];		
		}
		else
		{
			$error++;
		}
		if (isset($_POST['genid']) && $_POST['genid'] !='')
		{
			$genid = $_POST['genid'];
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
						echo '<h2>Réponse(s) ajoutée(s)</h2><p>Merci d\'avoir répondu !</p>';
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