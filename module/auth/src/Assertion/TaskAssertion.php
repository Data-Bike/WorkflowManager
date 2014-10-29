<?php

/*
 * Copyright (c) 2014, Alexander Platonov
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace auth\Assertion;

use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Session\Container;
use Doctrine;

class TaskAssertion implements AssertionInterface {

    private $errorIds;
    
    public function getErrorIds() {
        return $this->errorIds;
    }

    public function assert(\Zend\Permissions\Acl\Acl $acl, \Zend\Permissions\Acl\Role\RoleInterface $role = null, \Zend\Permissions\Acl\Resource\ResourceInterface $resource = null, $privilege = null) {
        $sessContainer = new Container('wm_user');
        $id = $role->getRoleId();
        $em = $role->getEntityManager();
        if (!$id) {
//            return FALSE;
        }
        if ($privilege == 'LIST') {
            return TRUE;
        } elseif ($privilege == 'GET') {
            return TRUE;
        } elseif ($privilege == 'auth') {
            return TRUE;
        } elseif ($privilege == 'UPDATE') {
            $curators = $resource->getRequest()->getPost('curatorsList');
            $executors = $resource->getRequest()->getPost('executorsList');
            print($executors);
            $users = $curators . ',' . $executors;
            $q = $em->createQuery("SELECT m.id FROM wm\Entity\User u INNER JOIN u.myTasks m WITH u.id=$id");
            foreach ($q->getResult() as $value) {
                $tasks[] = $value['id'];
            }
            $q = $em->createQuery("SELECT m.id FROM wm\Entity\User u INNER JOIN u.members m WITH u.id=$id");
            foreach ($q->getResult() as $value) {
                $members[] = $value['id'];
            }

            return count(array_diff(explode(',', $users), $members)) == 0;
        } elseif ($privilege == 'CREATE') {
            $content = $resource->getRequest()->getContent();
            $rqst = \Zend\Json\Json::decode($content, \Zend\Json\Json::TYPE_OBJECT);
            $curators = $rqst->curatorsList;
            $executors = $rqst->executorsList;
            $users = $curators . ($curators && $executors ? ',' : '') . $executors;
            $q = $em->createQuery("SELECT t.id FROM wm\Entity\User u INNER JOIN u.myTasks t WITH u.id=$id");
            foreach ($q->getResult() as $value) {
                $tasks[] = $value['id'];
            }
            $q = $em->createQuery("SELECT m.id FROM wm\Entity\User u INNER JOIN u.members m WITH u.id=$id");
            $members = array();
            foreach ($q->getResult() as $value) {
                $members[] = $value['id'];
            }
            
            $this->errorIds=[users=>$users?array_diff(explode(',', $users), $members):[]];
            return count($this->errorIds['users']) == 0;
        } elseif ($privilege == 'DELETE') {

            $taskId = $resource->getRequest()->getQuery('id');
            $q = $em->createQuery("SELECT m.id FROM wm\Entity\User u INNER JOIN u.members m WITH u.id=$id");

            $members = implode(',', $q->getResult());
            $q = $em->createQuery("SELECT t.id FROM wm\Entity\Task t INNER JOIN t.owner o WHERE o.id IN ($members)");
            return count($q->getResult()) > 0;
        }

        return FALSE;
    }

}
