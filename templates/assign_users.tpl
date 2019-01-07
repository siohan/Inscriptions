
{**}<div class="pageoverflow">
	<p>Les adhérents sélectionnés par catégorie sont actifs et non déjà affectés à cette cotisation</p>
{$formstart}

<div class="pageoverflow">
    <p class="pagetext">N° option :</p>
    <p class="pageinput">{$id_option}</p>
  </div>
<div class="pageoverflow">
    <p class="pagetext">N° Inscription :</p>
    <p class="pageinput">{$id_inscription}</p>
  </div>

{foreach from=$rowarray key=key item=entry}
<div class="pageoverflow">
    <p class="pageinput"><input type="checkbox"  name="m1_licence[{$key}]" id="m1_licence[{$key}]" {if $entry['participe'] ==1}checked='checked' {/if} value = '1'>{$entry['name']}</p>
  </div>
{/foreach}
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
{**}