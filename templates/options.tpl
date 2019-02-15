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
<h2>Liste des options</h2>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound} <br />{$Revenir} | {$add_edit}</p></div>
{if $itemcount > 0}
{*$form2start*}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Id</th>
		<th>Id inscription</th>
		<th>Nom</th>
		<th>Description </th>
		<th>Début</th>
		<th>Fin</th>
		<th>actif ?</th>
		<th>Inscrits ?</th>
		<th colspan="4">Action(s)</th>
	<!--	<th><input type="checkbox" id="selectall" name="selectall"></th>-->
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->id_inscription}</td>
	<td>{$entry->nom}</td>
	<td>{$entry->description}</td>
	<td>{$entry->date_debut|date_format:"%d-%m-%Y"} - {$entry->heure_debut}</td>
	<td>{$entry->date_fin|date_format:"%d-%m-%Y"} - {$entry->heure_fin}</td>
	<td>{$entry->actif}</td>
	<td>{$entry->inscrits}</td>
	<td>{$entry->editlink}</td>
	<td>{$entry->view}</td> 
	<td>{$entry->delete}</td>
	<td>{$entry->assign_users}</td> 
<!--	<td><input type="checkbox" name="{$actionid}sel[]" value="{$entry->id}" class="select"></td>-->
  </tr>
{/foreach}
 </tbody>
</table>
<!-- SELECT DROPDOWN 
<div class="pageoptions" style="float: right;">
<br/>{$actiondemasse}{$submit_massaction}
  </div>-->
{*$form2end*}
{/if}
