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
{tab_header name="mode" label="Mode Auto/Manu"}
{tab_start name="content"}

{if $edit == 1}
<input type="hidden" name="record_id" value="{$record_id}" />
{/if}
<input type="hidden" name="timbre" value="{$timbre}">
<div class="c_full cf">
	<label class="grid_3">Nom</label>
	<div class="grid_8">
		<input type="text" name="nom" value="{$nom}">{cms_help key='help_actif_inactif' title='Activation/Désactivation'}
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
	<label class="grid_3">Date de début</label>
		<div class="grid_8">
			{html_select_date start_year='2020' end_year='+20' prefix='debut_' time=$date_debut}@ {html_select_time time=$date_debut prefix='debut_'}{cms_help key='help_date_debut' title='Date de début'}
		</div>
</div>
<div class="c_full cf">
		<label class="grid_3">Date de fin</label>
		<div class="grid_8">
			{html_select_date start_year='2020' end_year='+20' prefix='fin_' time=$date_fin}@ {html_select_time time=$date_fin prefix='fin_'}{cms_help key='help_date_fin' title='Date de fin'}
		</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Date limite de réponse</label>
	<div class="pageinput">{html_select_date start_year='2020' end_year='+20' prefix='limite_' time=$date_limite}@ {html_select_time prefix='limite_' time=$date_limite}{cms_help key='help_limite_reponses' title='Envoi des notifications'}
	</div>
</div>
{tab_start name="mode"}
<div class="c_full cf">
	<label class="grid_3">Mode Automatique ?</label>
	<div class="grid_8">
		<select name="collect_mode">{cms_yesno selected=$collect_mode}</select>{cms_help key='help_auto_mode' title='Mode automatique ou manuel ?'}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Début de prospection</label>
	<div class="grid_8">
		{html_select_date start_year='2020' end_year='+20' prefix='collect_' time=$start_collect}@ {html_select_time time=$start_collect prefix='collect_'}{cms_help key='help_start_collect' title='Début de prospection'}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Fin de prospection</label>
	<div class="grid_8">
		{html_select_date start_year='2020' end_year='+20' prefix='end_collect_' time=$end_collect}@ {html_select_time time=$end_collect prefix='end_collect_'}{cms_help key='help_end_collect' title='Fin de prospection'}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Temps entre deux relances</label>		
	<div class="grid_8"><input type="text" name="result" value="{$result}"><select name="unite">{html_options options=$liste_unite selected=$unite}</select>{cms_help key='help_relances' title='Les relances'}
	</div>
</div>
{tab_end}
{form_end}

	

