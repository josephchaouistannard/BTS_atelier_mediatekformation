<?php

namespace App\Controller\admin;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des Playlists dans l'interface d'administration.
 *
 * Ce contrôleur permet aux administrateurs de visualiser, trier, filtrer,
 * ajouter, modifier et supprimer des playlists.
 *
 * @author joseph chaoui-stannard
 */
class AdminPlaylistsController extends AbstractController {
    
    /**
     * Repository de la classe Playlist.
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
     * Constructeur de la classe AdminPlaylistsController.
     * @param PlaylistRepository $playlistRepository Le dépôt des playlists.
     * @param CategorieRepository $categorieRepository Le dépôt des catégories.
     * @param FormationRepository $formationRespository Le dépôt des formations.
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * Chemin vers le template Twig pour l'affichage des playlists dans l'administration.
     */
    private const CHEMIN_PLAYLISTS = "admin/admin.playlists.html.twig";
    
    /**
     * Affiche la page d'administration des playlists.
     *
     * @Route('/admin/playlists', name: 'admin.playlists')
     * @return Response La réponse HTTP contenant la page d'administration des playlists rendue.
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les playlists selon un champ et un ordre spécifiés.
     *
     * @Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')
     * @param string $champ Le champ sur lequel trier (ex: 'name', 'nbFormations').
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @return Response La réponse HTTP contenant la page d'administration des playlists triées.
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        if($champ == "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif($champ == "nbFormations") {
            $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche les playlists contenant une valeur spécifique dans un champ donné.
     *
     * @Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')
     * @param string $champ Le champ sur lequel appliquer le filtre.
     * @param Request $request L'objet Request contenant la valeur de recherche.
     * @param string $table Le nom de la table si le champ de recherche se trouve dans une entité liée.
     * @return Response La réponse HTTP contenant la page d'administration des playlists filtrées.
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::CHEMIN_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Supprime une playlist spécifique.
     *
     * @Route('/admin/playlist/supprimer/{id}', name: 'admin.playlist.delete')
     * @param int $id L'identifiant de la playlist à supprimer.
     * @return Response Redirige vers la page d'administration des playlists après la suppression.
     */
    #[Route('/admin/playlist/supprimer/{id}', name: 'admin.playlist.delete')]
    public function deletePlaylist(int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * Affiche le formulaire d'édition d'une playlist et gère sa soumission.
     *
     * @Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')
     * @param int $id L'identifiant de la playlist à modifier.
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant le formulaire d'édition ou une redirection.
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlist.edit')]
    public function editPlaylist(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/admin.playlist.edit.html.twig', [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'une nouvelle playlist et gère sa soumission.
     *
     * @Route('/admin/playlist/add', name: 'admin.playlist.add')
     * @param Request $request L'objet Request contenant les données du formulaire.
     * @return Response La réponse HTTP contenant le formulaire d'ajout ou une redirection.
     */
    #[Route('/admin/playlist/add', name: 'admin.playlist.add')]
    public function addPlaylist(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }

        return $this->render('admin/admin.playlist.add.html.twig', [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
}
