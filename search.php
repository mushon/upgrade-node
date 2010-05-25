<?php get_header() ?>

	<div id="container">
		<div id="content">

<?php if ( have_posts() ) : ?>

		<h2 class="page-title"><?php _e( 'Search Results for:', 'sandbox' ) ?> <span><?php the_search_query() ?></span></h2>

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> <span class="title-nav">%title</span>' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '<span class="title-nav">%title</span> <span class="meta-nav">&rarr;</span>' ) ?></div>
				</div>

<?php while ( have_posts() ) : the_post() ?>

			<div id="post-<?php the_ID() ?>" class="<?php sandbox_post_class() ?>">
				<div class="post-content span-12">
					<?php lang_links($post->ID)?>
					<span class="cat-links"><?php printf( __( '%s', 'sandbox' ), get_the_category_list(' ') ) ?></span>
					<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class='edit-link'>", "</span>" ) ?>
					<div class="heading"><h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2></div>
					<?php if (in_category_name('events')){?>						
						<span class="event-time">
							<!-- Using the Event Calendar plugin's template tag: -->
							<?php ec3_schedule() ?>
							<!-- Using the default event date:
							<?php the_time('l, F jS, Y'); ?> at <?php the_time('G:i'); ?> -->
						</span><br/>
						<span class="event-loc"><?php echo get_post_meta($post->ID, 'event_loc', true);?></span>
					<?php } ?>
					<?php the_tags( __( '<div class="tag-links"><span class="tag-container"><a href="#" class="global-tag" title="search tag on the global network"></a>', 'sandbox' ), '</span><span class="tag-container"><a href="#" class="global-tag" title="search tag on the global network"></a>', "</span></div>" ) ?>
					<div class="entry-content">
						<?php the_content( __( 'Read On <span class="meta-nav">&raquo;</span>', 'sandbox' ) ) ?>
						<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'sandbox' ) . '&after=</div>') ?>
					</div>
				</div>
								
				<div class="entry-meta meta span-1 last">
					<a href="#" class="btn-up"></a>
					<a class="author-img" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename )?>" title="View all posts by <?php echo $authordata->display_name ?>">
						<?php echo get_avatar( get_the_author_email(), '20' ); ?>
					</a>
					<!--
<span class="day"><?php the_time('j'); ?></span>
					<span class="month"><?php the_time('M'); ?></span>
					<span class="year"><?php the_time('Y'); ?></span>
-->
					<span class="permalink"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"></a></span>
					<span class="comments-link"><?php comments_popup_link( __( '', 'sandbox' ), __( '1', 'sandbox' ), __( '%', 'sandbox' ) ) ?></span>
					<a href="#" class="btn-down"></a>

				</div>
			</div><!-- .post -->

<?php endwhile; ?>

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> <span class="title-nav">%title</span>' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '<span class="title-nav">%title</span> <span class="meta-nav">&rarr;</span>' ) ?></div>
				</div>

<?php else : ?>

			<div id="post-0" class="post no-results not-found">
				<h2 class="entry-title"><?php _e( 'Nothing Found', 'sandbox' ) ?></h2>
				<div class="entry-content">
					<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'sandbox' ) ?></p>
				</div>
				<form id="searchform-no-results" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s-no-results" name="s" class="text" type="text" value="<?php the_search_query() ?>" size="40" />
						<input class="button" type="submit" value="<?php _e( 'Find', 'sandbox' ) ?>" />
					</div>
				</form>
			</div><!-- .post -->

<?php endif; ?>

		</div><!-- #content -->
		</div>
	</div><!-- #container -->
	
<?php get_sidebar() ?>
<?php get_footer() ?>