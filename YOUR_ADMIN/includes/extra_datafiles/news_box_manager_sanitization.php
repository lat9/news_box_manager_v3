<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.1 and later by lat9.
// Copyright (C) 2016, Vinos de Frutas Tropicales
//
if (class_exists ('AdminRequestSanitizer') && method_exists ('AdminRequestSanitizer', 'getInstance')) {
    $news_mgr_sanitizer = AdminRequestSanitizer::getInstance();
    $news_mgr_sanitizer->addSimpleSanitization ('PRODUCT_DESC_REGEX', array ( 'news_title', 'news_content' ));
}