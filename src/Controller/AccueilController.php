<?php
namespace App\Controller;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la page d'acceuil (et la page CGU).
 *
 * @author emds
 */
class AccueilController extends AbstractController{
    
    /**
     * Repository pour accèder aux Formations.
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * Constructeur de AcceuilController.
     * @param \App\Repository\FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }
    
    /**
     * Route pour la page d'acceuil avec les deux dernières Formations.
     * @Route("/", name: "accueil")
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("pages/accueil.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * Route pour les CGU.
     * @Route("/cgu", name: "cgu")
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig");
    }
}
