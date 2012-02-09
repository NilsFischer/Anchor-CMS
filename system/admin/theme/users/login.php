<h1>Log in</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
		<fieldset>
			
			<p>
			    <label for="user">Benutzername:</label>
			    <input autocapitalize="off" name="user" id="user" value="<?php echo Input::post('user'); ?>">
			</p>
			
			<p>
    			<label for="pass">Passwort:</label>
    			<input type="password" name="pass" id="pass">
    			
    			<em>Solltest du dein Passwort vergessen haben, wende dich an den Seitenbetreiber.</em>
			</p>

			<p class="buttons">
			    <button type="submit">Login</button>
			    <a href="<?php echo base_url('admin/users/amnesia'); ?>">Hilfe! Ich habe mein Passwort vergessen.</a>
			    <a href="<?php echo base_url(); ?>">Zurück zu <?php echo site_name(); ?></a>
			</p>
		</fieldset>
	</form>

</section>

