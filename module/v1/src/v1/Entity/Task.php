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

class Task {

    public function __construct() {
        $this->executors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->curators = new \Doctrine\Common\Collections\ArrayCollection();
        $this->necessary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sufficiently = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invNecessary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invSufficiently = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return datetime
     */
    public function getStartDateTime() {
        return $this->startDateTime;
    }

    /**
     * 
     * @return datetime
     */
    public function getFinishDateTime() {
        return $this->finishDateTime;
    }

    /**
     * 
     * @return string
     */
    public function getAbout() {
        return $this->about;
    }

    /**
     * 
     * @return array
     */
    public function getExecutors() {
        return $this->executors;
    }

    /**
     * 
     * @return array
     */
    public function getCurators() {
        return $this->curators;
    }

    /**
     * 
     * @return array
     */
    public function getNecessary() {
        return $this->necessary;
    }

    /**
     * 
     * @return array
     */
    public function getSufficiently() {
        return $this->sufficiently;
    }

    /**
     * 
     * @return array
     */
    public function getInvNecessary() {
        return $this->invNecessary;
    }

    /**
     * 
     * @return array
     */
    public function getInvSufficiently() {
        return $this->invSufficiently;
    }

    /**
     * 
     * @return array
     */
    public function getConsequence() {
        return array_merge($this->getInvNecessary(), $this->getInvSufficiently());
    }

    /**
     * 
     * @@param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @@param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param datetime $startDateTime
     */
    public function setStartDateTime($startDateTime) {
        $this->startDateTime = $startDateTime;
    }

    /**
     * 
     * @param datetime $finishDateTime
     */
    public function setFinishDateTime($finishDateTime) {
        $this->finishDateTime = $finishDateTime;
    }

    /**
     * 
     * @param string $about
     */
    public function setAbout($about) {
        $this->about = $about;
    }

    /**
     * 
     * @param array $executors
     */
    public function setExecutors($executors) {
        $this->executors = $executors;
    }

    /**
     * 
     * @param array $curators
     */
    public function setCurators($curators) {
        $this->curators = $curators;
    }

    /**
     * 
     * @param array $necessary
     */
    public function setNecessary($necessary) {
        $this->necessary = $necessary;
    }

    /**
     * 
     * @param array $sufficiently
     */
    public function setSufficiently($sufficiently) {
        $this->sufficiently = $sufficiently;
    }

    /**
     * 
     * @param array $invNecessary
     */
    public function setInvNecessary($invNecessary) {
        $this->invNecessary = $invNecessary;
    }

    /**
     * 
     * @param array $invSufficiently
     */
    public function setInvSufficiently($invSufficiently) {
        $this->invSufficiently = $invSufficiently;
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
    protected $startDateTime;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $finishDateTime;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $about;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="executeTasks")
     * @JoinTable(name="tasks_executors")
     * */
    protected $executors;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="curateTasks")
     * @JoinTable(name="users_curators")
     * */
    protected $curators;

    /**
     * @ManyToMany(targetEntity="Task", mappedBy="invNecessary")
     * */
    protected $necessary;

    /**
     * @ManyToMany(targetEntity="Task", mappedBy="invSufficiently")
     * */
    protected $sufficiently;

    /**
     * @ManyToMany(targetEntity="Task", inversedBy="necessary")
     * @JoinTable(name="necessarys",
     *      joinColumns={@JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="necessary_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $invNecessary;

    /**
     * @ManyToMany(targetEntity="Task", inversedBy="sufficiently")
     * @JoinTable(name="sufficientlys",
     *      joinColumns={@JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="sufficiently_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $invSufficiently;

}
