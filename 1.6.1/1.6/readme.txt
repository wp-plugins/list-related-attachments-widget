=== List Related Attachments ===
Contributors: Twinpictures
Donate link: http://www.twinpictures.de/related-attachments/
Tags: widget, list, related, attachments, sidebar, uploaded, files, shortcode
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: 1.6

Listed Related Attachments is a small wordpress widget and shortcode that will display a filterd list of all related attachments for the current post or page.

== Description ==

List Related Attachments is a sidebar widget and shortcode that will display a filtered, sorted and ordered list of all related attachments linked to current post or page. The widget options are: title, number of attachments to display, type of attachment to display by mime/type, order by value, order direction and what should be displayed (attachment title, caption or description).  Visit [Twinpictures | List-Related-Attachments](http://www.twinpictures.de/related-attachments/ "List-Related-Attachments WordPress plugin oven at Twinpictures") for a complete listing of shortcode options and examples that delight and inform.

== Installation ==

1. Old-school: upload the `List Related Attachments` folder to the `/wp-content/plugins/` directory via FTP.  Hipster: Add the plugin via the WordPress Plugins menu.
1. Activate the Plugin
1. Add the Widget to the desired sidebar in the WordPress Widgets menu.
1. Configure the `Listed Related Attachments` widget options.
1. Add a the shortcode to your post like so: `[list-related-attach type="application/zip" count="3" orderby="menu_order ID" order="ASC" show="caption"]`
1. Test that the this plugin meets your demanding needs.
1. Rate the plugin and verify that it works at wordpress.org.
1. Leave a comment regarding bugs, feature request, cocktail recipes at [Twinpictures | List-Related-Attachments]

== Frequently Asked Questions ==

= What are the default shortcode attributes? =
1. type = "application" (so basically everything)
1. count = "-1" (so everything)
1. orderby = "date" (by the date the attachment was added)
1. order = "DESC" (Descending in the newest to oldest direction)
1. show = "title" (The title of the attachment)

= I am going to a respectable cocktail bar, can you recommend a drink I should try? =
Gin Basel Smash

= I am a Social Netwookiee, do you have a Facebook page? =
Yes, yes...[Twinpictures is on Facebook](http://www.facebook.com/twinpictures "Twinpictures on Facebook").
[Twinpictures is on Facebook]: http://www.facebook.com/twinpictures

= Does Twinpictures do the Twitter? =
Ah yes! [@twinpictures](http://twitter.com/#!/twinpictures "Twinpictures Twitter Channel") does the twitter tweeting around here.
[@twinpictures]: http://twitter.com/#!/twinpictures

= Where can I go for jokes and fun? =
Why not try: [Jokes & Fun]: http://jokesandfun.de/

= What is the meaning of life, the universe and everything? =
42

== Screenshots ==

1. Shown here are the available options of the `List Related Attachments`.  See any more you like?  Let us know!
1. For your viewing pleasure:  The sidebar widget in the midst of some hot-hot sidebar widget action.  Calm down now... it's not THAT exciting.

== Changelog ==

= 1.6 =
* Optimized code and added mime-type classes to list items.

= 1.5 =
* Fixed a bug in the shortcode (Big thanks to motylanogha for reporting this).  NOW the plugin works 100% like it should... I think.

= 1.4 =
* Options now allow to choose from the attachment title, caption or description as the text that is displayed in the attachment link.

= 1.3 =
* Expanded the options to include orderby and order... for *Ultimater Flexibility* -- yes, Ultimater is not a real word, but just go with it, OK?

= 1.2 =
* Added shortcode so that related attachments can be listed withing the page loop.  Listing related attachments is not just for sidebar widgets anymore.

= 1.1 =
* Changed mime/type to a text input field for *Ultimate Flexibility*.
* Pimped this readme.txt file.
* Added the plugin to wordpress.org.

= 1.0 =
* The plugin came to be.

== Upgrade Notice ==

= 1.6 =
Added new mime-type specific classes to the list items.

= 1.5 =
Fixes a bug in the shortcode discovered by motylanogha.  Plugin is now 100% working again.

= 1.4 =
Expands the plugin to include display options in the attachment link text.

= 1.3 =
Expands the plugin to include orderby and order options.

= 1.2 =
Introduces the [list-related-attach] shortcode for listing related attachments within posts & pages

= 1.1 =
Upgrade today to take advantage of Ultimate Flexibility by manually entering the mime/type of the attachments to list.
