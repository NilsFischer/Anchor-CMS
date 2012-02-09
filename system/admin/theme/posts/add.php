
<h1>Add a Post</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="title">Titel:</label>
    			<input id="title" name="title" value="<?php echo Input::post('title'); ?>">
    			
    			<em>Dein Beitragstitel.</em>
    		</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug'); ?>">
			    
			    <em>Der "Slug" für deinen beitrag (<code><?php echo $_SERVER['HTTP_HOST']; ?>/posts/<span id="output">slug</span></code>).</em>
			</p>
			
            <p>
                <label for="description">Beschreibung:</label>
                <textarea id="description" name="description"><?php echo Input::post('description'); ?></textarea>
                
                <em>Eine kompakte Zusammenfassung des Beitrags. Used in the post introduction, RSS feed, and <code>&lt;meta name="description" /&gt;</code>.</em>
            </p>
            
			<p>
			    <label for="html">Inhalt:</label>
			    <textarea id="html" name="html"><?php echo Input::post('html'); ?></textarea>
			    
			    <em>Der eigentliche Inhalt des Beitrags. Freut sich über eine kräftige Dosis HTML.</em>
			</p>
			
			<p>
			    <label>Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('Vorlage', 'Archiviert', 'Publiziert') as $status): ?>
    				<option value="<?php echo $status; ?>" <?php if(Input::post('status') == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>Stadien: Publiziert (Ansehbar), Vorlage (Nicht fertig), oder Archiviert (Versteckt).</em>
			</p>
		</fieldset>
		
		<fieldset>
		    <legend>Modifizieren</legend>
		    <em>Hier kannst du deinen Post modifiezieren. Dies ist jedoch optional.</em>
		    
		    <p>
		        <label for="css">Eigenes CSS:</label>
		        <textarea id="css" name="css"><?php echo Input::post('css'); ?></textarea>
		        
		        <em>Spezielles CSS. Wird in einem <code>&lt;style&gt;</code> Block verpackt.</em>
		    </p>

            <p>
                <label for="js">Eigenes JS:</label>
                <textarea id="js" name="js"><?php echo Input::post('js'); ?></textarea>
                
                <em>Spezielles Javascript. Wird in einem<code>&lt;script&gt;</code> Block verpackt.</em>
            </p>
		</fieldset>
		
		<fieldset>
		    <legend>Eigene Felder</legend>
		    <em>Erstelle dir eigene Felder.</em>

			<div id="fields">
				<!-- Re-Populate post data -->
				<?php foreach(Input::post('field', array()) as $data => $value): ?>
				<?php list($key, $label) = explode(':', $data); ?>
				<p>
					<label><?php echo $label; ?></label>
					<input name="field[<?php echo $key; ?>:<?php echo $label; ?>]" value="<?php echo $value; ?>">
				</p>
				<?php endforeach; ?>
			</div>
		</fieldset>
			
		<p class="buttons">
			<button type="submit">Erstellen</button>
			<button id="create" type="button">Erstell ein eigenes Feld</button>
			<a href="<?php echo base_url('admin/posts'); ?>">Kehre zu den Beiträgen zurück</a>
		</p>
	</form>

</section>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('js/mootools.js'); ?>"><\/script>');</script>

<script src="<?php echo theme_url('js/helpers.js'); ?>"></script>
<script src="<?php echo theme_url('js/popup.js'); ?>"></script>
<script src="<?php echo theme_url('js/custom_fields.js'); ?>"></script>

<script>
	(function() {
		var slug = $('slug'), output = $('output');

		// call the function to init the input text
		formatSlug(slug, output);

		// bind to input
		slug.addEvent('keyup', function() {formatSlug(slug, output)});
	}());
</script>

