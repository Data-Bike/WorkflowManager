<?php

/*
  Document   : IndexController
  Created on : 28.10.2013, 11:37:11
  Author     : cawa
  Description:
  Index controller
 */

namespace v1\Controller;

use Zend\Mvc\Controller\AbstractRestfulController,
    Zend\View\Model\JsonModel;

class IndexController extends AbstractRestfulController {

    public function indexAction() {

        //да не очень хорошее решение, но просто показать подойдет
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'1');
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'2');
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'3');
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'4');
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'5');
        $array[] = array('name' => 'Ужин', 'about' => 'Каждому нужен и обед и ужин', 'start' => '02.03.1988 22:30', 'finish' => '05.17.2345', 'id'=>'6');
        return new JsonModel($array);
    }

    public function userAction() {
        
        //да не очень хорошее решение, но просто показать подойдет
        $array[] = array('name' => 'Антон', 'label' => 'Антон', 'position' => 'Нач. отдела', 'id' => '1');
        $array[] = array('name' => 'Александр', 'label' => 'Александр', 'position' => 'Директор', 'id' => '2');
        $array[] = array('name' => 'Михаил', 'label' => 'Михаил', 'position' => 'специалист', 'id' => '3');
        $array[] = array('name' => 'Сергей', 'label' => 'Сергей', 'position' => 'специалист', 'id' => '4');
        $array[] = array('name' => 'Григорий', 'label' => 'Григорий', 'position' => 'менеджер', 'id' => '5');
        $array[] = array('name' => 'Фёдор', 'label' => 'Фёдор', 'position' => 'аналитик', 'id' => '6');
        return new JsonModel($array);
    }

}
