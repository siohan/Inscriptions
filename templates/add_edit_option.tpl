<h3>Ajout / Modification d'une option</h3>
{form_start}
<div class="c_full cf">
  <input type="submit" name="submit" value="Envoyer"/>
  {if $edit > 0}
  <input type="submit" name="apply" value="Modifier"/>
  {/if}
  <input type="submit" name="cancel" value="Annuler" formnovalidate/>
</div>
<input type="hidden" name="id_inscription" value="{$id_inscription}" />
{if $edit == 1}
<input type="hidden" name="record_id" value="{$record_id}" />
{/if}
<div class="c_full cf">
	<label class="grid_3">Nom</label>
	<div class="grid_8">
		<input type="text" name="nom" value="{$nom}" size="100">{cms_help key='help_nom_inscription' title='Libellé de votre événement'}
	</div>
</div>
	<div class="c_full cf">
		<label class="grid_3">Description</label>
			<div class="grid_8">
				{cms_textarea name="description" value=$description enablewysiwyg=true}
			</div>
	</div>

	<div class="c_full cf">
		<label class="grid_3">Actif</label>
		<div class="grid_8">
			<select name="actif">{cms_yesno selected=$actif}</select>{cms_help key='help_actif' title='Actif/Inactif'}
		</div>
	</div>	
<div class="c_full cf">
	<label class="grid_3">Date de début</label>
		<div class="grid_8">
			{html_select_date start_year='2019' end_year='+20' prefix='debut_' time=$date_debut}@ {html_select_time time=$date_debut prefix='debut_'}{*<input type="date" name="date_debut" value="{$date_debut}">*}{cms_help key='help_date_debut' title='Date de début'}
		</div>
</div>
<div class="c_full cf">
		<label class="grid_3">Date de fin</label>
		<div class="grid_8">
			{html_select_date start_year='2019' end_year='+20' prefix='fin_' time=$date_fin }@ {html_select_time time=$date_fin prefix='fin_'}{*<input type="date" name="date_fin" value="{$date_fin}">*}{cms_help key='help_date_fin' title='Date de fin'}
		</div>
</div>
<div class="c_full cf">
		<label class="grid_3">Tarif de l'option'</label>
		<div class="grid_8">
			<input type="text" name="tarif" value="{$tarif}"/>
		</div>
</div>


{form_end}
