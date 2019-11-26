{form_start action="admin_options_tab"}
<fieldset>
	<legend>Configuration principale</legend>
	<div class="pageoverflow">
		<p class="pagetext">Alias de la page du module (Obligatoire){cms_help key='help_pageid_inscription' title='Alias de la page des inscriptions'}</p>
		<p class="pageinput"><input type="text" name="pageid_inscriptions" value="{$pageid_inscriptions}"></p>
	</div>
<div class="pageoverflow">
	<p class="pagetext">Duplication {cms_help key='help_duplication_time' title='Intervalle de temps depuis la source'}</p>
	<p class="pageinput"><input type="text" name="result" value="{$result}"><select name="unite">{html_options options=$liste_unite selected=$unite}</select></p>
</div>
</fieldset>
<input type="submit" name="submit" value="Envoyer">
 <input type="submit" name="cancel" value="Annuler" formnovalidate/>
{form_end}
