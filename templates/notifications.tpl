{form_start action="admin_emails_tab"}
	
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
</fieldset>
<input type="submit" name="submit" value="Envoyer">
 <input type="submit" name="cancel" value="Annuler" formnovalidate/>
{form_end}
