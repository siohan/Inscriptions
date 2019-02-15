<div class="pageoverflow">
{$formstart}
{$record_id}
{*$categorie_produit*}



<div class="pageoverflow">
  <p class="pagetext">Libellé</p>
  <p class="pageinput">{$nom} {cms_help key='help_nom_inscription' title='Libellé de votre événement'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Description</p>
  <p class="pageinput">{$description} {cms_help key='help_date_limite' title='Date limite de réception des réponses'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Date limite d'inscription</p>
  <p class="pageinput">{$date_limite} {cms_help key='help_date_limite' title=''}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Date de début</p>
  <p class="pageinput">{$date_debut} {cms_help key='help_date_debut' title='Date de début'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Date de fin</p>
  <p class="pageinput">{$date_fin} {cms_help key='help_date_fin' title='Date de fin'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Actif</p>
  <p class="pageinput">{$actif} {cms_help key='help_actif' title='Actif/Inactif'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Groupe ?</p>
  <p class="pageinput">{$groupe} {cms_help key='help_groupe' title='Groupe pour les réponses'}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Choix multiple ?</p>
  <p class="pageinput">{$choix_multi} {cms_help key='help_choix_multi' title='Choix multiple'}</p>
</div>
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
