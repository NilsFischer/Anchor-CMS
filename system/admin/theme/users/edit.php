<h1>Editing <?php echo user_name(); ?>&rsquo;s profile</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate autocomplete="off">
		<fieldset>
			<p>
    			<label for="real_name">Realer Name:</label>
    			<input id="real_name" name="real_name" value="<?php echo Input::post('name', user_real_name()); ?>">
    			
    			<em>Der echte Name des Autors. Wird in der Verfasserzeile genutzt (Öffentlich).</em>
    		</p>
						
            <p>
                <label for="bio">Informationen:</label>
                <textarea id="bio" name="bio"><?php echo Input::post('bio', user_bio()); ?></textarea>
                
                <em>Ein paar Informationen über den Benutzer. Akzeptiert HTML solange es korrekt ist.</em>
            </p>
			
			<p>
			    <label for="status">Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('inactive','active') as $status): ?>
    				<option value="<?php echo $status; ?>"<?php if(Input::post('status', user_status()) == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Wenn der Status "Inactive" ist, wird sich der Benutzer nicht einloggen können.</em>
			</p>
			
			<p>
			    <label for="role">Rolle:</label>
    			<select id="role" name="role">
    				<?php foreach(array('administrator', 'editor', 'user') as $role): ?>
    				<option value="<?php echo $role; ?>"<?php if(Input::post('role', user_role()) == $role) echo 'selected'; ?>>
    					<?php echo ucwords($role); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Die Rolle des Benutzers. Siehe <a href="//anchorcms.com/docs/roles">hier</a> für mehr Informationen.</em>
			</p>
		</fieldset>
		
		<fieldset>
		
		    <legend>Benutzereinstellungen</legend>
		    <em>Die notwendigen Daten, um sich bei Anchor einloggen zu können.</em>
		
		    <p>
		        <label for="username">Benutzername:</label>
		        <input id="username" name="username" value="<?php echo Input::post('username', user_name()); ?>">
		        
		        <em>TDer gewünschte Benutzername. Kann später geändert werden.</em>
		    </p>

            <p>
                <label for="password">Passwort:</label>
                <input id="password" type="password" name="password">
                
                <em>Leer lassen, um nichts zu ändern.</em>
            </p>
            
		    <p>
		        <label for="email">Email:</label>
		        <input id="email" name="email" value="<?php echo Input::post('email', user_email()); ?>">

                <em>Die Email-Adresse des Benutzers. Wird gebraucht, sollte das Passwort vergessen werden.</em>
		    </p>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Update</button>
			<?php if(user_authed_id() !== user_id()): ?>
			<button name="delete" type="submit">Löschen</button>
			<?php endif; ?>
			
			<a href="<?php echo base_url('admin/users'); ?>">Kehre zu den Benutzern zurück</a>
		</p>
	</form>

</section>
