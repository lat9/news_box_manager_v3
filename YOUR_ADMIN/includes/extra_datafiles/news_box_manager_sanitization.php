<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2016-2024, Vinos de Frutas Tropicales
//
$news_mgr_sanitizer = AdminRequestSanitizer::getInstance();
$news_mgr_sanitizer->addSimpleSanitization('PRODUCT_DESC_REGEX', ['news_title', 'news_content']);
