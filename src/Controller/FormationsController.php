<?php
namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Contrôleur pour les pages des Formations.
 *
 * @author emds
 */
class FormationsController extends AbstractController
{

    /**
     * Repository pour la classe Formation.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Repository pour la classe Categorie.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur de la classe.
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Template de la page qui liste les formations.
     */
    private const CHEMIN_FORMATIONS = "pages/formations.html.twig";

    /**
     * Route pour voir les Formations.
     * @Route("/formations", name: "formations")
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route pour voir les Formations triées.
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name: "formations.sort")
     * @param string $champ Le champ sur lequel il faut triér
     * @param string $ordre L'ordre ASC ou DESC
     * @param string $table Si le champs est dans une autre table
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Route pour voir les Formations qui correspondent au filtre appliqué.
     * @Route("/formations/recherche/{champ}/{table}", name: "formations.findallcontain")
     * @param string $champ Champ sur lequelle il faut filtrer
     * @param Request $request
     * @param string $table Si le champ est dans une autre table
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Route pour voir le détail d'une Formation.
     * @Route("/formations/formation/{id}", name: "formations.showone")
     * @param int $id ID de la Formation
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
            'formation' => $formation
        ]);
    }

}