<?php

namespace AdressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AdressBookBundle\Entity\Person;
use AdressBookBundle\Entity\User;

class PersonController extends Controller {

    /**
     * @Route("/")
     */
    public function showIndexAction() {
        $repo = $this->getDoctrine()->getRepository('AdressBookBundle:Person');

        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();
        $user->getId();

        if ($user instanceof User) {

            $persons = $repo->getAllPersonsBySurnameAscending($user);
            return $this->render(
                            'AdressBookBundle:Person:show_index.html.twig', [
                        "persons" => $persons,
            ]);
        }
        return $this->redirectToRoute("fos_user_security_login");
    }

    /**
     * 
     * @Route("/{id}", requirements={"id":"\d+"})
     */
    public function showPeronAction($id) {
        $repo = $this->getDoctrine()->getRepository('AdressBookBundle:Person');
        $person = $repo->find($id);
        
        if ($person == null) {
            throw $this->createNotFoundException('nie ma użytkownika z takim ID !!!');
        }
        
        $this->checkPerson($person);
        return $this->render(
                        'AdressBookBundle:Person:show_person.html.twig', [
                    "person" => $person,
        ]);
    }

    /**
     * @Route("/new")
     * @Method("GET")
     * 
     */
    public function addNewPersonAction() {
        $person = new Person();
        $action = $this->generateUrl('adressbook_person_addnewperson');
        $form = $this->generateForm($person, $action);

        return $this->render('AdressBookBundle:Person:new_person.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new")
     * @Method("POST")
     */
    public function createNewPersonAction(Request $req) {

        $person = new Person();
        $form = $this->generateForm($person, null);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {//&& $form->isValid()) {
            $user = $this->container
                    ->get('security.context')
                    ->getToken()
                    ->getUser();
            $user->getId();




            $person = $form->getData();
            $person->setBaseUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('adressbook_person_showindex');
        }
        return $this->redirectToRoute('adressbook_person_addnewperson');
        //return $this->render('AdressBookBundle:Person:new_person.html.twig', [
        // 'form' => $form->createView(),
        //]);
    }

    private function generateForm($person, $action) {
        $form = $this->createFormBuilder($person)
                ->setAction($action)
                ->add('name', 'text')
                ->add('surname', 'text')
                ->add('description', 'text')
                ->add('save', 'submit', ['label' => 'Create Person'])
                ->getForm();
        return $form;
    }

    /**
     * 
     * @Route("/{id}/modify")
     * 
     */
    public function modifyPersonAction(Request $req, $id) {
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);

        $this->checkPerson($person);

        $form = $this->generateForm($person, $this->generateUrl('adressbook_person_modifyperson', ['id' => $id]));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $req->getMethod() == 'POST') {
            $person = $form->getData();

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('adressbook_person_showindex');
        }
        return $this->render('AdressBookBundle:Person:new_person.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @Route("/{id}/delete" ,requirements={"id":"\d+"})
     * 
     */
    public function deletePersonAction($id) {
        $em = $this->getDoctrine()->getManager();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $personToDelete = $personRepo->find($id);



        if ($personToDelete != null) {
            $this->checkPerson($personToDelete);
            $em->remove($personToDelete);
            $em->flush();
        }
        
        return $this->redirectToRoute("adressbook_person_showindex");
    }

    private function checkPerson($person) {
        $user = $this->container
                ->get('security.context')
                ->getToken()
                ->getUser();

        if ($user !== $person->getBaseUser()) {
            throw $this->createAccessDeniedException();
        }
    }

}
