<h1>Login</h1>
<p>Logga in med användarnamn eller email.</p>

<?=$login_form->GetHTML(array('start'=>true))?>
  <fieldset>
  <?=$login_form['acronym']->GetHTML()?>
  <?=$login_form['password']->GetHTML()?>
  <?=$login_form['login']->GetHTML()?>

  <?php if($allow_create_user) : ?>
  <p class='form-action-link'><a href='<?=$create_user_url?>' title='Create a new user account'>Skapa användare</a></p>
  <?php endif; ?>
  </fieldset>
</form>
