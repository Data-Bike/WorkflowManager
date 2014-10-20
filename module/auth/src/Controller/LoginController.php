<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace auth\Controller;

use Application\Controller\AbstractEntityUsingController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use wm\Entity\User;

class LoginController extends AbstractEntityUsingController {

    public function indexAction() {
        return new ViewModel();
    }

    public function loginAction() {
        $login = $this->params()->fromPost('username');
        $pass = $this->params()->fromPost('password');
        $user=$this->getEntityManager()->getRepository('wm\Entity\User')->findOneByUsername($login);
        if ($user->getPassword()==md5($pass)) {
            $session = new Container('wm_user');
            $session->offsetSet('role', 'admin');
        }
        return new ViewModel();
    }

    public function logoutAction() {
        $session = new Container('wm_user');
        $session->offsetSet('role', 'anonymous');
        return new ViewModel();
    }

}
