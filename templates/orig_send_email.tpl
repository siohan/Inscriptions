<h2>Liste des nouvelles inscriptions</h2>
<div class="pageoptions"><p class="pageoptions"></p></div>
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Nom</th>
		<th>Inscription</th>
		<th>option </th>
		<th>Timbre</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->nom_genid}</td>
	<td>{$entry->nom}</td>
	<td>{$entry->nom_option}</td>
	<td>{$entry->timbre}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}
<p><a href="{$lien_recap}">Consultez la liste en temps réel</a> 