<?php

namespace Ramapriya\HR;

/**
 * Класс обработчиков событий для добавления кастомной ссылки в меню группы
 * 
 * @param int GROUP_ID
 */

class HRModule {

    const GROUP_ID = 42;

    /**
     * 
     */

    public static function AddSocnetFeature(&$arSocNetFeaturesSettings) {

        $arSocNetFeaturesSettings['hr_form'] = [
            'allowed' => [SONET_ENTITY_USER, SONET_ENTITY_GROUP],
            'operations' => [
                'write' => [
                    SONET_ENTITY_USER => SONET_RELATIONS_TYPE_NONE, 
                    SONET_ENTITY_GROUP => SONET_ROLES_MODERATOR
                ],
                'view' => [
                    SONET_ENTITY_USER => SONET_RELATIONS_TYPE_ALL,
                    SONET_ENTITY_GROUP => SONET_ROLES_USER
                ]
            ],
            'minoperation' => 'view'
        ];

    }

    public static function AddSocNetMenu(&$arResult) {

        if(intval($arResult['Group']['ID']) === self::GROUP_ID) {

            $arResult['CanView']['hr_form'] = true;
            $arResult['Urls']['hr_form'] = \CComponentEngine::MakePathFromTemplate('/workgroups/group/#group_id#/hr_form/', ['group_id' => $arResult['Group']['ID']]);
            $arResult['Title']['hr_form'] = 'Заявка на подбор персонала';

        }

    }

    public static function OnParseSocNetComponentPath(&$arUrlTemplates, &$arCustomPagesPath) {

        $arUrlTemplates['hr_form'] = 'group/#group_id#/hr_form/';
        $arCustomPagesPath['hr_form'] = '/local/php_interface/hr/';
    }

}