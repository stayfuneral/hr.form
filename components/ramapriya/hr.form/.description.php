<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    'NAME' => Loc::getMessage('HR_FORM_COMPONENT_NAME'),
    'DESCRIPTION' => Loc::getMessage('HR_FORM_COMPONENT_DESCRIPRION'),
    'PATH' => [
        'ID' => Loc::getMessage('HR_FORM_COMPONENT_NAME')
    ]
];