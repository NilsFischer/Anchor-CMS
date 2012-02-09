<h1>Editing &ldquo;<?php echo truncate(page_name(), 4); ?>&rdquo;</h1>

<?php echo notifications(); ?>

<section class="content">

	<form method="post" action="<?php echo current_url(); ?>" novalidate>
		<fieldset>
			<p>
    			<label for="name">Name:</label>
    			<input id="name" name="name" value="<?php echo Input::post('name', page_name()); ?>">
    			
    			<em>Ter Name deiner Seite. Er wird in der Navigationsleiste angezeigt.</em>
    		</p>
			
			<p>
			    <label>Titel:</label>
			    <input id="title" name="title" value="<?php echo Input::post('title', page_title()); ?>">
			    
			    <em>Der Titel der Seite, welcher im  <code>&lt;title&gt;</code> angezeigt wird.</em>
			</p>
			
			<p>
			    <label for="slug">Slug:</label>
			    <input id="slug" autocomplete="off" name="slug" value="<?php echo Input::post('slug', page_slug()); ?>">
			    
			    <em>The slug for your post (<code><?php echo $_SERVER['HTTP_HOST']; ?>/<span id="output">slug</span></code>).</em>
			</p>
			
			<p>
			    <label for="content">Inhalt:</label>
			    <textarea id="content" name="content"><?php echo Input::post('content', page_content()); ?></textarea>
			    
			    <em>YDer Inhalt der Seite. Gerne auch in HTML.</em>
			</p>
			
			<p>
			    <label>Status:</label>
    			<select id="status" name="status">
    				<?php foreach(array('Vorlage', 'Archiviert', 'Publiziert') as $status): ?>
    				<option value="<?php echo $status; ?>"<?php if(Input::post('status', page_status()) == $status) echo 'selected'; ?>>
    					<?php echo ucwords($status); ?>
    				</option>
    				<?php endforeach; ?>
    			</select>
    			
    			<em>DWie möchtest du deine Seite speichern? Als Vorlage (Nicht fertig?), Archiviert (Fertig und peinlich) oder Publiziert (Fertig und anschaubar).</em>
			</p>
		</fieldset>
			
		<p class="buttons">
		    
		    <input type="hidden" name="id" value="<?php echo page_id(); ?>">
		
			<button name="save" type="submit">Speichern</button>
			<?php if(page_id() != Config::get('metadata.show_posts')): ?>
			<button name="delete" type="submit">Löschen</button>
			<?php endif; ?>
			
			<a href="<?php echo base_url('admin/pages'); ?>">Zurück zur Seiten Seite</a>
		</p>
	</form>

</section>

<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.1/mootools-yui-compressed.js"></script>
<script>window.MooTools || document.write('<script src="<?php echo theme_url('js/mootools.js'); ?>"><\/script>');</script>

<script src="<?php echo theme_url('js/helpers.js'); ?>"></script>

<script>
	(function() {
		var slug = $('slug'), output = $('output');

		// call the function to init the input text
		formatSlug(slug, output);

		// bind to input
		slug.addEvent('keyup', function() {formatSlug(slug, output)});
	}());
</script>

