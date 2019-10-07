<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2018-2019, Vinos de Frutas Tropicales
//
// -----
// Starting with v3.0.0 of the plugin, a store-owner can define individual news "types" and
// restrict their use to specific admin-profiles.  This "clone" handles news-box Type1, as
// defined by the store itself.
//
$_GET['nType'] = 1;
require 'news_box_manager.php';
