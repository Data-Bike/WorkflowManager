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

namespace wm\Repository;

use Doctrine\ORM\EntityRepository;
use wm\Entity;

class TaskRepository extends EntityRepository {

    public function getTasksByParams($params) {
        $q=$this->_em->createQuery("SELECT t FROM wm\Entity\Task t "
                . ($params['executorsList']?"INNER JOIN t.executors e WITH e.id IN (:executorsList) ":' ')
                . ($params['curatorsList']?"INNER JOIN t.curators c WITH c.id IN (:curatorsList) ":' ')
                . ($params['necessaryList']?"INNER JOIN t.necessary n WITH n.id IN (:necessaryList) ":' ')
                . ($params['sufficientlyList']?"INNER JOIN t.sufficiently s WITH s.id IN (:sufficientlyList) ":' ')
                . ($params['name']||$params['about']||$params['startDateTime']||$params['finishDateTime']?"WHERE ":'')
                . ($params['name']?"t.name LIKE :name ":'')
                . ($params['about']?(($params['name']?"AND":'')." t.about LIKE :about "):'')
                . ($params['startDateTime']?(($params['about']||$params['name']?"AND":'')." t.startDateTime = :startDateTime "):'')
                . ($params['finishDateTime']?(($params['startDateTime']||$params['about']||$params['name']?"AND":'')." t.finishDateTime = :finishDateTime "):'')
                )->setParameters($params);
        return $q->getResult();
        
    }

}
