<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class AuthenticatorController extends AbstractController
{

    #[Route('/authenticator/pair', name: 'app_authenticator_pair')]
    public function pair(): Response
    {
        return $this->render('authenticator/pair.html.twig');
    }

    #[Route('/authenticator/verify', name: 'app_authenticator_verify')]
    public function verify(): Response
    {
        return $this->render('authenticator/verify.html.twig');
    }
}