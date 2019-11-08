<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
  $('#selectall').click(function(){
    var v = $(this).attr('checked');
    if( v == 'checked' ) {
      $('.select').attr('checked','checked');
    } else {
      $('.select').removeAttr('checked');
    }
  });
  $('.select').click(function(){
    $('#selectall').removeAttr('checked');
  });
  $('#toggle_filter').click(function(){
    $('#filter_form').toggle();
  });
  {if isset($tablesorter)}
  $('#articlelist').tablesorter({ sortList:{$tablesorter} });
  {/if}
});
//]]>
</script>
<h2>Liste des réponses obtenues pour : {$titre}</h2>
<div class="pageoptions"><p class="pageoptions"><a href="{cms_action_url action='admin_options' record_id=$id_inscription}">{admin_icon icon='back.gif'} Revenir</a> <br /> {$itemcount}&nbsp;{$itemsfound} &nbsp; {$add_edit}</p></div>
{if $itemcount > 0}

<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>	
		<th>Nom</th>	
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->adherent}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}
