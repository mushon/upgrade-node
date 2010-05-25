<div class="sidebars">
	<div id="primary" class="sidebar">
		<ul class="xoxo">
		
			<li id="upgrade-international">
				A part of:<br/>
				<a href="http://www.theupgrade.net" target="_blank" title="Go to theUpgrade.net ">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/styles/images/ui_logo.png" alt="Upgrade International Network"/>
				</a>
			</li>
			
			<li id="organized">
				Organized by:
				<ul>
					<?php wp_list_bookmarks('title_li=&category_name=organized&categorize=0&orderby=rating'); ?> 
				</ul>
			</li>
		
		</ul>
		
		<ul class="xoxo">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(1) ) : // begin primary sidebar widgets ?>
			
			<li id="pages">
				<ul>
					<?php wp_list_pages('title_li=&'); ?>  
				</ul>

				</ul>
			</li>
<?php endif; // end primary sidebar widgets  ?>
		</ul>
	</div><!-- #primary .sidebar -->

	<div id="secondary" class="sidebar">
		<ul class="xoxo">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(2) ) : // begin secondary sidebar widgets ?>

			<li id="latest-posts">
				<h3><?php _e( 'Latest Posts', 'sandbox' ) ?></h3>
				 <ul>
					 <?php
					 global $post;
					 $myposts = get_posts('numberposts=5');
					 foreach($myposts as $post) :
					 ?>
					<li><a href="<?php the_permalink(); ?>"><?php echo get_avatar( get_the_author_email(), '21' ); ?><span class="side-title"><?php the_title(); ?> <span class="com-count">(<?php comments_number('%', '%', '%'); ?>)</span></span></a></li>
					 <?php endforeach; ?>
				 </ul>
			</li>

			<li id="authors">
				<h3>Authors</h3>
				<ul>
				<?php wp_list_authors('exclude_admin=0&optioncount=1'); ?>
				</ul>
			</li>

<?php endif; // end secondary sidebar widgets  ?>
		</ul>
	</div><!-- #secondary .sidebar -->
	
	<div id="thirdly" class="sidebar">
		<ul class="xoxo">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(3) ) : // begin secondary sidebar widgets ?>

<?php endif; // end secondary sidebar widgets  ?>
		</ul>
	</div><!-- #secondary .sidebar -->
</div>