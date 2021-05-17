<a href="{cms_action_url action='defaultadmin'}">{admin_icon icon='back.gif'} Revenir</a><br />{*<button class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><a href="{cms_action_url action='add_edit_inscription' record_id=$id_inscription}">Modifier</a></button>*}

{tab_header name="content" label="Inscription"}
{tab_header name="envoi" label="Dates"}
{tab_header name="mode" label="Mode Auto/Manu"}
{tab_start name="content"}


<div class="c_full cf">
	<label class="grid_3">Nom</label>
	<div class="grid_8">
		{$nom}
	</div>
</div>
	<div class="c_full cf">
		<label class="grid_3">Description</label>
			<div class="grid_8">
				{cms_textarea name="description" value=$description enablewysiwyg=true readonly="readonly"}
			</div>
	</div>

	<div class="c_full cf">
		<label class="grid_3">Actif</label>
		<div class="grid_8">
			<select name="actif" disabled="disabled">{cms_yesno selected=$actif}</select>
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Groupe concerné</label>
		<div class="grid_8">
			<select name="groupe" disabled="disabled">{html_options options=$liste_groupes selected=$groupe}</select>
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Les personnes externes au groupe peuvent s'inscrire ?</label>
		<div class="grid_8">
			<select name="ext" disabled="disabled">{cms_yesno selected=$ext}</select>
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Groupe à notifier des réponses</label>
		<div class="grid_8">
			<select name="group_notif" disabled="disabled">{html_options options=$liste_groupes selected=$group_notif}</select>
		</div>
	</div>
	<div class="c_full cf">
		<label class="grid_3">Choix multiple</label>
		<div class="grid_8">
			<select name="choix_multi" disabled="disabled">{cms_yesno selected=$choix_multi}</select>
		</div>
	</div>
{tab_start name="envoi"}
<div class="c_full cf">
	<label class="grid_3">Date de début</label>
		<div class="grid_8">
			{html_select_date start_year='2020' end_year='+20' prefix='debut_' time=$date_debut disabled="disabled"}@ {html_select_time time=$date_debut prefix='debut_' disabled="disabled"}
		</div>
</div>
<div class="c_full cf">
		<label class="grid_3">Date de fin</label>
		<div class="grid_8">
			{html_select_date start_year='2020' end_year='+20' prefix='fin_' time=$date_fin disabled="disabled"}@ {html_select_time time=$date_fin prefix='fin_' disabled="disabled"}
		</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Date limite de réponse</label>
	<div class="pageinput">{html_select_date start_year='2020' end_year='+20' prefix='limite_' time=$date_limite disabled="disabled"}@ {html_select_time prefix='limite_' time=$date_limite disabled="disabled"}
	</div>
</div>
{tab_start name="mode"}
<div class="c_full cf">
	<label class="grid_3">Mode Automatique ?</label>
	<div class="grid_8">
		<select name="collect_mode" disabled="disabled">{cms_yesno selected=$collect_mode}</select>
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Début de prospection</label>
	<div class="grid_8">
		{html_select_date start_year='2020' end_year='+20' prefix='collect_' time=$start_collect disabled="disabled"}@ {html_select_time time=$start_collect prefix='collect_' disabled="disabled"}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Fin de prospection</label>
	<div class="grid_8">
		{html_select_date start_year='2020' end_year='+20' prefix='end_collect_' time=$end_collect disabled="disabled"}@ {html_select_time time=$end_collect prefix='end_collect_' disabled="disabled"}
	</div>
</div>
<div class="c_full cf">
	<label class="grid_3">Temps entre deux relances</label>		
	<div class="grid_8"><input type="text" name="result" value="{$result}" readonly="readonly"><select name="unite" disabled="disabled">{html_options options=$liste_unite selected=$unite disabled="disabled"}</select>
	</div>
</div>
{tab_end}

	

