<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Localization\Loc;

IncludeTemplateLangFile(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage('HR_FORM_TITLE'));

$extensions = ['ui.btn', 'ui.forms', 'ui.alert', 'ui.vue'];

foreach($extensions as $ext) {
	Extension::load($ext);
}
?>

<div id="app">

    <template>
        
        <form @submit.prevent="createTask">

            <div v-for="(query, index) in queryData">
                <div class="ui-ctl-w33">
                    <p><b>{{index + 1}}. <i>{{query.query}}</i> <span v-if="isRequired(index + 1)" class="red">*</span></b></p>
                    <textarea :required="isRequired(index + 1)" v-model="query.answer" class="query-field ui-ctl-element"></textarea>
                </div>
            </div>

            <div class="ui-ctl-w33">
            
                <p><b><i><?=Loc::getMessage('HR_FORM_QUALITIES_TITLE')?></i></b></p>
                <details>
                    <summary><p><b>Список качеств:</b></p></summary>
                    <ul>

                        <li v-for="desc in qualities"><b><i>{{desc.quality}}:</i></b> {{desc.description}}</li>

                    </ul>
                </details>

                <div class="qualities" v-for="priority in 5">
                    <select :id="getSelectQualityId(priority)" @change="changeQuality(priority)" class="ui-ctl-element">
                        <option></option>
                        <option v-for="quality in qualities" :value="priority">{{quality.quality}}</option>
                    </select>
                </div>
            
            </div> 

            <button class="ui-btn ui-btn-primary"><?=Loc::getMessage('HR_FORM_SEND_BUTTON')?></button>
        </form>        
    
    </template>    

</div>

