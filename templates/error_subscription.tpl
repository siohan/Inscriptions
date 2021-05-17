<h2>Inscriptions</h2>
<p class="alert alert-warning">J'ai déjà un compte : <a href="{cms_action_url module=FrontEndUsers action=login}">Je me connecte</a><br />Je ne sais pas si j'ai un compte mais <a href="{cms_action_url action='auth_by_number'}">j'ai une licence !</a>{$error_message}
{if true == $redir}<a href="{cms_action_url module=Adherents action=default display=crea id_group=$groupe id_inscription=$id_inscription}">Cliquez ici</a>{/if}</p>

