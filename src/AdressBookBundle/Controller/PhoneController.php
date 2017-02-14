<?php

namespace AdressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AdressBookBundle\Entity\Phone;

class PhoneController extends Controller {

    /**
     * @Route("/{id}/addPhone")
     * @Method("GET")
     */
    public function addNewPhoneAction($id) {
        $phone = new Phone();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);

        $phone->setPerson($person);
        $action = $this->generateUrl('adressbook_phone_addnewphone', ['id' => $id]);
        $form = $this->generatePhoneForm($phone, $action);

        return $this->render('AdressBookBundle:Phone:add_new_phone.html.twig', [
                    'form' => $form->createView(), 'phone' => $phone
        ]);
    }

    /**
     * @Route("/{id}/addPhone")
     * @Method("POST")
     */
    public function createNewPhoneAction(Request $req, $id) {
        $phone = new Phone();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);
        $phone->setPerson($person);

        $form = $this->generatePhoneForm($phone, null);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $phone = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($phone);
            $em->flush();

            return $this->render('AdressBookBundle:Person:show_person.html.twig', [
                        "person" => $person]);
        }
        return $this->redirectToRoute('adressbook_phone_addnewphone');
    }

    private function generatePhoneForm($phone, $action) {
        $form = $this->createFormBuilder($phone)
                ->setAction($action)
                ->add('number', 'text')
                ->add('type', 'text')
                ->add('save', 'submit', ['label' => 'add Phone'])
                ->getForm();
        return $form;
    }

    /**
     * 
     * @Route("/{id}/deletePhone" ,requirements={"id":"\d+"})
     * 
     */
    public function deletePhoneAction($id) {
        $em = $this->getDoctrine()->getManager();
        $phoneRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Phone");
        $phoneToDelete = $phoneRepo->find($id);
        $person = $phoneToDelete->getPerson()->getId();

        if ($phoneToDelete != null) {
            $em->remove($phoneToDelete);
            $em->flush();
        }

        return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
    }

    /**
     * 
     * @Route("/{id}/modifyPhone")
     * 
     */
    public function modifyPhoneAction(Request $req, $id) {
        $phoneRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Phone");
        $phone = $phoneRepo->find($id);
        $person = $phone->getPerson()->getId();

        $form = $this->generatePhoneForm($phone, $this->generateUrl('adressbook_phone_modifyphone', ['id' => $id]));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $req->getMethod() == 'POST') {
            $phone = $form->getData();

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($phone);
            $em->flush();
            return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
        }
        return $this->render('AdressBookBundle:Phone:add_new_phone.html.twig', [
                    'form' => $form->createView(), 'phone' => $phone
        ]);
    }

}
