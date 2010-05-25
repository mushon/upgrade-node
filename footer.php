</div><!-- #wrapper .hfeed -->

	<div id="footer" class="meta">
		<div class="footer-wrap">
			<span id="theme-link">Upgrade! <?php bloginfo('name') ?></span>
			<span class="meta-sep">|</span>
			<span class="log-name"><?php echo $username; wp_register('', ''); ?></span>
			<span class="meta-sep">|</span>
			<span class="log-in-out"><?php wp_loginout(); ?></span>
			<span class="meta-sep">|</span>
			<span id="generator-link">Powered by <a href="http://wordpress.org/" title="<?php _e( 'WordPress', 'sandbox' ) ?>" rel="generator"><?php _e( 'WordPress', 'sandbox' ) ?></a></span>
		</div>
	</div><!-- #footer -->

<?php wp_footer() ?>

</body>
</html>