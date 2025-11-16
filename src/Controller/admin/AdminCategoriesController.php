<?php

namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des Catégories dans l'interface d'administration.
 *
 * Ce contrôleur permet aux administrateurs de visualiser, trier, filtrer,
 * et ajouter des catégories, ainsi que de supprimer des catégories existantes.
 *
 * @author joseph chaoui-stannard
 */
class AdminCategoriesController extends AbstractController {
    
    /**
     * Le dépôt pour accéder aux données des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur de la classe AdminCategoriesController.
     *
     * Injecte le CategorieRepository pour permettre l'accès aux données des catégories.
     *
     * @param CategorieRepository $categorieRepository Le dépôt des catégories.
     */
    public function __construct(
        CategorieRepository $categorieRepository,
    ) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Chemin vers le template Twig pour l'affichage des catégories dans l'administration.
     */
    private const CHEMIN_CATEGORIES = "admin/admin.categories.html.twig";
    
    /**
     * Affiche la page d'administration des catégories et gère l'ajout d'une nouvelle catégorie.
     *
     * Récupère toutes les catégories triées par nom et affiche un formulaire pour en ajouter une nouvelle.
     *
     * @Route('/admin/categories', name: 'admin.categories')
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant la page d'administration des catégories rendue ou une redirection.
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response{

        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);

        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }

        $categories = $this->categorieRepository->findAllOrderByName('ASC');
        return $this->render(self::CHEMIN_CATEGORIES, [
            'categories' => $categories,
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }

    /**
     * Trie les catégories selon un champ et un ordre spécifiés, et gère l'ajout d'une nouvelle catégorie.
     *
     * @Route('/admin/categories/tri/{champ}/{ordre}', name: 'admin.categories.sort')
     * @param string $champ Le champ sur lequel trier (ex: 'name', 'nbFormations').
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant la page d'administration des catégories triées ou une redirection.
     */
    #[Route('/admin/categories/tri/{champ}/{ordre}', name: 'admin.categories.sort')]
    public function sort($champ, $ordre, Request $request): Response{

        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);

        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }

        if($champ == "name") {
            $categories = $this->categorieRepository->findAllOrderByName($ordre);
        } elseif($champ == "nbFormations") {
            $categories = $this->categorieRepository->findAllOrderByNbFormations($ordre);
        }
        return $this->render(self::CHEMIN_CATEGORIES, [
            'categories' => $categories,
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }

    /**
     * Recherche les catégories contenant une valeur spécifique dans un champ donné, et gère l'ajout d'une nouvelle catégorie.
     *
     * @Route('/admin/categories/recherche/{champ}/{table}', name: 'admin.categories.findallcontain')
     * @param string $champ Le champ sur lequel appliquer le filtre (actuellement non utilisé pour la recherche de catégories).
     * @param Request $request L'objet Request contenant la valeur de recherche.
     * @param string $table Le nom de la table si le champ de recherche se trouve dans une entité liée (par défaut vide).
     * @return Response La réponse HTTP contenant la page d'administration des catégories filtrées ou une redirection.
     */
    #[Route('/admin/categories/recherche/{champ}/{table}', name: 'admin.categories.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{

        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);

        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $this->categorieRepository->add($categorie);
            return $this->redirectToRoute('admin.categories');
        }

        $valeur = $request->get("recherche");
        $categories = $this->categorieRepository->findByContainValue($valeur);
        return $this->render(self::CHEMIN_CATEGORIES, [
            'categories' => $categories,
            'valeur' => $valeur,
            'categorie' => $categorie,
            'formcategorie' => $formCategorie->createView()
        ]);
    }

    /**
     * Supprime une catégorie spécifique.
     *
     * @Route('/admin/categorie/supprimer/{id}', name: 'admin.categorie.delete')
     * @param int $id L'identifiant de la catégorie à supprimer.
     * @return Response Redirige vers la page d'administration des catégories après la suppression.
     */
    #[Route('/admin/categorie/supprimer/{id}', name: 'admin.categorie.delete')]
    public function deleteCategorie(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
    
}
