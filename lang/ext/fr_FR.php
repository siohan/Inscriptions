<?php
$lang['friendlyname'] = 'Asso Inscriptions';
$lang['moddescription'] = 'Gestion des inscriptions.';

$lang['add'] = 'Ajouter ';
$lang['apply'] = 'Appliquer';
$lang['apply_to_selection'] = 'Appliquer à la sélection';
$lang['areyousure_actionmultiple'] = 'Etes-vous certain ?';
$lang['cancel'] = 'Annuler';
$lang['copy'] = 'Dupliquer';
$lang['delete'] = 'Supprimer';
$lang['edit'] = 'Modifier';

$lang['resultsfoundtext'] = 'Résultats trouvés';

$lang['welcome_text'] = '<p>Bienvenue dans le module de gestion des inscriptions</p>';
$lang['postinstall'] = 'Merci d\'avoir installer ce module.';
$lang['postuninstall'] = 'Module désinstallé.';
$lang['really_uninstall'] = 'Etes-vous certain de vouloir désinstaller ce module ?';
$lang['false'] = 'Faux';
$lang['true'] = 'Vrai';
$lang['submit'] = 'Envoyer';
$lang['submitasnew'] = 'Ajouter en tant que nouvelle équipe';
$lang['submitall'] = 'Tout sélectionner';
$lang['view'] = 'Voir';
$lang['new'] = 'Nouveau';
$lang['send'] = 'Envoi email';
$lang['filtres'] = 'Filtrez !';
$lang['groupassign'] = 'Créer une liste';


// les aides aux formulaires
$lang['help_nom_inscription'] = 'Donnez un nom évocateur pour votre événement. Il servira aussi comme objet dans le mail';
$lang['help_date_limite'] = 'Passée la date limite, il sera impossible d\'envoyer des relances et de s\'inscrire';
$lang['help_date_debut'] = 'Entrez la date de début votre événement';
$lang['help_date_fin'] = 'Entrez la date de fin votre événement; Si nul, la date de début sera appliquée';
$lang['help_duplication_time'] = 'Définissez l\'intervalle de temps entre la source et la copie !';
$lang['help_actif'] = 'Activez ou désactivez votre événement. Si inactif, les relances et les inscriptions sont stoppées.';
$lang['help_groupe'] = 'Choisissez le groupe à qui envoyer les inscriptions pour votre événement';
$lang['help_groupe_notif'] = 'Choisissez quel groupe va recevoir les notifications des inscriptions via email';
$lang['help_choix_multi'] = 'Si plusieurs réponses sont possibles, choisissez Oui. Sinon Non. Vous pouvez changer cela à tout moment dans ce formulaire ou en cliquant sur le chevron grisé ou vert';
$lang['help_pageid_inscription'] = 'Vous devez indiquez l\'alias de la page de contenu dans laquelle vous devez implémenter la balise (voir aide sur le module) ';
$lang['help_envoi'] = 'L\'envoi pourra être programmé (à venir) et nécessitera le module Messages';
$lang['help'] = '<h3>Que fait ce module ?</h3>
<p>Ce module vous permet de gérer les inscriptions en interne à diverses manifestations, rencontres, entrainements,etc..La version externe est prévue.</p>
<h3>Mode d\'emploi</h3>
<p>Entrez les données de votre inscription (nom, description, dates,...). Dans "options", créer les options qui apparaitront dans le formulaire.</p>
<h3>Envoi de notifications</h3>
<p>Envoyer des notifications par emails au groupe choisi pour l\'inscription. Un lien est automatiquement généré dans le courriel. Les utilisateurs répondent en cliquant sur ce lien.<br />
Pour les utilisateurs ayant déjà répondu, il n\'y a pas de nouvelles relances.</p> 
<p>Pour recueillir les réponses, vous devez au préalable implémenter la balise suivante dans une de vos pages :<code>{cms_module module=\'Inscriptions\'}</code> et obligatoirement renseigner l\'alias de la page dans l\'onglet "Config". </p>
<p>Selon l\'activité du site, des notifications pour des nouvelles inscriptions peuvent être envoyées au groupe à notifier avec un lien vers la page contenant la liste des inscriptions.</p>
<h3>Duplication</h3>
<p>Ne perdez plus de temps à créer des inscriptions, dupliquez-les ! En dupliquant une inscription, vous en créer une nouvelle avec les éléments de la source y compris les options (m^me celles inactives). Seules les adhésions ne sont pas copiées.</p>
<p>Dupliquez également vos options une à une si vous le souhaitez.</p>
<h3>Onglet Config</h3>
<p>Retrouvez l\'élément duplication : ce dernier constitue l\'intervalle de temps entre la source et la copie soit de votre inscription, soit de l\'option.</p>
<h3>Divers</h3>
<ul>
<li>Pour obtenir la dernière version en cours (avant release officielle)
<a href="https://github.com/siohan/adherents">Version github</a>.</li>
<li>Enfin, vous pouvez aussi m\'envoyer un mail.</li>  
</ul>
<p>En tant que licence GPL, ce module est livré tel quel. Merci de lire le texte complet de la license pour une information complête.</p>
<h3>Copyright et License</h3>
<p>Copyright &amp;copy; 2014, Claude Siohan <a href="mailto:claude.siohan@gmail.com">AssoSimple</a>. Tous droits réservés.</p>
<p>Ce module est sous licence <a href="http://www.gnu.org/licenses/licenses.html#GPL">GNU Public License</a>. Vous devez accepter la licence avant d\'utiliser ce module.</p>
<p>Ce module a été distribué dans l\'espoir d\'être utile, mais sans
AUCUNE GARANTIE. Il vous appartient de le tester avant toute mise en
production, que ce soit dans le cadre d\'une nouvelle installation ou
d\'une mise à jour du module. L\'auteur du module ne pourrait être tenu
pour responsable de tout dysfonctionnement du site provenant de ce
module. Pour plus d\'informations, <a
href=\"http://www.gnu.org/licenses/licenses.html#GPL\" target=\"_blank\">consultez
la licence GNU GPL</a>.</p>';

?>