{form_start action="admin_options_tab"}
<fieldset>
	<legend>Configuration principale</legend>
	<div class="pageoverflow">
		<p class="pagetext">Alias de la page du module (Obligatoire){cms_help key='help_pageid_inscription' title='Alias de la page des inscriptions'}</p>
		<p class="pageinput"><input type="text" name="pageid_inscriptions" value="{$pageid_inscriptions}"></p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">Mode automatique par défaut ? {cms_help key='help_auto_mode' title='Mode automatique ou manuel'}</p>
		<p class="pageinput"><select name="collect_mode">{cms_yesno selected=$collect_mode}</select></p>
	</div>
<div class="pageoverflow">
	<p class="pagetext">Intervalle par défaut entre deux relances {cms_help key='help_collect_time' title='Intervalle de temps entre deux relances'}</p>
	<p class="pageinput"><input type="text" name="result1" value="{$result1}"><select name="unite1">{html_options options=$liste_unite1 selected=$unite1}</select></p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Duplication {cms_help key='help_duplication_time' title='Intervalle de temps depuis la source'}</p>
	<p class="pageinput"><input type="text" name="result" value="{$result}"><select name="unite">{html_options options=$liste_unite selected=$unite}</select></p>
</div>
</fieldset>
<input type="submit" name="submit" value="Envoyer">
 <input type="submit" name="cancel" value="Annuler" formnovalidate/>
{form_end}
