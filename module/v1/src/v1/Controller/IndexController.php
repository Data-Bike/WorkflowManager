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

class IndexController extends AbstractRestfulController
{

    public function indexAction()
    {
        
        //да не очень хорошее решение, но просто показать подойдет
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        $array[]=array('name'=>'Ужин','about'=>'Каждому нужен и обед и ужин','start'=>'02.03.1988 22:30','finish'=>'05.17.2345');
        return new JsonModel($array);
    }

}