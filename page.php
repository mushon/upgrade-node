<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				<div class="post-content span-12">
					<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class='edit-link'>", "</span>" ) ?>
					<?php lang_links($post->ID)?>
					<div class="heading"><h2 class="entry-title"><?php the_title() ?></h2></div>
					<div class="entry-content">
						<?php the_content( __( 'Read On <span class="meta-nav">&raquo;</span>', 'sandbox' ) ) ?>
						<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'sandbox' ) . '&after=</div>') ?>
					</div>
				</div>

			</div><!-- .post -->

		</div><!-- #content -->
		</div>
	</div><!-- #container -->
	
<?php get_sidebar() ?>
<?php get_footer() ?>