<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur du back office des formations
 *
 * @author emds
 */
class AdminFormationsController extends AbstractController {

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    function __construct(FormationRepository $formationRepository,
            CategorieRepository $categorieRepository,
            PlaylistRepository $playlistRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/formations/supprimer/{id}', name: 'admin.formations.supprimer')]
    public function supprimer($id): Response {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/admin/formations/ajouter', name: 'admin.formations.ajouter')]
    public function ajouter(Request $request): Response {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        if($request->isMethod('POST')) {
            $formation = new Formation();
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));

            $date = $request->get('publishedAt');
            if($date) {
                $formation->setPublishedAt(new \DateTime($date));
            }

            $playlistId = $request->get('playlist');
            if($playlistId) {
                $playlist = $this->playlistRepository->find($playlistId);
                $formation->setPlaylist($playlist);
            }

            $categorieIds = $request->get('categories', []);
            foreach($categorieIds as $categorieId) {
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }

            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("pages/admin/formation_form.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'formation' => null
        ]);
    }

    #[Route('/admin/formations/modifier/{id}', name: 'admin.formations.modifier')]
    public function modifier($id, Request $request): Response {
        $formation = $this->formationRepository->find($id);
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        if($request->isMethod('POST')) {
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));

            $date = $request->get('publishedAt');
            if($date) {
                $formation->setPublishedAt(new \DateTime($date));
            }

            $playlistId = $request->get('playlist');
            if($playlistId) {
                $playlist = $this->playlistRepository->find($playlistId);
                $formation->setPlaylist($playlist);
            }

            foreach($formation->getCategories() as $cat) {
                $formation->removeCategory($cat);
            }
            $categorieIds = $request->get('categories', []);
            foreach($categorieIds as $categorieId) {
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }

            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }

        return $this->render("pages/admin/formation_form.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'formation' => $formation
        ]);
    }

}
