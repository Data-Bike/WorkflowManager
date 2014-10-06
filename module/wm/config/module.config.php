<?php

/**
 * Конфиг модуля WorkflowManager
 *
 * @author cawa
 */

namespace wm;

return array(
    'router' => array(
        'routes' => array(
            'task' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/task[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*/?',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'wm\Controller',
                        'controller' => 'wm\Controller\Task',
                        'action' => 'index'
                    ),
                ),
            ),
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*/?',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'wm\Controller',
                        'controller' => 'wm\Controller\User',
                        'action' => 'index'
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'wm\Controller\Task' => 'wm\Controller\TaskController',
            'wm\Controller\User' => 'wm\Controller\UserController',
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5'
    ),
    'doctrine' => array(
        'driver' => array(
            'wm_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/wm/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'wm\Entity' => 'wm_entity',
                )
            )
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
