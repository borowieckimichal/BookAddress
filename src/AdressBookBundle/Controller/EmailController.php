<?php

namespace AdressBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AdressBookBundle\Entity\Email;

class EmailController extends Controller {

    /**
     * @Route("/{id}/addEmail")
     * @Method("GET")
     */
    public function addNewEmailAction($id) {
        $email = new Email();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);

        $email->setPerson($person);
        $action = $this->generateUrl('adressbook_email_addnewemail', ['id' => $id]);
        $form = $this->generateEmailForm($email, $action);

        return $this->render('AdressBookBundle:Email:add_new_email.html.twig', [
                    'form' => $form->createView(), 'email' => $email
        ]);
    }

    /**
     * @Route("/{id}/addEmail")
     * @Method("POST")
     */
    public function createNewEmailAction(Request $req, $id) {
        $email = new Email();
        $personRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Person");
        $person = $personRepo->find($id);
        $email->setPerson($person);

        $form = $this->generateEmailForm($email, null);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $email = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($email);
            $em->flush();

            return $this->render(
                            'AdressBookBundle:Person:show_person.html.twig', [
                        "person" => $person]);
        }
        return $this->redirectToRoute('adressbook_email_addnewemail');
    }

    private function generateEmailForm($email, $action) {
        $form = $this->createFormBuilder($email)
                ->setAction($action)
                ->add('email', 'text')
                ->add('type', 'text')
                ->add('save', 'submit', ['label' => 'add Email'])
                ->getForm();
        return $form;
    }

    /**
     * 
     * @Route("/{id}/deleteEmail" ,requirements={"id":"\d+"})
     * 
     */
    public function deleteEmailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $emailRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Email");
        $emailToDelete = $emailRepo->find($id);
        $person = $emailToDelete->getPerson()->getId();

        if ($emailToDelete != null) {
            $em->remove($emailToDelete);
            $em->flush();
        }

        return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
    }

    /**
     * 
     * @Route("/{id}/modifyEmail")
     * 
     */
    public function modifyEmailAction(Request $req, $id) {
        $mailRepo = $this->getDoctrine()->getRepository("AdressBookBundle:Email");
        $mail = $mailRepo->find($id);
        $person = $mail->getPerson()->getId();

        $form = $this->generateEmailForm($mail, $this->generateUrl('adressbook_email_modifyemail', ['id' => $id]));
        $form->handleRequest($req);

        if ($form->isSubmitted() && $req->getMethod() == 'POST') {
            $mail = $form->getData();


            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($mail);
            $em->flush();

            return $this->redirectToRoute("adressbook_person_showperon", ['id' => $person]);
        }
        return $this->render('AdressBookBundle:Email:add_new_email.html.twig', [
                    'form' => $form->createView(), 'email' => $mail
        ]);
    }

}
