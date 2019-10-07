DELETE FROM configuration WHERE configuration_key LIKE 'NEWS_BOX_%';
DELETE FROM configuration_group WHERE configuration_group_title = 'News Box Manager';
DROP TABLE IF EXISTS box_news;
DROP TABLE IF EXISTS box_news_content;
DELETE FROM admin_pages WHERE page_key IN ('localizationNewsBox', 'configNewsBox', 'toolsNewsBox');