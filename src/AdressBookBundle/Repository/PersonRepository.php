<?php

namespace AdressBookBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PersonRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PersonRepository extends EntityRepository {

    public function getAllPersonsBySurnameAscending() {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
                "SELECT p FROM AdressBookBundle:Person p ORDER BY p.surname ASC");

        return $query->getResult();
    }

}
