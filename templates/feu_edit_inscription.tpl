<div class="table-responsive">
	<p> Salut {$prenom} ! </p>
						<span style="display:none;">{$compteur}
						{$choix_multi}</span>
						{$formstart}
						{$id_inscription}
						
						{$genid}{*la licence de l'adhérent*}
					
						<div class="pageoverflow">
						  <h4>{$titre}</h4>
						  <p>{$description}</p>
						
							{if $choix_multi == true}
								{for $foo=1 to $compteur}
						  			<p class="pageinput">{$name_{$foo}}{$nom_{$foo}}</p>
								{/for}
							{else}								
									<p class="pageinput">{$nom}</p>								
							{/if}
						
						</div>
						<div class="pageoverflow">
						    <p class="pagetext">&nbsp;</p>
						    <p class="pageinput">{$submit}{$cancel}</p>
						  </div>
						{$formend}
			<p>Tu pourras modifier ton choix depuis l'espace privé dans la page "Mon compte" dans le menu ci-dessus</p>
</div>
	