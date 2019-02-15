{$start_form}
<fieldset>
<legend>Configuration principale</legend>
	<div class="pageoverflow">
		<p class="pagetext">Alias de la page des réponses (Obligatoire)</p>
		<p class="pageinput">{$pageid_inscriptions}</p>
	</div>
</fieldset>

<fieldset>	
<legend>Paramètres Email</legend>
<div class="pageoverflow">
	<p class="pagetext">Email du gestionnaire de présence</p>
	<p class="pageinput">{$input_adminemail}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail(prévenir et relancer)</p>
	<p class="pageinput">{$relanceemail}</p>
</div>
<!--
<div class="pageoverflow">
	<p class="pagetext">Le rapport des réponses</p>
	<p class="pageinput">{$send_email}</p>
</div>
-->
</fieldset>

{$submit}
{$end_form}