<h3>Ajout/modification d'une inscription</h3>
{form_start}
<div class="c_full cf">
  <input type="submit" name="submit" value="Envoyer"/>
  {if $edit > 0}
  <input type="submit" name="apply" value="Modifier"/>
  {/if}
  <input type="submit" name="cancel" value="Annuler" formnovalidate/>
</div>
{tab_header name="content" label="Inscription"}
{tab_header name="envoi" label="Dates"}
{tab_start name="content"}

{if $edit == 1}
<input type="hidden" name="record_id" value="{$record_id}" />
{/if}
<div class="c_full cf">
	<label class="grid_3">Nom</label>
	<div class="grid_8">
		<input type="text" name="nom" value="{$nom}">{cms_help key='help_nom_inscription' title='Libellé de votre événement'}
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
		<label class="grid_3">Groupe concerné</label>
		<div class="grid_8">
			<select name="groupe">{html_options options=$liste_groupes selected=$groupe}</select>{cms_help key='help_groupe' title='Groupe concerné'}
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Groupe à notifier des réponses</label>
		<div class="grid_8">
			<select name="group_notif">{html_options options=$liste_groupes selected=$group_notif}</select>{cms_help key='help_groupe_notif' title='Groupe concerné'}
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Choix multiple</label>
		<div class="grid_8">
			<select name="choix_multi">{cms_yesno selected=$choix_multi}</select>{cms_help key='help_choix_multi' title='Choix multiple ?'}
		</div>
	</div>
{tab_start name="envoi"}

<div class="c_full cf">
	<label class="grid_3">Date et heure d'envoi</label>
	<div class="pageinput">{html_select_date start_year='2019' end_year='+20' prefix='envoi_' time=$timbre}@ {html_select_time prefix='envoi_' time=$timbre}{cms_help key='help_envoi' title='Envoi des notifications'}
</div>
	
<div class="c_full cf">
	<label class="grid_3">Date de début</label>
		<div class="grid_8">
			{html_select_date start_year='2019' end_year='+20' prefix='debut_' time=$date_debut}@ {html_select_time time=$date_debut prefix='debut_'}{cms_help key='help_date_debut' title='Date de début'}
		</div>
</div>
<div class="c_full cf">
		<label class="grid_3">Date de fin</label>
		<div class="grid_8">
			{html_select_date start_year='2019' end_year='+20' prefix='fin_' time=$date_debut}@ {html_select_time time=$date_fin prefix='fin_'}{cms_help key='help_date_fin' title='Date de fin'}
		</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Date limite de réponse</label>
	<div class="grid_8">
		{html_select_date start_year='2019' end_year='+20' prefix='limite_' time=$date_limite}@ {html_select_time time=$date_limite prefix='limite_'}{cms_help key='help_date_limite' title='Date limite de réception des réponses'}
	</div>
</div>
{tab_end}
{form_end}

