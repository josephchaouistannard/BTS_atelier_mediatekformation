<?php

namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Form\FormationType;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Contrôleur pour la gestion des Formations dans l'interface d'administration.
 *
 * Ce contrôleur permet aux administrateurs de visualiser, trier, filtrer,
 * ajouter, modifier et supprimer des formations.
 *
 * @author joseph chaoui-stannard
 */
class AdminFormationsController extends AbstractController
{

    /**
     * Repository de la classe Formation.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Repository de la classe Categorie.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur de la classe AdminFormationsController.
     *
     * Injecte les dépôts nécessaires pour accéder aux données des formations et catégories.
     *
     * @param FormationRepository $formationRepository Le dépôt des formations.
     * @param CategorieRepository $categorieRepository Le dépôt des catégories.
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Chemin vers le template Twig pour l'affichage des formations dans l'administration.
     */
    private const CHEMIN_FORMATIONS = "admin/admin.formations.html.twig";

    /**
     * Affiche la page d'administration des formations.
     *
     * Récupère toutes les formations et toutes les catégories, puis les passe à la vue.
     *
     * @Route('/admin/formations', name: 'admin.formations')
     * @return Response La réponse HTTP contenant la page d'administration des formations rendue.
     */
    #[Route('/admin/formations', name: 'admin.formations')]
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
     * Trie les formations selon un champ, un ordre et éventuellement une table liée spécifiés.
     *
     * @Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')
     * @param string $champ Le champ sur lequel trier.
     * @param string $ordre L'ordre de tri ('ASC' ou 'DESC').
     * @param string $table Le nom de la table si le champ de tri se trouve dans une entité liée (par défaut vide).
     * @return Response La réponse HTTP contenant la page d'administration des formations triées.
     */
    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
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
     * Recherche les formations contenant une valeur spécifique dans un champ donné.
     *
     * @Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')
     * @param string $champ Le champ sur lequel appliquer le filtre.
     * @param Request $request L'objet Request contenant la valeur de recherche.
     * @param string $table Le nom de la table si le champ de recherche se trouve dans une entité liée (par défaut vide).
     * @return Response La réponse HTTP contenant la page d'administration des formations filtrées.
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
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
     * Supprime une formation spécifique.
     *
     * @Route('/admin/formation/supprimer/{id}', name: 'admin.formation.delete')
     * @param int $id L'identifiant de la formation à supprimer.
     * @return Response Redirige vers la page d'administration des formations après la suppression.
     */
    #[Route('/admin/formation/supprimer/{id}', name: 'admin.formation.delete')]
    public function deleteFormation(int $id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Affiche le formulaire d'édition d'une formation et gère sa soumission.
     *
     * @Route('/admin/formation/edit/{id}', name: 'admin.formation.edit')
     * @param int $id L'identifiant de la formation à modifier.
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant le formulaire d'édition ou une redirection.
     */
    #[Route('/admin/formation/edit/{id}', name: 'admin.formation.edit')]
    public function editFormation(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/admin.formation.edit.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'une nouvelle formation et gère sa soumission.
     *
     * @Route('/admin/formation/add', name: 'admin.formation.add')
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant le formulaire d'ajout ou une redirection.
     */
    #[Route('/admin/formation/add', name: 'admin.formation.add')]
    public function addFormation(Request $request): Response
    {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render('admin/admin.formation.add.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }

}
