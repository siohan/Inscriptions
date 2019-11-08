{form_start action="admin_emails_tab"}
<fieldset>
	<legend>Configuration principale</legend>
	<div class="pageoverflow">
		<p class="pagetext">Alias de la page du module (Obligatoire)</p>{cms_help key='help_pageid_inscription' title='Alias de la page des inscriptions'}
		<p class="pageinput"><input type="text" name="pageid_inscriptions" value="{$pageid_inscriptions}"></p>
	</div>
<fieldset>	
<legend>Paramètres Email</legend>
<div class="pageoverflow">
	<p class="pagetext">Email du gestionnaire des inscriptions</p>
	<p class="pageinput"><input type="text" name="admin_email" value="{$admin_email}"></p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail (prévenir et relancer)</p>
	<p class="pageinput">{cms_textarea name="relanceemail" text=$relanceemail enablewysiwyg=false}</p>
</div>
<!--
<div class="pageoverflow">
	<p class="pagetext">Le rapport des réponses</p>
	<p class="pageinput">{$send_email}</p>
</div>
-->
</fieldset>
<input type="submit" name="submit" value="Envoyer">
 <input type="submit" name="cancel" value="Annuler" formnovalidate/>
{form_end}
