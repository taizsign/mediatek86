<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * controlleur du back office des catégories
 */
class AdminCategoriesController extends AbstractController {

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * affiche toutes les catégories
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response{
        $categories = $this->categorieRepository->findAll();
        return $this->render("pages/admin/categories.html.twig", [
            'categories' => $categories
        ]);
    }

    /**
     * ajoute une catégorie si elle n'existe pas déjà
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories/ajouter', name: 'admin.categories.ajouter')]
    public function ajouter(Request $request): Response{
        $nom = $request->get('name');
        // vérifie que le nom n'est pas vide et qu'il n'existe pas déjà
        $exist = $this->categorieRepository->findOneBy(['name' => $nom]);
        if($nom && !$exist){
            $categorie = new Categorie();
            $categorie->setName($nom);
            $this->categorieRepository->add($categorie);
        }
        return $this->redirectToRoute('admin.categories');
    }

    /**
     * supprime une catégorie si elle n'a pas de formations
     * @param type $id
     * @return Response
     */
    #[Route('/admin/categories/supprimer/{id}', name: 'admin.categories.supprimer')]
    public function supprimer($id): Response{
        $categorie = $this->categorieRepository->find($id);
        // suppression uniquement si aucune formation rattachée
        if($categorie->getFormations()->isEmpty()){
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute('admin.categories');
    }

}
