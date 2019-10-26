<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
//                               
$autoLoadConfig[200][] = array(
    'autoType' => 'init_script',
    'loadFile' => 'init_news_box_manager_admin.php'
);
                             
$autoLoadConfig[200][] = array(
    'autoType' => 'class',
    'loadFile' => 'observers/NewsBoxManagerAdminObserver.php',
    'classPath' => DIR_WS_CLASSES
);
$autoLoadConfig[200][] = array(
    'autoType' => 'classInstantiate',
    'className' => 'NewsBoxManagerAdminObserver',
    'objectName' => 'nbm'
);