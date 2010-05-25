<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php the_post() ?>

			<div class="post-head">
				<h2 class="page-title"><a href="<?php echo get_permalink($post->post_parent) ?>" title="<?php printf( __( 'Return to %s', 'sandbox' ), wp_specialchars( get_the_title($post->post_parent), 1 ) ) ?>" rev="attachment"><?php echo get_the_title($post->post_parent) ?></a></h2>
				<a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename )?>" title="View all posts by <?php echo $authordata->display_name ?>"><?php echo get_avatar( get_the_author_email(), '41' ); ?></a>
				<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class=\"edit-link\">", "</span>" ) ?>
			</div>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				
				<div class="entry-content">
				<h3 class="entry-title"><?php the_title() ?></h3>
					<div class="entry-attachment"><a href="<?php echo wp_get_attachment_url($post->ID); ?>" title="<?php echo wp_specialchars( get_the_title($post->ID), 1 ) ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a></div>
					<div class="entry-caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt() ?></div>
<?php the_content() ?>

				</div>
				
				<div class="entry-meta meta">
						<span class="author vcard"><?php printf( __( 'By %s', 'sandbox' ), '<a class="url fn n" href="' . get_author_link( false, $authordata->ID, $authordata->user_nicename ) . '" title="' . sprintf( __( 'View all posts by %s', 'sandbox' ), $authordata->display_name ) . '">' . get_the_author() . '</a>' ) ?></span>
					<span class="date"><?php the_date('H:i, M jS, y'); ?></span>
					<span class="cat-links"><?php printf( __( '%s', 'sandbox' ), get_the_category_list(' ') ) ?></span>
					<?php the_tags( __( '<span class="tag-links">', 'sandbox' ), " ", "</span>" ) ?>
					<span class="comfeed-link"><a href="<?php echo comments_rss() ?>" title="Comments RSS to <?php the_title() ?>" rel="alternate" type="application/rss+xml">Comments RSS</a></span>
				</div>
				
			</div><!-- .post -->

			<div id="nav-images" class="navigation">
				<div class="nav-previous"><?php previous_image_link() ?></div>
				<div class="nav-next"><?php next_image_link() ?></div>
			</div>

<?php comments_template() ?>

		</div><!-- #content -->
		</div>
	</div><!-- #container -->
	
<?php get_sidebar() ?>
<?php get_footer() ?>