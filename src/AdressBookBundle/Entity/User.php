<?php

namespace AdressBookBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\OneToMany(targetEntity="Person", mappedBy="baseUser")
     */
    private $persons;

    public function __construct() {
        parent::__construct();
        $this->persons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add persons
     *
     * @param \AdressBookBundle\Entity\Person $persons
     * @return User
     */
    public function addPerson(\AdressBookBundle\Entity\Person $persons) {
        $this->persons[] = $persons;

        return $this;
    }

    /**
     * Remove persons
     *
     * @param \AdressBookBundle\Entity\Person $persons
     */
    public function removePerson(\AdressBookBundle\Entity\Person $persons) {
        $this->persons->removeElement($persons);
    }

    /**
     * Get persons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersons() {
        return $this->persons;
    }

}
