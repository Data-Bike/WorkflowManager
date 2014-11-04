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

namespace wm\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/** @ORM\Entity 
 * @ORM\Entity (repositoryClass="wm\Repository\TaskRepository") 
 */
class Task {

    public function __construct() {
        $this->executors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->curators = new \Doctrine\Common\Collections\ArrayCollection();
        $this->necessary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sufficiently = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invNecessary = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invSufficiently = new \Doctrine\Common\Collections\ArrayCollection();
        $this->finishDateTime = new \DateTime();
        $this->startDateTime = new \DateTime();
    }

    public function canYouChange() {
        return TRUE;
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
     * @return DateTime
     */
    public function getStartDateTime() {
        return $this->startDateTime;
    }

    /**
     * 
     * @return DateTime
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
     * @return  \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCurators() {
        return $this->curators;
    }

    /**
     * 
     * @return  \Doctrine\Common\Collections\ArrayCollection
     */
    public function getNecessary() {
        return $this->necessary;
    }

    /**
     * 
     * @return  \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSufficiently() {
        return $this->sufficiently;
    }

    /**
     * 
     * @return  \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInvNecessary() {
        return $this->invNecessary;
    }

    /**
     * 
     * @return  \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInvSufficiently() {
        return $this->invSufficiently;
    }

    /**
     * 
     * @return  \Doctrine\Common\Collections\ArrayCollection
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
     * @param DateTime $startDateTime
     */
    public function setStartDateTime($startDateTime) {
        $this->startDateTime = $startDateTime;
    }

    /**
     * 
     * @param DateTime $finishDateTime
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
     * @param  \Doctrine\Common\Collections\ArrayCollection $executors
     */
    public function setExecutors($executors) {
        $this->executors = $executors;
    }

    /**
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $curators
     */
    public function setCurators($curators) {
        $this->curators = $curators;
    }

    /**
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $necessary
     */
    public function setNecessary($necessary) {
        $this->necessary = $necessary;
    }

    /**
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $sufficiently
     */
    public function setSufficiently($sufficiently) {
        $this->sufficiently = $sufficiently;
    }

    /**
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $invNecessary
     */
    public function setInvNecessary($invNecessary) {
        $this->invNecessary = $invNecessary;
    }

    /**
     * 
     * @param  \Doctrine\Common\Collections\ArrayCollection $invSufficiently
     */
    public function setInvSufficiently($invSufficiently) {
        $this->invSufficiently = $invSufficiently;
    }

    /**
     * 
     * @return  wm\Entity\User
     */
    function getOwner() {
        return $this->owner;
    }

    /**
     * 
     * @param  wm\Entity\User $owner
     */
    function setOwner($owner) {
        $this->owner = $owner;
    }
    
    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'startDateTime' => $this->startDateTime->format(\DateTime::W3C),
            'finishDateTime' => $this->finishDateTime->format(\DateTime::W3C),
            'about' => $this->about
        ];
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
     * @var DateTime
     * @ORM\Column(type="datetime", length=255, nullable=false)
     */
    protected $startDateTime;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", length=255, nullable=false)
     */
    protected $finishDateTime;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $about;

    /**
     * @ORM\ManyToOne(targetEntity="wm\Entity\User", inversedBy="myTasks")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * */
    protected $owner;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\User", inversedBy="executeTasks") 
     * @ORM\JoinTable(name="executors_tasks",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="executor_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $executors;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\User", inversedBy="curateTasks")
     * @ORM\JoinTable(name="curators_tasks",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="curator_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $curators;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\Task", mappedBy="invNecessary", fetch="EAGER")
     * 
     * */
    protected $necessary;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\Task", mappedBy="invSufficiently")
     * */
    protected $sufficiently;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\Task", inversedBy="necessary")
     * @ORM\JoinTable(name="necessarys",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="necessary_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $invNecessary;

    /**
     * @ORM\ManyToMany(targetEntity="wm\Entity\Task", inversedBy="sufficiently")
     * @ORM\JoinTable(name="sufficientlys",
     *      joinColumns={@ORM\JoinColumn(name="task_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="sufficiently_task_id", referencedColumnName="id")}
     *      )
     * */
    protected $invSufficiently;

}
