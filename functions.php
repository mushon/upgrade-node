<?php
/*
This file is part of SANDBOX.

SANDBOX is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

SANDBOX is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with SANDBOX. If not, see http://www.gnu.org/licenses/.
*/

$THEME_VERSION = "2009.05.26";


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

//get the post thumbnail:
function get_thumb ($post_ID){
	$thumbargs = array(
	'post_type' => 'attachment',
	'numberposts' => 1,
	'post_status' => null,
	'post_parent' => $post_ID
	);
	$thumbs = get_posts($thumbargs);
	if ($thumbs) {
		return get_attachment_icon($thumbs[0]->ID);
	}
}

// Produces a list of pages in the header without whitespace
function sandbox_globalnav() {
	if ( $menu = str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages('title_li=&sort_column=menu_order&echo=0') ) )
		$menu = '<ul>' . $menu . '</ul>';
	$menu = '<div id="menu">' . $menu . "</div>\n";
	echo apply_filters( 'globalnav_menu', $menu ); // Filter to override default globalnav: globalnav_menu
}

// Generates semantic classes for BODY element
function sandbox_body_class( $print = true ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$c = array('wordpress');

	// Applies the time- and date-based classes (below) to BODY element
	sandbox_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()  ? $c[] = 'home'       : null; // For the front page, if set
	is_home()        ? $c[] = 'blog'       : null; // For the blog posts page, if set
	is_archive()     ? $c[] = 'archive'    : null;
	is_date()        ? $c[] = 'date'       : null;
	is_search()      ? $c[] = 'search'     : null;
	is_paged()       ? $c[] = 'paged'      : null;
	is_attachment()  ? $c[] = 'attachment' : null;
	is_404()         ? $c[] = 'four04'     : null; // CSS does not allow a digit as first character

	// Special classes for BODY element when a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			sandbox_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
	}

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		if ( is_page_template() ) // Hat tip to Ian, themeshaper.com
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( ( ( $page = $wp_query->get('paged') ) || ( $page = $wp_query->get('page') ) ) && $page > 1 ) {
		$c[] = 'paged-' . $page;
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;
		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}

	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class',  $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Generates semantic classes for each post DIV element
function sandbox_post_class( $print = true ) {
	global $post, $sandbox_post_alt;

	// hentry for hAtom compliace, gets 'alt' for every other post DIV, describes the post type and p[n]
	$c = array( 'hentry', "p$sandbox_post_alt", $post->post_type, $post->post_status );

	// Author for the post queried
	$c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));

	// Category for the post queried
	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	// Tags for the post queried; if not tagged, use .untagged
	if ( get_the_tags() == null ) {
		$c[] = 'untagged';
	} else {
		foreach ( (array) get_the_tags() as $tag )
			$c[] = 'tag-' . $tag->slug;
	}

	// For password-protected posts
	if ( $post->post_password )
		$c[] = 'protected';

	// Applies the time- and date-based classes (below) to post DIV
	sandbox_date_classes( mysql2date( 'U', $post->post_date ), $c );

	// If it's the other to the every, then add 'alt' class
	if ( ++$sandbox_post_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for post DIV
	$c = join( ' ', apply_filters( 'post_class', $c ) ); // Available filter: post_class

	// And tada!
	return $print ? print($c) : $c;
}

// Define the num val for 'alt' classes (in post DIV and comment LI)
$sandbox_post_alt = 1;

// Generates semantic classes for each comment LI element
function sandbox_comment_class( $print = true ) {
	global $comment, $post, $sandbox_comment_alt;

	// Collects the comment type (comment, trackback),
	$c = array( $comment->comment_type );

	// Counts trackbacks (t[n]) or comments (c[n])
	if ( $comment->comment_type == 'comment' ) {
		$c[] = "c$sandbox_comment_alt";
	} else {
		$c[] = "t$sandbox_comment_alt";
	}

	// If the comment author has an id (registered), then print the log in name
	if ( $comment->user_id > 0 ) {
		$user = get_userdata($comment->user_id);
		// For all registered users, 'byuser'; to specificy the registered user, 'commentauthor+[log in name]'
		$c[] = 'byuser comment-author-' . sanitize_title_with_dashes(strtolower( $user->user_login ));
		// For comment authors who are the author of the post
		if ( $comment->user_id === $post->post_author )
			$c[] = 'bypostauthor';
	}

	// If it's the other to the every, then add 'alt' class; collects time- and date-based classes
	sandbox_date_classes( mysql2date( 'U', $comment->comment_date ), $c, 'c-' );
	if ( ++$sandbox_comment_alt % 2 )
		$c[] = 'alt';

	// Separates classes with a single space, collates classes for comment LI
	$c = join( ' ', apply_filters( 'comment_class', $c ) ); // Available filter: comment_class

	// Tada again!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function sandbox_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// For category lists on category archives: Returns other categories except the current one (redundant)
function sandbox_cats_meow($glue) {
	$current_cat = single_cat_title( '', false );
	$separator = "\n";
	$cats = explode( $separator, get_the_category_list($separator) );
	foreach ( $cats as $i => $str ) {
		if ( strstr( $str, ">$current_cat<" ) ) {
			unset($cats[$i]);
			break;
		}
	}
	if ( empty($cats) )
		return false;

	return trim(join( $glue, $cats ));
}

// For tag lists on tag archives: Returns other tags except the current one (redundant)
function sandbox_tag_ur_it($glue) {
	$current_tag = single_tag_title( '', '',  false );
	$separator = "\n";
	$tags = explode( $separator, get_the_tag_list( "", "$separator", "" ) );
	foreach ( $tags as $i => $str ) {
		if ( strstr( $str, ">$current_tag<" ) ) {
			unset($tags[$i]);
			break;
		}
	}
	if ( empty($tags) )
		return false;

	return trim(join( $glue, $tags ));
}

// Produces an avatar image with the hCard-compliant photo class
function sandbox_commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar_size = apply_filters( 'avatar_size', '20' ); // Available filter: avatar_size
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, $avatar_size ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}

// Function to filter the default gallery shortcode
function sandbox_gallery($attr) {
	global $post;
	if ( isset($attr['orderby']) ) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if ( !$attr['orderby'] )
			unset($attr['orderby']);
	}

	extract(shortcode_atts( array(
		'orderby'    => 'menu_order ASC, ID ASC',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr ));

	$id           =  intval($id);
	$orderby      =  addslashes($orderby);
	$attachments  =  get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

	if ( empty($attachments) )
		return null;

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link( $id, $size, true ) . "\n";
		return $output;
	}

	$listtag     =  tag_escape($listtag);
	$itemtag     =  tag_escape($itemtag);
	$captiontag  =  tag_escape($captiontag);
	$columns     =  intval($columns);
	$itemwidth   =  $columns > 0 ? floor(100/$columns) : 100;

	$output = apply_filters( 'gallery_style', "\n" . '<div class="gallery">', 9 ); // Available filter: gallery_style

	foreach ( $attachments as $id => $attachment ) {
		$img_lnk = get_attachment_link($id);
		$img_src = wp_get_attachment_image_src( $id, $size );
		$img_src = $img_src[0];
		$img_alt = $attachment->post_excerpt;
		if ( $img_alt == null )
			$img_alt = $attachment->post_title;
		$img_rel = apply_filters( 'gallery_img_rel', 'attachment' ); // Available filter: gallery_img_rel
		$img_class = apply_filters( 'gallery_img_class', 'gallery-image' ); // Available filter: gallery_img_class

		$output  .=  "\n\t" . '<' . $itemtag . ' class="gallery-item gallery-columns-' . $columns .'">';
		$output  .=  "\n\t\t" . '<' . $icontag . ' class="gallery-icon"><a href="' . $img_lnk . '" title="' . $img_alt . '" rel="' . $img_rel . '"><img src="' . $img_src . '" alt="' . $img_alt . '" class="' . $img_class . ' attachment-' . $size . '" /></a></' . $icontag . '>';

		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "\n\t\t" . '<' . $captiontag . ' class="gallery-caption">' . $attachment->post_excerpt . '</' . $captiontag . '>';
		}

		$output .= "\n\t" . '</' . $itemtag . '>';
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= "\n</div>\n" . '<div class="gallery">';
	}
	$output .= "\n</div>\n";

	return $output;
}

// Widget: Search; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_search($args) {
	extract($args);
	$options = get_option('widget_sandbox_search');
	$title = empty($options['title']) ? __( 'Search', 'sandbox' ) : attribute_escape($options['title']);
	$button = empty($options['button']) ? __( 'Find', 'sandbox' ) : attribute_escape($options['button']);
?>
			<?php echo $before_widget ?>
				<?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
				<form id="searchform" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="10" tabindex="1" />
						<input type="submit" class="button" value="<?php echo $button ?>" tabindex="2" />
					</div>
				</form>
			<?php echo $after_widget ?>
<?php
}

// Widget: Search; element controls for customizing text within Widget plugin
function widget_sandbox_search_control() {
	$options = $newoptions = get_option('widget_sandbox_search');
	if ( $_POST['search-submit'] ) {
		$newoptions['title'] = strip_tags(stripslashes( $_POST['search-title']));
		$newoptions['button'] = strip_tags(stripslashes( $_POST['search-button']));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_sandbox_search', $options );
	}
	$title = attribute_escape($options['title']);
	$button = attribute_escape($options['button']);
?>
	<p><label for="search-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="search-title" name="search-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<p><label for="search-button"><?php _e( 'Button Text:', 'sandbox' ) ?> <input class="widefat" id="search-button" name="search-button" type="text" value="<?php echo $button; ?>" /></label></p>
	<input type="hidden" id="search-submit" name="search-submit" value="1" />
<?php
}

// Widget: Meta; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_meta($args) {
	extract($args);
	$options = get_option('widget_meta');
	$title = empty($options['title']) ? __( 'Meta', 'sandbox' ) : attribute_escape($options['title']);
?>
			<?php echo $before_widget; ?>
				<?php echo $before_title . $title . $after_title; ?>
				<ul>
					<?php wp_register() ?>

					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>

				</ul>
			<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; to match the Sandbox style
function widget_sandbox_rsslinks($args) {
	extract($args);
	$options = get_option('widget_sandbox_rsslinks');
	$title = empty($options['title']) ? __( 'RSS Links', 'sandbox' ) : attribute_escape($options['title']);
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?> <?php _e( 'Posts RSS feed', 'sandbox' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All posts', 'sandbox' ) ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e( 'Comments RSS feed', 'sandbox' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All comments', 'sandbox' ) ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; element controls for customizing text within Widget plugin
function widget_sandbox_rsslinks_control() {
	$options = $newoptions = get_option('widget_sandbox_rsslinks');
	if ( $_POST['rsslinks-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['rsslinks-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_sandbox_rsslinks', $options );
	}
	$title = attribute_escape($options['title']);
?>
	<p><label for="rsslinks-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
	<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function sandbox_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;

	// Formats the Sandbox widgets, adding readability-improving whitespace
	$p = array(
		'before_widget'  =>   "\n\t\t\t" . '<li id="%1$s" class="widget %2$s">',
		'after_widget'   =>   "\n\t\t\t</li>\n",
		'before_title'   =>   "\n\t\t\t\t". '<h3 class="widgettitle">',
		'after_title'    =>   "</h3>\n"
	);

	// Table for how many? Two? This way, please.
	register_sidebars( 3, $p );

	// Finished intializing Widgets plugin, now let's load the Sandbox default widgets; first, Sandbox search widget
	$widget_ops = array(
		'classname'    =>  'widget_search',
		'description'  =>  __( "A search form for your blog (Sandbox)", "sandbox" )
	);
	wp_register_sidebar_widget( 'search', __( 'Search', 'sandbox' ), 'widget_sandbox_search', $widget_ops );
	unregister_widget_control('search'); // We're being Sandbox-specific; remove WP default
	wp_register_widget_control( 'search', __( 'Search', 'sandbox' ), 'widget_sandbox_search_control' );

	// Sandbox Meta widget
	$widget_ops = array(
		'classname'    =>  'widget_meta',
		'description'  =>  __( "Log in/out and administration links (Sandbox)", "sandbox" )
	);
	wp_register_sidebar_widget( 'meta', __( 'Meta', 'sandbox' ), 'widget_sandbox_meta', $widget_ops );
	unregister_widget_control('meta'); // We're being Sandbox-specific; remove WP default
	wp_register_widget_control( 'meta', __( 'Meta', 'sandbox' ), 'wp_widget_meta_control' );

	//Sandbox RSS Links widget
	$widget_ops = array(
		'classname'    =>  'widget_rss_links',
		'description'  =>  __( "RSS links for both posts and comments (Sandbox)", "sandbox" )
	);
	wp_register_sidebar_widget( 'rss_links', __( 'RSS Links', 'sandbox' ), 'widget_sandbox_rsslinks', $widget_ops );
	wp_register_widget_control( 'rss_links', __( 'RSS Links', 'sandbox' ), 'widget_sandbox_rsslinks_control' );
}

// Translate, if applicable
load_theme_textdomain('sandbox');

// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'sandbox_widgets_init' );

// Registers our function to filter default gallery shortcode
add_filter( 'post_gallery', 'sandbox_gallery', $attr );

// Adds filters for the description/meta content in archives.php
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );


/**********************************************************************/
/* additional, node admin page for the upgrades template */

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

function add_custom_boxes()
{
	add_meta_box('upgrade_location', __('Upgrade Location', 'upgrade'), 'upgrade_location_inner_custom_box', 'post', 'advanced');
}

function upgrade_location_inner_custom_box()
{
	global $post;
	$loc = get_post_meta($post->ID, "event_loc", true);
	if(empty($loc)) {
		$loc = upgrades_node_address();
	}
	print '<input style="width: 100%;" type="text" name="event_loc" value="'.$loc.'" />';	
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
add_action('admin_menu', 'add_custom_boxes');
add_action('save_post', 'upgrade_save_post');





function parse_net_feed() {
	global $netFeed;
	//define('MAGPIE_CACHE_DIR', '/tmp/magpie_cache');
	require_once(dirname(__FILE__).'/../../../wp-includes/rss.php');
	$url = "http://theupgrade.net/feeds.xml";
	$rss = fetch_rss($url);
	$netFeed = $rss->items;
}


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



// The network feed is parsed below in widget_netfeed
// This array will hold the RSS items that have georss info 
// so that the Google Map can add them in the theme.
$netFeed = array();
parse_net_feed();

?>