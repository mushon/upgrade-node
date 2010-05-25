<?php

//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args');



// Removing and adding thematic sidebars
function childtheme_sidebars_init() {

// Register the New Footer Sidebar
register_sidebar(array(
    
// Title for the Widget Dashboard
'name' => 'New Footer Sidebar',

// ID for the XHTML Markup
'id' => 'new-footer-sidebar',

// Description for the Widget Dashboard Box
'description' => __('This widget area shows up above the footer.', 'thematic'),

// Do not edit these. It keeps Headers and lists consistent for Thematic
'before_widget' => thematic_before_widget(),
'after_widget' => thematic_after_widget(),
'before_title' => thematic_before_title(),
'after_title' => thematic_after_title(),
));
 
// Unregister and sidebars you donÕt need based on its ID.
// For a full list of Thematic sidebar IDs, look at /thematc/library/extensions/widgets-extensions.php
unregister_sidebar('index-top');
unregister_sidebar('index-insert');
unregister_sidebar('index-bottom');
unregister_sidebar('single-top');
unregister_sidebar('single-insert');
unregister_sidebar('single-bottom');
unregister_sidebar('page-top');
unregister_sidebar('page-bottom');
}

// When WP initiates, add the above settings
add_action( 'init', 'childtheme_sidebars_init',20 );


// Determine if the language requires RTL settings (using the qTranslate plugin):
function lang_dir() {
	if ( function_exists('qtrans_getLanguage')) {
		$lang = strtolower(qtrans_getLanguage());
		if ($lang == "ar" || $lang == "fa" || $lang == "iw" || $lang == "ks" || $lang == "ps" || $lang == "sd" || $lang == "ur" || $lang == "yi"){
			echo "dir-rtl lang-".$lang;
		} else {
			echo "lang-".$lang;
		}
	}
}

// Creates the links for the translations of the post. use either 'text', 'image', 'both' or 'dropdown':
function lang_links($id){
	if ( function_exists('qtrans_getLanguage') ) {
		qtrans_generateLanguageSelectCode('text', $id);
	}
}

// Address categories by name to check if a certain category has a certain slug
function in_category_name($name) {
	foreach (get_the_category() as $cat) {
		if ($cat->category_nicename == $name) {
			return true;
		}
	}
	return false;
}

// Gets the event time (using the Event Calendar plugin):
function ec3_schedule(){
	if ( function_exists('ec3_get_schedule')) {
		echo ec3_get_schedule('%s; ','%1$s %3$s %2$s. ','%s');
	} else {
		$event_time = get_the_time('l, F jS, Y') ." at ". get_the_time('G:i');
		echo $event_time;
	}
}

// Add search to header
function add_search(){
include (TEMPLATEPATH . '/searchform.php');
}
add_action('thematic_header','add_search');

// Change search box text
function childtheme_search_value() {
return " ";
}
add_filter('search_field_value', 'childtheme_search_value');


/* Front Page Loop */
// Get rid of thematic index loop on the main page
function remove_index_loop() {
    remove_action('thematic_indexloop', 'thematic_index_loop');
}
add_action('init', 'remove_index_loop');

// Filtering the thematic index loop
function upgrade_index_loop(){
       while ( have_posts() ) : the_post()  // Start the loop:
    // This is just what we decide to show in each post ?>
           <div class="post">
                <div class="post-content span-12">
		<?php lang_links($post->ID)?>
		<span class="cat-links"><?php printf( __( '%s', 'sandbox' ), get_the_category_list(' ') ) ?></span>
		<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class='edit-link'>", "</span>" ) ?>
		<div class="heading"><h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2></div>
		<?php if (in_category_name('events')){?>						
		<span class="event-time">
		    <!-- Using the Event Calendar plugin's template tag:  -->
		    <?php ec3_schedule() ?>
		    <!-- Using the default event date:
		    <?php the_time('l, F jS, Y'); ?> at <?php the_time('G:i'); ?> -->
		</span><br>
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
					<?php echo get_avatar( get_the_author_email(), '40' ); ?>
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
    <?php
    endwhile; // loop done, go back up 
}
// And in the end activate the new loop.
add_filter('thematic_indexloop', 'upgrade_index_loop');


/* Archive Page Loop */
// Remove archive loop
function remove_the_loop() {
    remove_action('thematic_archiveloop', 'thematic_archive_loop');
}
add_action('init', 'remove_the_loop');

function the_archive_loop() {
       while ( have_posts() ) : the_post()  // Start the loop:
    // This is just what we decide to show in each post ?>
           <div class="post">
                <div class="post-content span-12">
		<?php lang_links($post->ID)?>
		<span class="cat-links"><?php printf( __( '%s', 'sandbox' ), get_the_category_list(' ') ) ?></span>
		<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class='edit-link'>", "</span>" ) ?>
		<div class="heading"><h2 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php printf( __('Permalink to %s', 'sandbox'), the_title_attribute('echo=0') ) ?>" rel="bookmark"><?php the_title() ?></a></h2></div>
		<?php if (in_category_name('events')){?>						
		<span class="event-time">
		    <!-- Using the Event Calendar plugin's template tag:  -->
		    <?php ec3_schedule() ?>
		    <!-- Using the default event date:
		    <?php the_time('l, F jS, Y'); ?> at <?php the_time('G:i'); ?> -->
		</span><br>
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
					<?php echo get_avatar( get_the_author_email(), '40' ); ?>
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
    <?php
    endwhile; // loop done, go back up 
}
add_action('thematic_archiveloop', 'the_archive_loop');


/* Single Post Page Loop */

// Filtering the single page thematic loop
function upgrade_singlepost_loop(){
    if ( is_single()) {
       while ( have_posts() ) : the_post() ?>
            <div id="container">
	    <div id="content">

			<div id="nav-above" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> <span class="title-nav">%title</span>' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '<span class="title-nav">%title</span> <span class="meta-nav">&rarr;</span>' ) ?></div>
			</div>
                            <div class="post">
                                    <div class="post-content span-12">
					<?php lang_links($post->ID)?>
					<span class="cat-links"><?php printf( __( '%s', 'sandbox' ), get_the_category_list(' ') ) ?></span>
					<?php edit_post_link( __( 'Edit', 'sandbox' ), "<span class='edit-link'>", "</span>" ) ?>
					<div class="heading"><h2 class="entry-title"><?php the_title() ?></h2></div>
					<?php if (in_category_name('events')){?>						
						<span class="event-time">
							<!-- Using the Event Calendar plugin's template tag: -->
							<?php ec3_schedule() ?>
							<!-- Using the default event date:
							<?php the_time('l, F jS, Y'); ?> at <?php the_time('G:i'); ?> -->
						</span><br>
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
					<span class="comments-link"><a href="<?php comments_link?>"><?php comments_number('', '1', '%');?></a></span>
					<a href="#" class="btn-down"></a>

				</div>
			</div><!-- .post -->

			<div id="nav-below" class="navigation">
				<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> <span class="title-nav">%title</span>' ) ?></div>
				<div class="nav-next"><?php next_post_link( '%link', '<span class="title-nav">%title</span> <span class="meta-nav">&rarr;</span>' ) ?></div>
			</div>

<?php comments_template() ?>

		</div><!-- #content -->
		</div>
	</div><!-- #container -->
    <?php
    endwhile; // loop done, go back up 
    }
}
// And in the end activate the new loop.
add_action('thematic_abovecontainer', 'upgrade_singlepost_loop');


/* Dashboard Node Settings */
// Additional, node admin page for the upgrades template

add_action('admin_menu', 'upgrades_add_theme_page');

function upgrades_add_theme_page() {
	if ( $_GET['page'] == basename(__FILE__) ) {
		
		if ( 'save' == $_REQUEST['action'] ) {
			if ( isset($_REQUEST['njform']) ) {
			
					if (empty($_REQUEST['upgrades_use_gmap']))
						delete_option('upgrades_use_gmap');
					else {
						update_option('upgrades_use_gmap', "true");
					}
			
					if ( '' == $_REQUEST['upgrades_node_name'] )
						delete_option('upgrades_node_name');
					else {
						$upgrades_node_name = trim ( $_REQUEST['upgrades_node_name'] );
						update_option('upgrades_node_name', $upgrades_node_name);
					}
					
					if ( '' == $_REQUEST['node_google_key'] )
						delete_option('node_google_key');
					else {
						$node_google_key = trim ( $_REQUEST['node_google_key'] );
						update_option('node_google_key', $node_google_key);
					}
					
					if ( '' == $_REQUEST['node_color_light'] )
						delete_option('node_color_light');
					else {
						$node_color_light = trim ( $_REQUEST['node_color_light'] );
						update_option('node_color_light', $node_color_light);
					}
				
				 	if ( '' == $_REQUEST['node_color_dark'] )
						delete_option('node_color_dark');
					else {
						$node_color_dark = trim ( $_REQUEST['node_color_dark'] );
						update_option('node_color_dark', $node_color_dark);
					}
				
				 	if ( '' == $_REQUEST['node_color_text'] )
						delete_option('node_color_text');
					else {
						$node_color_text = trim ( $_REQUEST['node_color_text'] );
						update_option('node_color_text', $node_color_text);
					}

					if ( '' == $_REQUEST['upgrades_node_address'] )
						delete_option('upgrades_node_address');
					else {
						$upgrades_node_address = trim ( $_REQUEST['upgrades_node_address'] );
						update_option('upgrades_node_address', $upgrades_node_address);
					}
					
			  		if ( '' == $_REQUEST['upgrades_node_lat'] )
						delete_option('upgrades_node_lat');
					else {
						$upgrades_node_lat = trim ( $_REQUEST['upgrades_node_lat'] );
						update_option('upgrades_node_lat', $upgrades_node_lat);
					}

					if ( '' == $_REQUEST['upgrades_node_lon'] )
						delete_option('upgrades_node_lon');
					else {
						$upgrades_node_lon = trim ( $_REQUEST['upgrades_node_lon'] );
						update_option('upgrades_node_lon', $upgrades_node_lon);
					}

					if ( '' == $_REQUEST['upgrades_node_zoom'] )
						delete_option('upgrades_node_zoom');
					else {
						$upgrades_node_zoom = trim ( $_REQUEST['upgrades_node_zoom'] );
						update_option('upgrades_node_zoom', $upgrades_node_zoom);
					}
			}

			//print_r($_REQUEST);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		} 
		 
		add_action('admin_head', 'upgrades_theme_node_meta');
	}
	add_theme_page(__('Node Settings'), __('Node Settings'), 'edit_themes', basename(__FILE__), 'upgrades_theme_page');
}

// head of the node's settings page

function upgrades_theme_node_meta() {
?>
<script type='text/javascript'>
// <![CDATA[
	function lalal() {
	}
// ]]>
</script>
<style type='text/css'>
	#headwrap {
		text-align: center;
	}
</style>
<?php
}

// body of the node's setting page:


function upgrades_theme_page() {
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
?>
<div class='wrap'>
	<h2><?php _e('Customize settings for your node'); ?></h2>
	
	
	<div id="nonJsForm">
			<form method="post" action="">				
				<?php wp_nonce_field('node_settings'); ?>
				
				<h3>General details</h3>

				<table class="form-table">
				
					<tr valign="top">
					<th scope="row"><label for="upgrades_node_name"><?php _e('Upgrade node codename:');?></label></th>
					<td><input name="upgrades_node_name" type="text" id="upgrades_node_name" 
						value="<?php echo attribute_escape(upgrades_node_name()); ?>" size="64" />
					<br /><span>This is used for identifying the different feeds. 
					For example: &#214;stersund-Stockholm could be simply 'stockholm' four these purposes </span>
					</td>
					</tr>
					
					
					<tr valign="top">
					<th scope="row"><label for="upgrades_use_gmap"><?php _e('Use Google Map:');?></label></th>
					<td>
					
					<input type="checkbox" name="upgrades_use_gmap" id="upgrades_use_gmap" value="1"
					<?php print (get_option('upgrades_use_gmap')) ? "checked=\"checked\"" : null; ?> />
					<br /><span>If checked, the Upgrade! theme will use a Google Map in the header.  
					If unchecked, the theme will use an image from the most recent post.</span>
					
					</td>
					</tr>
					
					<!-- 
					This will be taken directly from the WP-GEO plugin
					<tr valign="top">
					<th scope="row"><label for="node_google_key"><?php _e('Google Map API Key:');?></label></th>
					<td><input name="node_google_key" type="text" id="node_google_key" 
						value="<?php //echo attribute_escape(node_google_key()); ?>" size="64" />
					</td>
					</tr>
					 -->
				</table>
				
				<h3>Node Ribbon Colors</h3>

				<table class="form-table">
				
					<tr valign="top">
					<th scope="row"><label for="node_color_light"><?php _e('Node color light:');?></label></th>
					<td>#<input name="node_color_light" type="text" id="node_color_light" 
						value="<?php echo attribute_escape(node_color_light()); ?>" size="6" />
					</td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><label for="node_color_dark"><?php _e('Node color dark:');?></label></th>
					<td>#<input name="node_color_dark" type="text" id="node_color_dark" 
						value="<?php echo attribute_escape(node_color_dark()); ?>" size="6" />
						<br />No need to specify the gray color which will be added to each ribbon
					</td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><label for="node_color_text"><?php _e('Node color link:');?></label></th>
					<td>#<input name="node_color_text" type="text" id="node_color_text" 
						value="<?php echo attribute_escape(node_color_text()); ?>" size="6" />
						<br />This will be used for text links and should be contrasted enough from your other two tones (a 30% darker tone based on your dark ribbon colors usually works)
					</td>
					</tr>

				</table>
				
				
				<h3>Default Location</h3>

				<table class="form-table">
				
					<tr valign="top">
					<th scope="row"><label for="upgrades_node_address"><?php _e('Address:');?></label></th>
					<td><input name="upgrades_node_address" type="text" id="upgrades_node_address" 
						value="<?php echo attribute_escape(upgrades_node_address()); ?>" size="64" />
						<p>Enter your address and your latitude and longitude will be calculated automatically.</p>
					</td>
					</tr>
					
					<tr valign="top">
					<th scope="row"><label for="Geo_location"><?php _e('Geo location:');?></label></th>
					<td>Latitude:<input name="upgrades_node_lat" type="text" id="upgrades_node_lat" 
						value="<?php echo attribute_escape(upgrades_node_lat()); ?>" size="32" />
					
					Longitude:<input name="upgrades_node_lon" type="text" id="upgrades_node_lon" 
						value="<?php echo attribute_escape(upgrades_node_lon()); ?>" size="32" />

					Zoom:<input name="upgrades_node_zoom" type="text" id="upgrades_node_zoom" 
						value="<?php echo attribute_escape(upgrades_node_zoom()); ?>" size="32" />

					<br />
					   Use Google Maps to find the lat/lon of your location. 
					   <a href="http://www.getlatlon.com/" target="_blank">More tips here</a>
					</td>
					</tr>

				</table>


				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="njform" value="true" />

				<br />
				<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Settings'); ?>" /></p>
				
			</form>
		</div>

</div>
<?php }

//embed styles in header:
function nodeStyles(){
	echo "
	
	a,
	a:hover,
	.meta a:hover,
	.edit-link a:hover  {
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#container div.entry-content a.more-link:hover{
		background-color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#stripe{
		border-top-color: #" . attribute_escape(node_color_light()) . ";
		background-color: #" . attribute_escape(node_color_dark()) . " ;
	}
	
	div#right-col h2.page-title{
		background:#" . attribute_escape(node_color_light()) . ";
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#right-col h2.page-title a{
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#container div.post span.event-loc{
		background: #" . attribute_escape(node_color_light()) . ";
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#container div.post span.event-time{
		background: #" . attribute_escape(node_color_dark()) . " ;
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div#container div.entry-content a.more-link{
		background: #" . attribute_escape(node_color_dark()) . " ;
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div.entry-meta{
		background: #" . attribute_escape(node_color_dark()) . " ;
		color: #" . attribute_escape(node_color_text()) . ";
	}
	
	div.entry-meta span.comments-link a{
		color: #" . attribute_escape(node_color_dark()) . " ;
	}
	
	div.entry-meta a.author-img:hover img {
		border: 2px solid #" . attribute_escape(node_color_dark()) . " ;
	}
	
	div.entry-meta a.author-img img{
		border-color: 2px solid #" . attribute_escape(node_color_text()) . ";
	}
	
	.comment-author{
		background: #" . attribute_escape(node_color_light()) . ";
	}
	
	div#wp-calendar table td.ec3_eventday{
		border-color: #" . attribute_escape(node_color_light()) . ";
	}
	
	div#wp-calendar table td.ec3_eventday a{
		color: #" . attribute_escape(node_color_text()) . "!important;
		border-color: #" . attribute_escape(node_color_dark()) . "; 
	}
	
	";
}


///  keys

/*
function node_google_key() {
	return apply_filters('node_google_key', get_option('node_google_key'));
}
*/

function upgrades_node_name() {
	if(!get_option('upgrades_node_name')) {
		add_option('upgrades_node_name', 'Defualt Node Name');
	}
	return apply_filters('upgrades_node_name', get_option('upgrades_node_name'));
}

function node_color_light() {
	if(!get_option('node_color_light')) {
		add_option('node_color_light', 'CCCCCC');
	}
	return apply_filters('node_color_light', get_option('node_color_light'));
}

function node_color_dark() {
	if(!get_option('node_color_dark')) {
		add_option('node_color_dark', '999999');
	}
	return apply_filters('node_color_dark', get_option('node_color_dark'));
}

function node_color_text() {
	if(!get_option('node_color_text')) {
		add_option('node_color_text', '555555');
	}
	return apply_filters('node_color_text', get_option('node_color_text'));
}

function upgrades_node_address() {
	if(!get_option('upgrades_node_address')) {
		add_option('upgrades_node_address', 'Put your address here');
	}
	return apply_filters('upgrades_node_address', get_option('upgrades_node_address'));
}

function upgrades_node_lon() {
	if(!get_option('upgrades_node_lon')) {
		geolocate();
	}
	return apply_filters('upgrades_node_lon', get_option('upgrades_node_lon'));
}

function upgrades_node_lat() {
	if(!get_option('upgrades_node_lat')) {
		geolocate();
	}
	return apply_filters('upgrades_node_lat', get_option('upgrades_node_lat'));
}

function upgrades_node_zoom() {
	if(!get_option('upgrades_node_zoom')) {
		geolocate();
	}
	return apply_filters('upgrades_node_zoom', get_option('upgrades_node_zoom'));
}

function geolocate()
{
	if(!($a = urlencode(upgrades_node_address())))
	{
		return false;
	}
	$wpgeo_options = get_option("wp_geo_options");
	$key = $wpgeo_options['google_api_key'];
	$url = "http://maps.google.com/maps/geo?q=$a&output=csv&key=$key";
	if(function_exists("curl_init"))
	{
	    $cinit = curl_init();  
		curl_setopt($cinit, CURLOPT_URL, $url);  
		curl_setopt($cinit, CURLOPT_HEADER,0);  
		curl_setopt($cinit, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);  
		curl_setopt($cinit, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($cinit, CURLOPT_RETURNTRANSFER, 1);  
		$response = curl_exec($cinit);  
		curl_close($cinit);
	}
	else if(function_exists("file_get_contents"))
	{
		$response = file_get_contents($url);
	}
	if(strstr($response,"200"))
	{  
		$data = explode(",",$response);  
		if(!get_option('upgrades_node_lat')) {
			add_option('upgrades_node_lat', $data[2]);
		}
		if(!get_option('upgrades_node_lon')) {
			add_option('upgrades_node_lon', $data[3]);
		}
		if(!get_option('upgrades_node_zoom')) {
			add_option('upgrades_node_zoom', $data[1]);
		}
	}
}


function feed_insert_node_info()
{
	print "<upgrade:nodeName>".upgrades_node_name()."</upgrade:nodeName>\n";
	print "<upgrade:nodeUrl>".bloginfo('home')."</upgrade:nodeUrl>\n";
	print "<upgrade:nodeAddress>".upgrades_node_address()."</upgrade:nodeAddress>\n";
	print "<upgrade:nodeColorLight>#".node_color_light()."</upgrade:nodeColorLight>\n";
	print "<upgrade:nodeColorDark>#".node_color_dark()."</upgrade:nodeColorDark>\n";
	print "<upgrade:nodeColorText>#".node_color_text()."</upgrade:nodeColorText>\n";
	print "<upgrade:nodeThemeVersion>".$THEME_VERSION."</upgrade:nodeThemeVersion>\n";
	
}


function feed_insert_namespace()
{
	print "\n\txmlns:upgrade=\"http://upgrade.eyebeam.org/upgrade\"";
}

function upgrade_event_form()
{
	global $post;
	$loc = get_post_meta($post->ID, "event_loc", true);
	if(empty($loc))
	{
		$loc = upgrades_node_address();
	}
	$edit_html = '
		<div class="postbox if-js-open">
			<h3>' . __('Upgrade Event Location') . '</h3>
			<div class="inside">
				<input style="width: 100%;" type="text" name="event_loc" value="'.$loc.'" />
			</div>
		</div>';
	print $edit_html;
}

function upgrade_save_post($post_id)
{
	if (isset($_POST['event_loc']))
	{
		// Only delete post meta if isset (to avoid deletion in bulk/quick edit mode)
		delete_post_meta($post_id, 'event_loc');
		add_post_meta($post_id, 'event_loc', $_POST['event_loc']);	
	}
	
}

add_action('rss_head', 'feed_insert_node_info');
add_action('rss2_head', 'feed_insert_node_info');
add_action('atom_head', 'feed_insert_node_info');
add_action('rdf_header', 'feed_insert_node_info');
add_action('atom_ns', 'feed_insert_namespace');
add_action('rdf_ns', 'feed_insert_namespace');
add_action('rss2_ns', 'feed_insert_namespace');
add_action('edit_form_advanced', 'upgrade_event_form');
add_action('save_post', 'upgrade_save_post');


// The netowrk feed is parsed below in widget_netfeed
// This array will hold the RSS items that have georss info 
// so that the Google Map can add them in the theme.
$netFeed = array();


function parse_net_feed() {
	global $netFeed;
	//define('MAGPIE_CACHE_DIR', '/tmp/magpie_cache');
	require_once(dirname(__FILE__).'/../../../wp-includes/rss.php');
	$url = "http://theupgrade.net/feeds.xml";
	$rss = fetch_rss($url);
	$netFeed = $rss->items;
}
parse_net_feed();

function get_net_feed() {
	global $netFeed;
	return $netFeed;
}

// This function prints the sidebar widget--the cool stuff!
function widget_netfeed($args) {

	global $netFeed;

    // $args is an array of strings which help your widget
    // conform to the active theme: before_widget, before_title,
    // after_widget, and after_title are the array keys.
    extract($args);

    // Collect our widget's options, or define their defaults.
    $options = get_option('widget_netfeed');
    $title = empty($options['title']) ? 'Latest Global Events' : $options['title'];

     // It's important to use the $before_widget, $before_title,
     // $after_title and $after_widget variables in your output.
    echo $before_widget;
    echo $before_title . $title . $after_title;
  	echo "<ul>";
   	//echo "Channel Title: " . $rss->channel['title'] . "<p>";
	foreach ($netFeed as $item):
	?>
		<li>
		<div class="net_wrap" style="border-color:<?=$item['nodecolordark']?>;"></span><a href="<?=$item['link']?>" title="<?=$item['summary']?>" target="blank" style="color:<?=$item['nodecolortext']?>; border-color: 2px solid <?=$item['nodecolorlight']?>">
		<span><!--<? echo date("M dS",$item['date_timestamp'])?>--> U! <?=$item['nodename']?>:</span><br />
		<?=$item['title']?></a></div>
		</li>
	<?php endforeach;
	echo "</ul>";
    echo $after_widget;
}

// This is the function that outputs the form to let users edit
// the widget's title and so on. It's an optional feature, but
// we'll use it because we can!
function widget_netfeed_control() {

    // Collect our widget's options.
    $options = get_option('widget_netfeed');

    // This is for handing the control form submission.
    if ( $_POST['netfeed-submit'] ) {
        // Clean up control form submission options
        $newoptions['title'] = strip_tags(stripslashes($_POST['netfeed-title']));
    }

    // If original widget options do not match control form
    // submission options, update them.
    if ( $options != $newoptions ) {
        $options = $newoptions;
        update_option('widget_netfeed', $options);
    }

    // Format options as valid HTML. Hey, why not.
    $title = htmlspecialchars($options['title'], ENT_QUOTES);

	// The HTML below is the control form for editing options.
	?>
	    <div>
	    <label for="netfeed-title" style="line-height:35px;display:block;">Widget title: <input type="text" id="netfeed-title" name="netfeed-title" value="<?php echo $title; ?>" /></label>
	    <input type="hidden" name="netfeed-submit" id="netfeed-submit" value="1" />
	    </div>
	<?php
	// end of widget_netfeed_control()
}

// This registers the widget. About time.
register_sidebar_widget('Upgrade! Network Feed', 'widget_netfeed');

// This registers the (optional!) widget control form.
register_widget_control('Upgrade! Network Feed', 'widget_netfeed_control');


?>