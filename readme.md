# News Box Manager v3.1.1 for Zen Cart 1.5.6 and later

See the plugin's [support-thread](https://www.zen-cart.com/showthread.php?226052-News-Box-Manager-v3-0-0-Support-Thread) on the Zen Cart forums!

## New Features

v3.0.0 (and later) of _**News Box Manager**_ builds on the [previous](https://github.com/lat9/news_box_manager) versions of the plugin, adding the following features _for Zen Cart 1.5.6 and later_:

1. Enables the creation of up to four (4) different types of 'news', each separately manageable.
	1. Separate admin tools for each type, enables different admin profiles to manage each news type.
	2. An additional tool enables the management of _all_ news types.
	3. Two (2) storefront sideboxes are now provided, enabling different news types' to be displayed in different locations.
2. Uses the `zc156` admin's bootstrap support for its admin-tool displays.
	1. Adds search and sort controls to the admin-level tools, making it easier to manage your news articles.
	2. Adds controls to the admin's article-listing, enabling you to make a copy of an article and/or to move an article to a different news-type.
3. Changes the storefront `news_archive` and `more_news` pages to be `all_articles` and `article`, respectively.
	1. Previous pages' header-processing remains to redirect access to those pages (permanently) to their new homes!
4. Watches for admin-level language-addition and -removal actions, adjusting the articles' news-content accordingly.

Additional changes:

1. An article must have non-blank title and content _**in all the store's languages**_ (previously only one language was required).  
	1. On installation, any article found to have a blank title or content _in any of the store's languages_ is disabled.
2. Due to the significant changes to the various language files, the German language files have been removed from the distribution.

## Additional Documentation

- [Admin Tool Processing](/wiki/Admin-Tool-Processing)
- [Storefront Processing](/wiki/Storefront-Processing)
- [Sitemap XML Integration](/wiki/Sitemap-XML-Integration).  Added in v3.1.0.

