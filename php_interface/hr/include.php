<?php

defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Ramapriya\HR\HRModule;

$hrModuleClass = [
    'Ramapriya\\HR\\HRModule' => '/local/php_interface/hr/HRModule.php'
];
Loader::registerAutoloadClasses(null, $hrModuleClass);

$eventHandler = EventManager::getInstance();

$handlerParams = [];

$eventHandler->addEventHandler('socialnetwork', 'OnFillSocNetFeaturesList', ['Ramapriya\\HR\\HRModule','AddSocnetFeature']);

$eventHandler->addEventHandlerCompatible('socialnetwork', 'OnFillSocNetMenu', ['Ramapriya\\HR\\HRModule','AddSocNetMenu']);

$eventHandler->addEventHandlerCompatible('socialnetwork', 'OnParseSocNetComponentPath', ['Ramapriya\\HR\\HRModule','OnParseSocNetComponentPath']);