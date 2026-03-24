<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controlleur du back office des playlists
 */
class AdminPlaylistsController extends AbstractController {

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * affiche toutes les playlists
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * affiche les playlists triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbrformations":
                $playlists = $this->playlistRepository->findAllOrderByFormationsCount($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * affiche les playlists dont un champ contient la valeur recherchée
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * supprime une playlist si elle n'a pas de formations
     * @param type $id
     * @return Response
     */
    #[Route('/admin/playlists/supprimer/{id}', name: 'admin.playlists.supprimer')]
    public function supprimer($id): Response{
        $playlist = $this->playlistRepository->find($id);
        // suppression uniquement si aucune formation rattachée
        if($playlist->getFormations()->isEmpty()){
            $this->playlistRepository->remove($playlist);
        }
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * affiche le formulaire d'ajout et enregistre la playlist
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/ajouter', name: 'admin.playlists.ajouter')]
    public function ajouter(Request $request): Response{
        if($request->getMethod() == "POST") {
            $playlist = new Playlist();
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("pages/admin/playlist_form.html.twig", [
            'playlist' => null
        ]);
    }

    /**
     * affiche le formulaire de modification et enregistre les changements
     * @param type $id
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/modifier/{id}', name: 'admin.playlists.modifier')]
    public function modifier($id, Request $request): Response{
        $playlist = $this->playlistRepository->find($id);
        if($request->getMethod() == "POST") {
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("pages/admin/playlist_form.html.twig", [
            'playlist' => $playlist,
            'playlistformations' => $playlistFormations
        ]);
    }

}
