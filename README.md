##Instagram Image Feed
Contributors: Mark Hayden  
Tags: wordpress, instagram, search, plugin  
Requires at least: 3.5.1  
Tested up to: 3.6  
Stable tag: 0.0.02  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple plugin for pulling in and displaying instagram queries unique to each post.


###Description

This is a simple wordpress plugin created to perform, and cache, a search query that is unique to an individual post within wordpress. You enter a user name with each post. From there instagram is queried and the results are stored for you to display. Uses a simple shortcode to make displaying data easy.


###Installation

1. Add the plugin into your wordpress install. wp-content/plugins/[PUT FOLDER HERE]
2. From the wordpress admin dashboard go to plugins and activate the "igram srchr" plugin.
3. Navigate to the plugin settings page under the "Settings" menu at the left of your admin panel.
4. Follow instructions there to generate and set up a twitter app, as well as the default plugin settings.
5. You will need to go into an active post and enter a query before the tests will pass.


###Usage

* igram_id => the numeric id for an image from instagram
* igram_query => the query that is responsible for returning the image
* igram_handle => the photographers username
* igram_thumbnail => the thumbnail sized image (150x150)
* igram_low_resolution => the thumbnail sized image (306x306)
* igram_standard => the thumbnail sized image (612x612)
* igram_posted => the date the image was taken and posted to instagram
* logged => when the image was saved to your database database


Print out the body of all tweets associated with posts query from wordpress.

```
[igram_srch]
	<img src="{{igram_thumbnail}}" alt="" />
[/igram_srch]

[igram_srch limit=5]
	<img src="{{igram_thumbnail}}" alt="" />
[/igram_srch]

[twtr_igram limit=5 date="Y-M-D"]
	<img src="{{igram_thumbnail}}" alt="" />
[/igram_srch]
```

Print out the body of all tweets associated with posts query from php.

```
<?php echo do_shortcode('[igram_srch limit=5]{{twtr_content}}[/igram_srch]'); ?>
<?php echo do_shortcode('[igram_srch date="Y-M-D"]{{twtr_content}}[/igram_srch]'); ?>
```

###Changelog
0.0.02
> Initial build and launch.