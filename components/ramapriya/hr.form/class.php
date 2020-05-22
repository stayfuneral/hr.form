<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

/**
 * Класс компонента формы заявки на подбор персонала для группы HR
 */

class HRFormComponent extends CBitrixComponent implements Controllerable {

    /**
     * Объект Request
     * 
     * @var object $request
     */
    public $request;

    /**
     * Получает массив из файла
     * 
     * @param string $file
     */
    private function getArrayFromFile($file) {
        return $GLOBALS['APPLICATION']->IncludeFile($this->getPath() . $file);
    }

    /**
     * Получает список вопросов из файла
     */
    private function getQueries() {
        return $this->getArrayFromFile('/files/queries.php');
    }

    /**
     * Метод из наследуемого интерфейса, который нужен для работы с Ajax
     */

    public function configureActions() {

    }
    
    /**
     * Основной метод компонента. Записывает Request в переменную, а также подключает шаблон компонента
     */


    public function executeComponent() {
        $this->request = Context::getCurrent()->getRequest();
        $this->IncludeComponentTemplate();
    }

    /**
     * Обрабатывает Ajax-запрос, переданный из шаблона компонента, возвращает массив с вопросами и описаниями качеств, а также ID текущего пользователя (понадобится для постановки задачи)
     * 
     * @return array
     */
    public function sendRequestAction() {

        if($this->request['action'] === 'get_queries') {
            $response = [                
                'current_user' => intval($GLOBALS['USER']->GetID()),
                'queries' => $this->getQueries(),
                'qualities' => $this->getQualities()
            ];
            return $response;
        }   
        
    }

    /**
     * Обрабатывает Ajax-запрос на создание задачи
     */
    public function createTaskAction() {
        if($this->request['action'] === 'create_task') {
            $arTaskFields = $this->request['fields'];

            Loader::includeModule('tasks');
            $tasks = new CTasks;

            $task = $tasks->Add($arTaskFields);

            if(intval($task) > 0) {
                $response = [
                    'result' => 'success',
                    'task' => $task
                ];
            } else {
                $response = [
                    'result' => 'error',
                    'error_description' => $tasks->LAST_ERROR
                ];
            }

            return $response;
        }
    }

    /**
     * Получает список качеств из файла
     * 
     * @return array
     */
    public function getQualities() {
        $qualities = [];

        foreach($this->getArrayFromFile('/files/qualities.php') as $quality) {
            $qual = explode(': ', $quality);
            $qualities[] = [
                'quality' => $qual[0],
                'description' => $qual[1],
            ];
        }
        return $qualities;
    }

}