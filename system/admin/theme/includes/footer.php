
	<?php if(user_authed()): ?>
	<aside id="sidebar">
		<h2>Status check</h2>
		
		<?php if(error_check() !== false): ?>
		<p>Oh Nein, wir fanden <?php echo count(error_check()) === 1 ? 'ein Problem' : 'mehrere Probleme'; ?>!</p>
		
		<ul>
		    <?php foreach(error_check() as $error): ?>
		    <li><?php echo $error; ?></li>
		    <?php endforeach; ?>
		</ul>
		<?php else: ?>
		    <p>Sieht alles gut aus, weiter so.</p>        
		<?php endif; ?>
	</aside>
	<?php endif; ?>

    <footer id="bottom">
        <small>Läuft auf Anchor, Version <?php echo ANCHOR_VERSION; ?>. <a href="<?php echo base_url(); ?>">Sieh dir deine Seita an</a>.</small>
        
        <em>Make blogging beautiful.</em>
    </footer>
	
	</body>
</html>
