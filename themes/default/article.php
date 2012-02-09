<section class="content">

    <h1><?php echo article_title(); ?></h1>
	
	<article>
	    <?php echo article_html(); ?>
	</article>
</section>

<section class="footnote">
	<p>Dieser Artikel ist mein <?php echo numeral(article_id() + 1); ?> Ältester. Er ist <?php echo count_words(article_html()); ?> Wörter lang. <?php echo article_custom_field('attribution'); ?></p>
</section>

