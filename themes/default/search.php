<section class="content">
    
    <h1>Deine Suche nach &ldquo;<?php echo search_term(); ?>&rdquo;.</h1>
    
    <?php if(has_search_results()): ?>
        <p>Wir fanden <?php echo total_search_results(); ?> <?php echo pluralise(total_search_results(), 'result'); ?> für &ldquo;<?php echo search_term(); ?>&rdquo;</p>
        <ul class="items wrap">
			<?php while(search_results()): ?>
			<li>
				<a href="<?php echo post_url(); ?>" title="<?php echo post_title(); ?>">
				    <time datetime="<?php echo date(DATE_W3C, post_time()); ?>"><?php echo relative_time(post_time()); ?></time>
				    <h2><?php echo post_title(); ?></h2>
				</a>
			</li>
			<?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Leider gab es keine Ergebnisse für &ldquo;<?php echo search_term(); ?>&rdquo;. Hast du alles richtig geschrieben?</p>
    <?php endif; ?>
    
</section>

