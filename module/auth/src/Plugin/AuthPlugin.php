<?php

namespace auth\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
use auth\Role\UserRole;
use auth\Resource\TaskResource;
use auth\Assertion\TaskAssertion;
use auth\Assertion\UserAssertion;
use Zend\View\Model\JsonModel;

class AuthPlugin extends AbstractPlugin {

    protected $sesscontainer;

    private function getSessContainer() {
        if (!$this->sesscontainer) {
            $this->sesscontainer = new SessionContainer('wm_user');
        }
        return $this->sesscontainer;
    }

    public function doAuthorization($e) {
        //setting ACL...
        $acl = new Acl();
        //add role ..
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'), 'anonymous');
        $acl->addRole(new Role('admin'), 'user');

        $acl->addResource(new Resource('Application'));
        $acl->addResource(new Resource('auth'));
        $acl->addResource(new Resource('wm'));
        $acl->addResource(new Resource('administration'));
        $acl->addResource(new Resource('user'));

        $acl->deny('anonymous', 'Application', 'view');
        $acl->deny('anonymous', 'wm', 'view');
        $acl->deny('anonymous', 'administration', 'view');
        $acl->allow('anonymous', 'auth', 'view');

        $acl->allow('user', array('Application'), array('view')
        );

        //admin is child of user, can publish, edit, and view too !
        $acl->allow('admin', array('Application'), array('publish', 'edit')
        );
        $acl->allow('admin', array('wm'), array('publish', 'view')
        );
        $acl->allow('admin', array('administration'), array('publish', 'view')
        );
        $acl->allow('admin', array('user'), array('publish', 'view')
        );

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $rqst = $e->getRequest();
        $id = $rqst->getQuery('id');
        $operation = $rqst->isGET() && $id ? 'GET' :
                ($rqst->isGET() ? 'LIST' :
                        ($rqst->isPUT() ? 'UPDATE' :
                                ($rqst->isPOST() ? 'CREATE' :
                                        ($rqst->isDELETE() ? 'DELETE' : ''
                                        ))));
//       print_r($rqst);
        if ($namespace == 'auth') {
            $operation = $namespace;
        }

        $em = $this->getController()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $acl2 = new Acl();
        $userRole = new UserRole($this->getSessContainer()->id ? $this->getSessContainer()->id : '0', $em);
        $acl2->addRole($userRole);
        $resource = new TaskResource($rqst);
        $acl2->addResource($resource);
        $role = (!$this->getSessContainer()->role ) ? 'anonymous' : $this->getSessContainer()->role;
//        echo $acl->allow($userRole, $resource, $operation, new TaskAssertion());
        $userAssertion = new UserAssertion();
        $taskAssertion = new TaskAssertion();
        
        if ($controllerClass == 'wm\Controller\UserController') {
            $acl2->allow(NULL, NULL, NULL, $userAssertion);
        } elseif ($controllerClass == 'wm\Controller\TaskController') {
            $acl2->allow(NULL, NULL, NULL, $taskAssertion);
        } elseif ($controllerClass == 'auth\Controller\LoginController') {
            $acl2->allow(NULL, NULL, NULL, $taskAssertion);
        }else{
            $acl2->allow(NULL, NULL, NULL, $taskAssertion);
        }
//        echo $controllerClass;
        if (!$acl->isAllowed($role, $namespace, 'view')) {
            $router = $e->getRouter();
            $url = $router->assemble(array(), array('name' => 'login'));

            $response = $e->getResponse();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();
        }elseif(!$acl2->isAllowed($userRole, $resource, $operation)){
            
            $response = $e->getResponse();
            $response->setStatusCode(403);
            $model=new JsonModel($taskAssertion->getErrorIds());
            $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $e->setViewModel($model);
            $e->stopPropagation();
        }
    }

}
