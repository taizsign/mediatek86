<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les playlists triées sur le nombre de formations
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByFormationsCount($ordre): array{
        $playlists = $this->findAll();
        $nbrFormations = [];
        foreach($playlists as $p){
            $nbrFormations[] = count($p->getFormations());
        }
        if($ordre == 'ASC'){
            array_multisort($nbrFormations, SORT_ASC, $playlists);
        }else{
            array_multisort($nbrFormations, SORT_DESC, $playlists);
        }
        return $playlists;
    }

    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();       
    } 
	
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();              
        }           
    }    
    
}
