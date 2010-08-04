<?php
/*
Plugin Name: U! Network Feed
Plugin URI:
Description: Global U! Network Feed
Author: Angel Ng
Version: 1
Author URI: http://lonewolf-online.net/
*/

 
function networkfeed() {
  // Set up an hourly wp-cron job to cache the feed
  // Currently this creates a job but does not help the feed load any faster.
  //if ( !wp_next_scheduled('cache_networkfeed') ) {
  //wp_schedule_event( time(), 'hourly', 'cache_networkfeed' ); // hourly, daily and or twicedaily
  //}
  
  // Use the SimplePie Core through an array, pull multiple rss feeds together.
  // For more information: http://simplepie.org/
  
  $urls = array(
      'http://wowm.org/uz/',
      'http://turbulence.org/upgrade_boston/',
      'http://www.upgrade-berlin.net/',
      'http://www.upgradesaopaulo.com.br/arte-novas-midias/',
      'http://digiwaukee.net/upgrade/',
      'http://www.upgrade.artapsu.com/',
      'http://upgrade.eyebeam.org/',
      'http://upgrade.okno.be/',
      'http://www.incident.net/upgradedakar/',
      'http://www.likenow.org/upgrade/',
      'http://upgradechicago.org/');
  $feed = new SimplePie();
  $feed->set_feed_url($urls);
  
    // Set to pull only 1 item per feed
    $feed->set_item_limit(1);
    
    // Set SimplePie Cache location to a specific file
    //$feed->enable_cache(true);
    $feed->set_cache_location(get_bloginfo('stylesheet_directory') . './cache');
    //$feed->set_cache_duration(999999999); 
    //$feed->set_timeout(-1);
    
    // Initialize the feed so that we can use it.
    $feed->init();
    $feed->handle_content_type();
   
    echo "<ul>";
    foreach ($feed->get_items(0,6) as $item):
    
        // Call the custom Upgrade tags within the feeds (we must also call the original feed from which the data is being pulled)
        $nodename = $item->get_feed()->get_channel_tags('http://upgrade.eyebeam.org/upgrade', 'nodeName');
        $name = $nodename[0]['data'];
        
        $nodecolordark = $item->get_feed()->get_channel_tags('http://upgrade.eyebeam.org/upgrade', 'nodeColorDark');
        $dark = $nodecolordark[0]['data'];
        
        $nodecolorlight = $item->get_feed()->get_channel_tags('http://upgrade.eyebeam.org/upgrade', 'nodeColorLight');
        $light = $nodecolorlight[0]['data'];
        
        $nodecolortext = $item->get_feed()->get_channel_tags('http://upgrade.eyebeam.org/upgrade', 'nodeColorText');
        $text = $nodecolortext[0]['data'];
        
        //$nodemarker = $item->get_feed()->get_channel_tags('http://upgrade.eyebeam.org/upgrade', 'nodeMarker');
        //$marker = $nodemarker[0]['data'];
        
        // Activate the cron job
        //add_action('cache_networkfeed', 'widget');
        
        // Finally, echo the custom data and place it in the widget.
        ?>
        <div class="feedcontent">
            <div class="wrap" style="border-color:<?php echo $dark?>">
              <li style="border-color:<?php echo $text?>; padding: 0 6px">
                <span class="nodename" style="color:<?php echo $text ?>">U! <?php echo $name; ?>:</span>
                <?php
                // List the global feed post titles and link to the post permalink.
                ?>
                <span class="feed">
                <a href="<?php print $item->get_permalink(); ?>" style="color:<?php echo $text ?>">
                <?php print $item->get_title(); ?></a></span>
              </li>
            </div>
        </div>
        <?php
    
    endforeach;
    echo "</ul>";
    
    $feed->__destruct(); // Do what PHP should be doing on it's own so that there are no memory leaks.
    unset($feed);
    unset($item);
}
 
function widget_networkfeed($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?>Global Network Feed<?php echo $after_title;
  networkfeed();
  echo $after_widget;
}
 
function networkfeed_init() {
  register_sidebar_widget(__('U! Network Feed'), 'widget_networkfeed');
}

add_action("plugins_loaded", "networkfeed_init");
?>
