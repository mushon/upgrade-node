
*IMPORTANT: This is for testing purposes only. Theme is currently under development.


Use these theme files to start your Upgrade! Network website.

This theme is created specfically for the Upgrade! Network and is built
on top of the Thematic Theme Framework. You will be required to install both
for this theme to work.

To begin, install Wordpress: http://wordpress.org/

Once you have WP set up, click on the 'Themes' tab under 'Appearance'
on your WP Dashboard and install the Thematic Theme Framework.

Move this entire folder (upgrade_node) to the root level of wp-content/themes
and activate this theme like any other WordPress theme.

For this theme you will need to download extra plugins:
Install these through the 'Plugins' tab on your WP Dashboard.

Click 'Add New' then search and install the following WP plugins:

'SimplePie Core'
!Important: This plugin is used for the global events plugin and widget. To
activate manually copy networkfeed.php located this theme folder to
wp-content>plugins folder. Activate the plugin the the WP Dashboard then go to
the widget tab and drag the U! Network Feed widget into the primary or secondary
aside.
*Note: This causes the site to load slow. There is a ticket for this problem.
Don't activate this widget for a normal load time.

'Geo-Mashup'
!Important: In the upgrade_node theme folder there is a subfolder titled
'geo-mashup-custom' This folder must be manually moved into wp-content>plugins folder
within your WP directory.

'WPML Multilingual CMS'
This is the translation plugin that we are using to replace qtranslator.

'Event Calendar 3'
This plugin allows you to put a calendar in the sidebars.

For updated files visit the Git Repo: http://github.com/thisisangelng/upgrade-node