<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleur pour la connexion et déconnexion.
 */
class LoginController extends AbstractController
{
    /**
     * Route pour la page de connexion.
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(['/login'], name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // récupération éventuelle d'erreur et dernier login utilisé
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * Route pour la déconnexion.
     * @return void
     */
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        // Méthode existe juste pour créer route
    }

    /**
     * Route qui permet d'afficher la page Login en accèdant à '/admin'.
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function loginRedirect()
    {
        return $this->redirectToRoute('admin.formations');
    }

}
