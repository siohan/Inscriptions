<div class="pageoverflow">
{$formstart}

{$id_option}{$id_inscription}

{foreach from=$rowarray key=key item=entry}
<div class="pageoverflow">
    <p class="pageinput"><input type="checkbox"  name="m1_genid[{$key}]" id="m1_genid[{$key}]" {if $entry['participe'] ==1}checked='checked' {/if} value = '1'>{$entry['name']}</p>
  </div>
{/foreach}
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>