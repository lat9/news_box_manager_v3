<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2015-2024, Vinos de Frutas Tropicales
//
$autoLoadConfig[200][] = [
    'autoType' => 'init_script',
    'loadFile' => 'init_news_box_manager_admin.php'
];

$autoLoadConfig[200][] = [
    'autoType' => 'class',
    'loadFile' => 'observers/NewsBoxManagerAdminObserver.php',
    'classPath' => DIR_WS_CLASSES
];
$autoLoadConfig[200][] = [
    'autoType' => 'classInstantiate',
    'className' => 'NewsBoxManagerAdminObserver',
    'objectName' => 'nbm'
];
