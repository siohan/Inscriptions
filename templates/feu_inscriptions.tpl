{if $itemcount > 0}


{foreach from=$items item=entry}
  <table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
	</tr>
 </thead>
 <tbody><tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->nom}</td>
	<td>{$entry->description|summarize:25}</td>
	<td>{if $genid >0}<a href="{cms_action_url action='' id_inscription=$entry->id genid=$genid}">Je m'inscris</a>{else}<a href="{cms_action_url action='' id_inscription=$entry->id}">Je m'inscris</a>{/if}
   </tr>
</tbody>
</table>
{/foreach}
 
{/if}
