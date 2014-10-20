<?php
//filename : module/ZfCommons/src/ZfCommons/Controller/Plugin/MyPlugin.php
namespace auth\Plugin;
  
use Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\Session\Container as SessionContainer,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource;
     
class AuthPlugin extends AbstractPlugin
{
    protected $sesscontainer ;
 
    private function getSessContainer()
    {
        if (!$this->sesscontainer) {
            $this->sesscontainer = new SessionContainer('wm_user');
        }
        return $this->sesscontainer;
    }
     
    public function doAuthorization($e)
    {
        //setting ACL...
        $acl = new Acl();
        //add role ..
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'),  'anonymous');
        $acl->addRole(new Role('admin'), 'user');
         
        $acl->addResource(new Resource('Application'));
        $acl->addResource(new Resource('auth'));
        $acl->addResource(new Resource('wm'));
        $acl->addResource(new Resource('administration'));
         
        $acl->deny('anonymous', 'Application', 'view');
        $acl->deny('anonymous', 'wm', 'view');
        $acl->deny('anonymous', 'administration', 'view');
        $acl->allow('anonymous', 'auth', 'view');
         
        $acl->allow('user',
            array('Application'),
            array('view')
        );
         
        //admin is child of user, can publish, edit, and view too !
        $acl->allow('admin',
            array('Application'),
            array('publish', 'edit')
        );
        $acl->allow('admin',
            array('wm'),
            array('publish', 'view')
        );
        $acl->allow('admin',
            array('administration'),
            array('publish', 'view')
        );
         
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $rqst=$e->getRequest();
        $id=$rqst->getQuery('id');
        $operation=$rqst->isGET()&&$id?'GET':
                ($rqst->isGET()?'LIST':
                ($rqst->isPUT()?'UPDATE':
                ($rqst->isPOST()?'CREATE':
                ($rqst->isDELETE()?'DELETE':''
                        ))));
        
         echo $operation;
        $role = (! $this->getSessContainer()->role ) ? 'anonymous' : $this->getSessContainer()->role;
        if ( ! $acl->isAllowed($role, $namespace, 'view')){
            $router = $e->getRouter();
            $url    = $router->assemble(array(), array('name' => 'login'));
         
            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            /* change with header('location: '.$url); if code below not working */
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();            
        }
    }
}