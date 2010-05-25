<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head profile="http://gmpg.org/xfn/11">
	<title>Upgrade! <?php wp_title( '-', true, 'right' ); echo wp_specialchars( get_bloginfo('name'), 1 ) ?></title>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/grid.css" type="text/css" media="screen, projection" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/print.css" type="text/css" media="print" />
	<!--[if IE]>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/styles/ie.css" type="text/css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url') ?>" />
	<!-- <link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/styles/node-styles.php" /> -->
	<style type="text/css">
		<?php echo nodeStyles() ?>
	</style>
	<!--[if lt IE 7]>
		<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta)/IE7.js" type="text/javascript"></script>
	<![endif]-->

	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'sandbox' ), wp_specialchars( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
	
	
	<?php if(get_option('upgrades_use_gmap')): ?>
	
		<?php $wpgeo_options = get_option("wp_geo_options"); ?>
		<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?=$wpgeo_options['google_api_key']?>" type="text/javascript"></script>
	    <script type="text/javascript">
	    //<![CDATA[
		var map;
	    function load()
		{
			if (GBrowserIsCompatible())
			{
				<?php
				
				$lat = null;
				$lon = null;
				
				$my_query = new WP_Query("category_name=events&showposts=1");
				if($my_query->have_posts())
				{
					$my_query->the_post();
					$lon = get_post_meta($my_query->post->ID, '_wp_geo_longitude', true);
					$lat = get_post_meta($my_query->post->ID, '_wp_geo_latitude', true);
				}
				
				if(!$lat || !$lon)
				{
					$lat = attribute_escape(upgrades_node_lat());
					$lon = attribute_escape(upgrades_node_lon());
				}
				$zoom = attribute_escape(upgrades_node_zoom());
				?>
	
				var llHome = new GLatLng('<?=$lat?>', '<?=$lon?>');
				map = new GMap2(document.getElementById("gmap"));
				map.addControl(new GSmallZoomControl());
				map.setCenter(llHome, <?=$zoom?>, G_SATELLITE_MAP);
				map.panBy(new GSize(-282, 0));
				
				// Create a GIcon that will be used to mark all of the local events.
				var Icon = new GIcon();
				Icon.image = "<?php bloginfo('wpurl'); ?>/wp-content/uploads/icon.png";
				Icon.iconSize = new GSize(22, 64);
				Icon.iconAnchor = new GPoint(11, 54);
				Icon.infoWindowAnchor = new GPoint(11, 54);
				
				var home = new GMarker(llHome, { icon:Icon });
				map.addOverlay(home);
				
				GEvent.addListener(home, 'click', function() { 
					home.openInfoWindowHtml('Upcoming Event!'); 
				});
				
				// Dear curious source-looker,
				// Why doesn't it work to just pull in the feed?  It has GeoRSS info in it! :(
				// -jeff
				//var geoXml = new GGeoXml("http://theupgrade.net/feeds.xml");
				//map.addOverlay(geoXml);
		
				<?php foreach(get_net_feed() as $fitem): if(isset($fitem['georss'])): ?>
					var Icon<?=$fitem['nodename']?> = new GIcon();
					Icon<?=$fitem['nodename']?>.image = "<?=$fitem['nodeurl']?>/wp-content/uploads/icon.png";
					Icon<?=$fitem['nodename']?>.iconSize = new GSize(22, 64);
					Icon<?=$fitem['nodename']?>.iconAnchor = new GPoint(11, 54);
					Icon<?=$fitem['nodename']?>.infoWindowAnchor = new GPoint(11, 54);

					<?php $parts = explode(" ", $fitem['georss']['point']); ?>
					var ll<?=$fitem['nodename']?> = new GLatLng('<?=$parts[0]?>', '<?=$parts[1]?>');
					var <?=$fitem['nodename']?> = new GMarker(ll<?=$fitem['nodename']?>, { icon:Icon<?=$fitem['nodename']?> });
					map.addOverlay(<?=$fitem['nodename']?>);
					GEvent.addListener(<?=$fitem['nodename']?>, 'click', function() { 
						<?=$fitem['nodename']?>.openInfoWindowHtml('U! <?=$fitem['nodename']?>'); 
					});
					
				<?php endif; endforeach;?>

				map.setCenter(llHome, 16, G_SATELLITE_MAP);
				map.panBy(new GSize(-282, 0));
			} // end if(GBrowserIsCompatible())
	    } // end load()
	    //]]>
		</script>
	
	<?php endif;  // if get_option('upgrades_use_gmap') ?>
	
	<?php wp_head() // For plugins  ?>

</head>


<?php if(get_option('upgrades_use_gmap')): ?>
<body class="<?php sandbox_body_class() ?> <?php lang_dir() ?>" onload="load()" onunload="GUnload()">
<div id="map">
	<div id="gmap"></div>
	<div id="stripe"></div>
</div>
<?php else: ?>
	<body class="<?php sandbox_body_class() ?> <?php lang_dir() ?>">
	<div id="map">
	<?php
		$temp = $wp_query;
		get_posts("category_name=events");
		while (have_posts())
		{
			the_post();
			$q = 'post_mime_type=image&post_parent='.$post->ID;
			$images =& get_children($q);
			if ( !empty($images)) {
				foreach( $images as $attachment_id => $attachment ) {
					$im = wp_get_attachment_image_src( $attachment_id, 'full' );
					$header_bg_image = $im[0];
				}
			}
		}
		$wp_query = $temp;
		?>
		<style>
		#map {
			background-image: url(<?=$header_bg_image?>);
			background-repeat: repeat;
		}
		</style>
		<div id="gmap"></div>
		<div id="stripe"></div>
	</div>
	
	

<?php endif; ?>

<div id="wrapper" class="hfeed container">
	<h1 id="blog-title">
		<a href="<?php bloginfo('home') ?>/" title="<?php echo wp_specialchars( get_bloginfo('name'), 1 ) ?>" rel="home">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/styles/images/upgrade_title.png" alt="Upgrade!"/>
			<span><?php bloginfo('name') ?></span>
		</a>
	</h1>
	<div id="right-col" class="">
			<div id="feed" class="">
				<a href="<?php bloginfo('rss2_url') ?>" title="RSS feed"><img src="<?php bloginfo('stylesheet_directory'); ?>/styles/images/rss_icon.png" alt="rss-feed"/></a>
			</div>
			
		<div id="header" class="">
			<!-- <div id="blog-description"><?php bloginfo('description') ?></div> -->
			<div id="search" class="">
				<h3><label for="s"><?php _e( 'Search', 'sandbox' ) ?></label></h3>
				<form id="searchform" class="blog-search" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="10" tabindex="1" />
						<input type="submit" class="button" value="" tabindex="2" />
					</div>
				</form>
			</div>
		</div><!--  #header -->
		
		
		<div id="access">
			<!-- <?php sandbox_globalnav() ?> -->
		</div><!-- #access -->
