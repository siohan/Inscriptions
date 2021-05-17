<div class="table-responsive">
<p class="alert alert-primary" role="alert">Si vous avez déjà été enregistré, inutile de repréciser votre email et votre numéro de téléphone</p>
						{form_start action='feu_do_edit_inscription'}
						<input type="hidden" name="compteur" value="{$compteur}">
						<input type="hidden" name="choix_multi" value="{$choix_multi}"></span>
						
						<input type="hidden" name="id_inscription" value="{$id_inscription}">
						
						<input type="hidden" name="genid" value="{$genid}">
						<input type="hidden" name="ext" value="{$ext}">
						
					
						<div class="pageoverflow">
						  <h4>{$titre}</h4>
						  <p>{$description}</p>
						<div class="form-group">
							<label for="user_name">Nom</label><input class="form-control" type="text" name="user_name" value="{$user_name}" required>   </div>
						<div class="form-group">	<label for="user_forename">Prénom</label><input class="form-control" type="text" name="user_forename" value="{$user_forename}" required>
						</div>
						<div class="form-group">
							<label for="user_name">Email</label><input class="form-control" type="text" name="user_email" value="{$user_email}" required>   </div>
							{if $choix_multi == true}
								{for $foo=1 to $compteur}
						  			<input type="checkbox" name="nom[]" value="{$name_{$foo}}" {if true==$check_{$foo}}checked{/if} {if $available_{$foo} == false} disabled{/if}>{$nom_{$foo}} {if $places_restantes_{$foo} >0}({$places_restantes_{$foo}} places restantes){/if}{if $available_{$foo} == false}(Complet){/if}</p>
								{/for}
							{else}								
								{for $foo=1 to $compteur}
									<input type="radio" name="nom" value="{$name_{$foo}}" {if true==$check_{$foo}}checked{/if} {if $available_{$foo} == false} disabled{/if}>{$nom_{$foo}}{if $places_restantes_{$foo} >0}({$places_restantes_{$foo}} places restantes){/if}{if $available_{$foo} == false}(Complet){/if}<br />	
								{/for}							
							{/if}
						
						</div>
						<div class="pageoverflow">
						    <p class="pagetext">&nbsp;</p>
						    <button class="btn btn-primary" type="submit" name="submit" value="Envoyer" >Envoyer</button> 
							<input type="submit" name="cancel" value="Annuler"></p>
						  </div>
						{form_end}
			<p>Tu pourras modifier ton choix depuis l'espace privé dans la page "Mon compte" dans le menu ci-dessus</p>
</div>
	