<h1>Site metadata</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="sitename">Seiten/Blogname:</label>
    			<input id="sitename" name="sitename" value="<?php echo Input::post('name', site_name()); ?>">
    			
    			<em>Der Name deiner Seite und/oder deines Blogs.</em>
    		</p>
			
			
			<p>
			    <label for="description">Seitenbeschreibung:</label>
			    <textarea id="description" name="description"><?php echo Input::post('description', site_description()); ?></textarea>
			    
			    <em>Worum gehts hier?</em>
			</p>
			
			<p>
			    <label>Aktuelles Theme:</label>
    			<select id="theme" name="theme">
    				<?php foreach(glob(PATH . 'themes/*') as $theme): ?>
    				<option value="<?php echo replace($theme); ?>"<?php if(Input::post('theme', current_theme()) == replace($theme)) echo 'selected'; ?>>
    					<?php echo ucwords(replace($theme)); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Dein aktuell ausgwähltes Theme (bestimmt das Aussehen).</em>
			</p>
			
			<p>
				<label for="twitter">Twitter:</label>
				<input id="twitter" name="twitter" value="<?php echo Input::post('twitter', twitter_account()); ?>">
				
				<em>Dein Twitter Account. So wirds aussehen: @<span id="output"><?php echo twitter_account(); ?></span>.</em>
			</p>
		</fieldset>
			
		<p class="buttons">
			<button name="save" type="submit">Änderungen speichern</button>
		</p>
	</form>

</section>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('js/mootools.js'); ?>"><\/script>');</script>

<script src="<?php echo theme_url('js/helpers.js'); ?>"></script>

<script>
	(function() {
		var tweet = $('twitter'), output = $('output');

		// call the function to init the input text
		formatTwitter(tweet, output);

		// bind to input
		tweet.addEvent('keyup', function() {formatTwitter(tweet, output)});
	}());
</script>

