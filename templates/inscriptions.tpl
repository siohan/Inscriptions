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
<h2>Liste des Inscriptions</h2>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound} &nbsp; <a href="{cms_action_url action=add_edit_inscription}">{admin_icon icon='newobject.gif'}Ajouter une inscription</a></p></div>
{if $itemcount > 0}
{*$form2start*}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Id</th>
		<th>Nom</th>
		<th>Description </th>
		<th>Date limite</th>
		<th>Début - Fin</th>
		<th>actif ?</th>
		<th>Prévenir</th>
		<th>Options actives</th>
		<th>Options</th>
		<th>Inscrit(s)</th>
		<th>Choix multi</th>
		<th>Groupe</th>
		<th colspan="2">Action(s)</th>
		<!--<th><input type="checkbox" id="selectall" name="selectall"></th>-->
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->nom}</td>
	<td>{$entry->description}</td>
	<td>{$entry->date_limite|date_format:"%d-%m-%Y"}</td>
	<td>{$entry->date_debut|date_format:"%d-%m-%Y (%Hh%M)"}-{$entry->date_fin|date_format:"%d-%m-%Y (%Hh%M)"}</td>
	<td>{$entry->actif}</td>
	<td>{$entry->emailing}</td>
	<td>{$entry->nb_options}</td>
	<td>{$entry->options}</td>
	<td>{$entry->nb_inscrits}</td>
	<td>{$entry->choix_multi}</td>
	<td>{$entry->groupe}</td>
	<td>{$entry->editlink}</td> 
	<td>{$entry->delete}</td> 
<!--	<td><input type="checkbox" name="{$actionid}sel[]" value="{$entry->id}" class="select"></td>-->
  </tr>
{/foreach}
 </tbody>
</table>

<!-- SELECT DROPDOWN 
<div class="pageoptions" style="float: right;">
<br/>{$actiondemasse}{$submit_massaction}
  </div> -->
{*$form2end*}
{/if}
