<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<h3>Ma liste de participants</h3>
<table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
 <thead>
	<tr>
		<th>Licence</th>
	
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->licence}</td>
   
    
  </tr>
{/foreach}
 </tbody>
</table>
{/if}
{if $itemcount2 > 0}
{foreach from=$items2 item=entry}
{*<pre>{$items2|var_dump}</pre>*}
<h3>Résultats du {$entry->date_event|date_format:"%d/%m"}</h3>	
{if $itemscount_{$entry->valeur} >0 }
  	<table class="table table-bordered">
		{foreach from=$prods_{$entry->valeur} item=donnee}
		{*<pre>{$prods_{$entry->valeur}|var_dump}</pre>*}	<tr><td>{$donnee->equa}</td><td>{$donnee->scorea}</td><td>{$donnee->scoreb}</td><td>{$donnee->equb}</td></tr>
		{/foreach}
		</table>
		
		{else}
		<p>Pas encore de résultats !</p>
		{/if}
{/foreach}
 </tbody>
</table>
{/if}

