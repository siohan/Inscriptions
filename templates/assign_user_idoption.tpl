
              <div class="table-responsive">
						<span style="display:hidden;">{$compteur}
						{$choix_multi}</span>
						{$formstart}
						{$id_inscription}
						
						{$record_id}{*la licence de l'adhérent*}
					
						<div class="pageoverflow">
						  <p class="pagetext">Nom:</p>
						
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
						</div>
					</div>