=== LH Save Down ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-save-down/
Tags: post, posts, wordpress, download, html, text, attachment, pdf, template, templates
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 2.20

Save posts and pages in text, html, epub, or pdf attachment format. Only post content is saved all other stuff gets discarded.

== Description ==

Save post as text, html, pdf, or epub. Useful for enabling downloads of your content.

The format of the downloadable attachment can be modified by use of templates.

== Installation ==

1. Upload the entire `lh-save-down` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Regenerate  your permalinks, via settings->permalinks.
1. Optionally if you want to change the format of your html or pdf downloads move the lh_save_down-html-post_template.php template (for html) or h_save_down-pdf-post_template.php template (for pdf) into your theme or child theme directory and edit it to customise the output.

== Frequently Asked Questions ==

= I installed the plugin but can not see any thing on post, how do I save posts, pages, cpts etc in the various formats? =

*Add /feed/lh-save_down-text/ (for text output), /feed/lh-save_down-html/ (for html output), /feed/feed/lh-save_down.pdf (for pdf output), or /feed/feed/lh-save_down.epub (for epub output) to the end of the url of any singular page. You may want to edit your theme files to add these parameters to the end of permalinks to create download links.

= What is something does not work?  =

LH Save Down, and all [https://lhero.org/](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins and switch to one of the themes that come with core, e.g. twentyfifteen, twentysixteen etc.

If the problem persists please leave a post in the support forum: [https://wordpress.org/support/plugin/lh-save-down/](https://wordpress.org/support/plugin/lh-save-down/). I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

= Where can I see this plugin in action?  =

There is a download link in the main menu on this page of the sports organisation of which I am president: [https://princesparktouch.com/about/](https://princesparktouch.com/about/)

== Changelog ==

**2.00 May 10, 2016**  
Object oriented code.

**2.10 May 20, 2016**  
Added pdf download option

**2.11 March 30, 2017**  
use isset

**2.12 July 29, 2017**  
Added class check

**2.13 December 20, 2017**  
Added basic epub support

**2.20 July 28, 2022**  
updated pdf library for php 8.0