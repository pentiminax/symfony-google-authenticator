<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\AuthenticatorService;
use OTPHP\TOTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method User getUser()
 */
class AuthenticatorController extends AbstractController
{
    public function __construct(
        private readonly AuthenticatorService $authenticatorService
    )
    {
    }

    #[Route('/authenticator/pair', name: 'app_authenticator_pair')]
    public function pair(Request $request): Response
    {
        if ($request->isMethod(Request::METHOD_POST)) {
            $secret = $request->request->get('secret');

            $this->authenticatorService->validatePairing($this->getUser(), $secret);

            return $this->redirectToRoute('app_home_index');
        }

        [$qrCodeUri, $secret] = $this->authenticatorService->getQrCodeUri($this->getUser());

        return $this->render('home/index.html.twig', [
            'qrCodeUri' => $qrCodeUri,
            'secret' => $secret
        ]);
    }

    #[Route('/authenticator/verify', name: 'app_authenticator_verify')]
    public function verify(Request $request): Response
    {
        if (null === $this->getUser()->getSecret()) {
            return $this->redirectToRoute('app_authenticator_pair');
        }

        $totp = TOTP::createFromSecret($this->getUser()->getSecret());

        if ($request->isMethod(Request::METHOD_POST)) {
            $otp = $request->request->get('otp');

            $result = $totp->verify($otp);
        }

        return $this->render('authenticator/verify.html.twig', [
            'result' => $result ?? null
        ]);
    }
}