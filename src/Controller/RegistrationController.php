<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Picture;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Service\MediaManageService;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, MediaManageService $media): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We recover the transmitted picture 
            $picture = $form->get('picture')->getData();

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // we add the picture to the user 
            if (empty($picture)) {
                // we attribute to the user the default picture (here persona.png)
                $pictureUser = new Picture();
                $pictureUser->setPictureFileName($this->getParameter('pictureDefault'));
                $pictureUser->setAlt('pictureDefaultUser');
                $user->setPicture($pictureUser);
            } else {
                // addition (physically) of an uploader picture on the server
                $newPicture = $media->addImageOnServer($picture);

                // we attribute to the user the picture upload 
                $user->setPicture($newPicture);
            }

            // we add the user to the database 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('emailRegistra@snowtricks.com', 'emailRegitraSnowtricks'))
                    ->to($user->getEmail())
                    ->subject('Confirmer votre Email d\'inscription sur Snowtricks')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            // addition of a flash message
            $this->addFlash('accountToCreate', 'Votre compte a bien été créé et est en attente de validation de votre par dans votre boite email.');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a bien été vérifié.');

        return $this->redirectToRoute('home');
    }
}
