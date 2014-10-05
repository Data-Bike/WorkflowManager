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

namespace v1\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity */
class User {

    public function __construct() {
        $this->executeTasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->curateTasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * 
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * 
     * @return array
     */
    public function getExecuteTasks() {
        return $this->executeTasks;
    }

    /**
     * 
     * @return array
     */
    public function getCurateTasks() {
        return $this->curateTasks;
    }

    /**
     * 
     * @return array
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return array
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    /**
     * 
     * @return array
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * 
     * @return array
     */
    public function setExecuteTasks($executeTasks) {
        $this->executeTasks = $executeTasks;
    }

    /**
     * 
     * @return array
     */
    public function setCurateTasks($curateTasks) {
        $this->curateTasks = $curateTasks;
    }

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $position;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="executors")
     * */
    protected $executeTasks;

    /**
     * @ORM\ManyToMany(targetEntity="Task", mappedBy="curators")
     * */
    protected $curateTasks;

}
