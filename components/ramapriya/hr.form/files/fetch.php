<?php require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\IO\File;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Main\Context;
use Bitrix\Tasks\Internals\TaskTable;

$queries = File::getFileContents(__DIR__ . '/queries');
$queries = explode(PHP_EOL, $queries);

$quality = File::getFileContents(__DIR__ . '/qualities');
$quality = explode(PHP_EOL, $quality);

$qualities = [];

foreach($quality as $qual) {

    $item = explode(': ', $qual);
    $qualities[] = [
        'quality' => $item[0],
        'description' => $item[1]
    ];

}

$request = Context::getCurrent()->getRequest();


if($request->isPost() !== false) {

    $response = [];

    $inputs = Json::decode($request->getInput());
    $requestType = $inputs['request_type'];

    switch($requestType) {
        case 'getQueryData':

            $response['qualities'] = $qualities;
            $response['queries'] = $queries;
            break;
            
        case 'createTask':

            Loader::includeModule('tasks');

            $tasks = new CTasks;

            $fields = $inputs['fields'];

            $task = $tasks->Add($fields);

            if(intval($task) > 0) {
                $response['result'] = 'success';
                $response['task'] = $task;
            } else {
                $response['result'] = 'error';
                $response['error_description'] = $tasks->LAST_ERROR;
            }
    }

    echo Json::encode($response, JSON_UNESCAPED_UNICODE);
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';