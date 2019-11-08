
{if $itemcount >0}

<h3>Les options</h3>
<a href="{cms_action_url action=admin_reponses id_inscription=$id_inscription}">Revenir</a>
<p>Cliquez sur les chevrons pour modifier les choix.</p>
		<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
		 <thead>
		  <tr>
			<th>Nom</th>
			<th>Inscrit ?</th>		
		  </tr>
		 </thead>
		 <tbody>
		{foreach from=$items item=entry}
		  <tr class="{$entry->rowclass}">
			<td>{$entry->nom}</td>
		    <td>{$entry->is_inscrit}</td>	
		  </tr>
		{/foreach}
		 </tbody>
		</table>
{/if}
