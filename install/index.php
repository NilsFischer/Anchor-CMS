<!doctype html>
<html lang="en-gb">
    <head>
        <meta charset="utf-8">
        <title>Install Anchor CMS</title>
        <mate name="robots" content="noindex, nofollow">
        
        <link rel="stylesheet" href="css/install.css">
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/jquery.js"><\/script>');</script>
        <script src="js/install.js"></script>
    </head>
    <body>
    
    	<h1><img src="img/logo.gif" alt="Anchor install logo"></h1>
    	
    	<?php
    	
    	/*
    		Compatibility checks
    	*/
    	$compat = array();
    	
    	// php
    	if(version_compare(PHP_VERSION, '5.3.0', '<')) {
    		$compat[] = 'Anchor benötigt PHP 5.3 oder neuer.<br><em>Im Moment läuft bei dir PHP ' . PHP_VERSION . '</em>';
    	}
    	
    	// curl
    	if(function_exists('curl_init') === false) {
    		$compat[] = 'Anchor benötigt eine installierte und aktiverite Version von PHP cURL';
    	}
    	
    	// PDO
    	if(class_exists('PDO') === false) {
    		$compat[] = 'Anchor benötigt PDO (PHP Data Objects)';
    	} else {
    		if(in_array('mysql', PDO::getAvailableDrivers()) === false) {
    			$compat[] = 'Anchor benötigt den MySQL PDO Driver';
    		}
    	}

    	?>
    	
    	<?php if(count($compat)): ?>
    
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Für Anchor fehlen ein paar Vorraussetzungen:</p>
    		
    		<ul style="padding-bottom: 1em;">
    			<?php foreach($compat as $item): ?>
    			<li><?php echo $item; ?></li>
    			<?php endforeach; ?>
    		</ul>
    		
    		<p><a href="." class="button" style="float: none; display: inline-block;">Ok, ich habe es behoben, lasst die Spiele beginnen.</a></p>
    	</div>
    
    	<?php elseif(file_exists('../config.php')): ?>
    	
    	<div class="content">
    		<h2>Woops.</h2>
    		
    		<p>Anchor ist bereits installiert. Du solltest diesen Ordner wirklich löschen!</p>
    		
    		<p><a href="../" class="button" style="float: none; display: inline-block;">Zurück zur Hauptseite.</a></p>
    	</div>
    	
    	<?php else: ?>
    
        <p class="nojs error">Du musst für diese Installation JavaScript aktivieren. <em>Sorry :(</em></p>

        <div class="content">
            <h2>Willkommen zu Anchor.</h2>

            <p>Wenn du dich schon länger nach einem leichten, einfachen Bloggingsystem umgesehen hast, hast du es gefunden. Füll einfach die Daten aus und ZACK!, schon hast du deinen neuen Blog.</p>
            
            <small>Wenn du eine etwas andere, eigene Installation willst, bearbeite einfach <code>config.default.php</code> 
            (Ob vor oder nach dieser Installation ist eigentlich egal, solange du es <code>config.php</code> nennst).</small>
            
            <div class="notes"></div>
            
            <form method="get" novalidate>
                <fieldset id="diagnose">
                    <legend>Deine Datenbankeinstellungen</legend>
                    
                    <p>
                        <label for="host">Dein Datenbankhost:</label>
                        <input id="host" name="host" placeholder="localhost">
                    </p>
                    <p>
                        <label for="user">Dein Datenbankbenutzername:</label>
                        <input id="user" autocapitalize="off" name="user" placeholder="root">
                    </p>
                    <p>
                        <label for="pass">Dein Passwort:</label>
                        <input id="pass" autocapitalize="off" name="pass" placeholder="password">
                    </p>
                    <p>
                        <label for="db">Dein Datenbankname:</label>
                        <input id="db" autocapitalize="off" name="db" placeholder="anchor">
                    </p>
                    
                    <a href="#check" class="button">Check die Datenbankeinstellungen ab, Alter!</a>
                </fieldset>
                
                <fieldset>
                    <legend>Über deine Seite</legend>
                    
                    <p>
                        <label for="name">Seitenname:</label>
                        <input id="name" name="name" placeholder="My awesome Anchor site">
                    </p>
                    
                    <p>
                        <label for="description">Beschreibung:</label>
                        <textarea id="description" name="description"></textarea>
                    </p>

                    <p>
                        <label for="theme">Theme:</label>
                        <select id="theme" name="theme">
                            <?php foreach(glob('../themes/*') as $theme): $name = basename($theme); ?>
                            <?php if(file_exists($theme . '/about.txt')): ?>
                            <option value="<?php echo $name; ?>" <?php if($name === 'default') echo 'selected'; ?>><?php echo ucwords($name); ?></option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </p>
                    
                    <p>
                        <label for="email">Deine Email-Adresse:</label>
                        <input id="email" name="email">
                    </p>
                    
                    <p>
                        <label for="path">Der Pfad zu deiner Seite:</label>
                        <input id="path" name="path" value="<?php echo dirname(dirname($_SERVER['SCRIPT_NAME'])); ?>">
                    </p>
                    
                    <p>
                        <label><input type="checkbox" name="clean_urls" value="1">
                        Benutze saubere URLs</label> (Apache mod_rewrite ist aktiviert)
                    </p>
                    
                </fieldset>
                
                <br style="clear: both;">
                <button type="submit">Installier Anchor</button>
            </form>
        </div>
        
        <?php endif; ?>
        
        <p class="footer">Mit viel Liebe von <a href="//twitter.com/visualidiot">Visual Idiot</a> gemacht. Sollte es nicht klappen, sende ihm eine (englische) Nachricht auf Twitter (Solltest du nicht gut Englisch können, gibt es noch Google Translate). Er wird antworten.</p>
    </body>
</html>
