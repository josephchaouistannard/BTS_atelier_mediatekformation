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
 * Description of AdminCategoriesController
 *
 * @author joseph chaoui-stannard
 */
class AdminCategoriesController extends AbstractController {
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(
        CategorieRepository $categorieRepository,
    ) {
        $this->categorieRepository = $categorieRepository;
    }

    private const CHEMIN_CATEGORIES = "admin/admin.categories.html.twig";
    
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

    #[Route('/admin/categorie/supprimer/{id}', name: 'admin.categorie.delete')]
    public function deleteCategorie(int $id): Response
    {
        $categorie = $this->categorieRepository->find($id);
        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }
    
}
