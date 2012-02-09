<h1>Reset Password</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" >
		<fieldset>
			<legend>Neues Passwort für <?php echo user_real_name(); ?></legend>
			<em>Bitte gib ein neues, unvergessliches Passwort ein.</em>
			
			<p>
			    <label for="password">Passwort:</label>
			    <input name="password" id="password" type="password" value="<?php echo Input::post('password'); ?>">
			</p>

			<p class="buttons">
			    <button type="submit">Einsenden</button>
			    <a href="<?php echo base_url(); ?>">Zurück zu <?php echo site_name(); ?></a>
			</p>
		</fieldset>
	</form>

</section>

