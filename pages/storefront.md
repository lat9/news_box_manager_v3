# Storefront Processing

The storefront processing for the **News Box Manager** provides:

1. Two (2) separately locateable sideboxes.
2. An `all_articles` page, where all active articles are displayed.
3. An `article` page, where the detailed content of a single article is displayed.

## Sidebox Display

Two storefront sideboxes (`news_box_sidebox` and `news_box_sidebox2`) are provided, enabling your store display different content types (using the admin's **Tools->Layout Boxes Controller**).  The sidebox configuration is controlled by the plugin's configuration settings:

![Sidebox configuration items](images/sidebox_configuration.jpg) 

Each sidebox can have a different layout, one of:

| List | GridTitleDate | GridTitleDateDesc |
| ---- | ---- | ---- |
|  !['List' format](images/sidebox_list_format.jpg) | !['GridTitleDate' format](images/sidebox_gridtitledate_format.jpg) | !['GridTitleDateDesc' format](images/sidebox_gridtitledatedesc_format.jpg) |


## "All Articles" Display

The `all_articles` page (`news_archive` for plugin versions prior to 3.0.0), displays a running list of (er) all articles currently active for the store.  Its method of display is controlled by the plugin's configuration settings:

!["All Articles" configuration settings](images/articles_configuration.jpg)

If the _Display Mode_ is set to `Table`, the listing displays similar to:

![All Articles, using Table format](images/all_articles_table_format.jpg)

When the _Display Mode_ is set to `Listing`, the _Date Format_ also comes into play.  For an `MdY` date:

![All Articles, Listing format with MdY dates](images/all_articles_listing_format_mdy.jpg)

or for a `short` date:

![All Articles, Listing format with short dates](images/all_articles_listing_format_short.jpg)

In any case, clicking on an article's detailed link results in the `article` page (previously `more_news`) is displayed with the article's full content.
