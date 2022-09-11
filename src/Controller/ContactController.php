<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $phone   = $form->get('telephone')->getData();
            $content = $form->get('content')->getData();


            $email = (new TemplatedEmail())
            ->from($address)
            ->to('admin@admin.com')
            ->subject($subject)
            ->text($content)

            ->htmlTemplate('contact/signup.html.twig')
        
            ->context([
                'mail'=> $address,
                'telephone'=> $phone,
                'subject' => $subject,
                'content' => $content,
            ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_success');

        }

        return $this->renderForm('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form
        ]);
    }

    #[Route('/contact/success', name: 'app_success')]
    public function success(): Response
    {
        return $this->render('success/index.html.twig', [
            'controller_name' => 'SuccessController',
        ]);
    }

}
