<?php

namespace AdressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AdressBookBundle\Entity\Adress;

class AdressController extends Controller {

    /**
     * @Route("/{id}/addAddress")
     * @Method("GET")
     */
    public function addNewAdressAction($id) {
        $adress = new Adress();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);

        $adress->setPerson($person);
        $action = $this->generateUrl('adressbook_adress_addnewadress', ['id' => $id]);
        $form = $this->generateAdressForm($adress, $action);

        return $this->render('AdressBookBundle:Adress:add_new_adress.html.twig', [
                    'form' => $form->createView(), 'adress' => $adress
        ]);
    }

    /**
     * @Route("/{id}/addAddress")
     * @Method("POST")
     */
    public function createNewAdressAction(Request $req, $id) {
        $adress = new Adress();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);
        $adress->setPerson($person);

        $form = $this->generateAdressForm($adress, null);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $adress = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($adress);
            $em->flush();

            return $this->render(
                            'AdressBookBundle:Person:show_person.html.twig', [
                        "person" => $person]);
        }
        return $this->redirectToRoute('adressbook_adress_addnewadress');
    }

    private function generateAdressForm($adress, $action) {
        $form = $this->createFormBuilder($adress)
                ->setAction($action)
                ->add('city', 'text')
                ->add('street', 'text')
                ->add('houseNo', 'integer')
                ->add('flatNo', 'integer')
                ->add('save', 'submit', ['label' => 'Create Adress'])
                ->getForm();
        return $form;
    }

    /**
     * 
     * @Route("/{id}/deleteAdress" ,requirements={"id":"\d+"})
     * 
     */
    public function deleteAdressAction($id) {
        $em = $this->getDoctrine()->getManager();
        $adressRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Adress");
        $adressToDelete = $adressRepo->find($id);
        $person = $adressToDelete->getPerson()->getId();

        if ($adressToDelete != null) {
            $em->remove($adressToDelete);
            $em->flush();
        }

        return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
    }

    /**
     * 
     * @Route("/{id}/modifyAdress")
     * 
     */
    public function modifyAdressAction(Request $req, $id) {
        $adressRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Adress");
        $adress = $adressRepo->find($id);
        $person = $adress->getPerson()->getId();

        $form = $this->generateAdressForm($adress, $this->generateUrl('adressbook_adress_modifyadress', ['id' => $id]));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $req->getMethod() == 'POST') {
            $adress = $form->getData();


            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($adress);
            $em->flush();

            return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
        }
        return $this->render('AdressBookBundle:Adress:add_new_adress.html.twig', [
                    'form' => $form->createView(), 'adress' => $adress
        ]);
    }

}
