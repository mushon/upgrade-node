
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

In addition, for this theme you will need to download some extra plugins:
Install these through the 'Plugins' tab on your WP Dashboard.

Click 'Add New' then search and install the following WP plugins:

1. 'SimplePie Core'

!Important: This plugin is used for the Global Network Feed Widget. To
activate, access the Widget tab in your WP dashboard and drag the U! Global
Network Feed widget into the primary or secondary aside.

*Note: This causes the site to load slow. There is a ticket for this problem.
Don't activate this widget for a normal load time.

2. 'Geo-Mashup'

!Important: In the upgrade_node theme folder there is a subfolder titled
'geo-mashup-custom' This folder must be manually moved into wp-content>plugins folder
within your WP directory and then activated through the Plugin tab on the Dashboard.

Simply enter in a Google API key in the Geo Mashup settings for the map to work.

There are specific settings needed for this plugin to function as wanted. For the
purpose of development the instructions are not included here but will be included
in the full installation tutorial.

3. 'WPML Multilingual CMS'
This is the translation plugin that we are using to replace qtranslator should
you require a multilingual site.

4. 'Event Calendar 3'
This plugin allows you to put a calendar in the sidebars that displays events.
For set up, create an Events category and go into the Event Calendar Options.
Select 'Events' as the category to display in the calendar.




For updated files visit the Git Repo: http://github.com/thisisangelng/upgrade-node