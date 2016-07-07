<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class CompetenceRepository extends EntityRepository
{
    public function getCompetenceBuild($dicipline, $categorie)
    {
        // requête Builder
        $query = $this->createQueryBuilder('c');
        $query
            ->join('c.categorie', 'cat' , 'WITH', 'cat.discipline = :discipline')
            ->addSelect('cat')
            ->where('c.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->setParameter('discipline', $dicipline);

        return $query
            ->getQuery()
            ->getResult();
    }
    public function getCompetenceDql($dicipline, $categorie)
    {
        // requête DQL
        $query = $this->_em->createQuery('SELECT a FROM AppBundle:competence a JOIN AppBundle:categorie b WITH b.discipline = :discipline WHERE a.categorie = :categorie');
        $query->setParameters([
            'categorie' => $categorie,
            'discipline' => $dicipline
        ]);
        return $query
            ->getResult();
    }
    public function getCompetenceOne($categorie)
    {
        // requête One Parameter
        $query = $this->_em->createQuery('SELECT a FROM AppBundle:competence a JOIN AppBundle:categorie b WHERE a.categorie = :categorie');
        $query->setParameters([
            'categorie' => $categorie
        ]);
        return $query
            ->getResult();
    }

    public function getNbrCompetence()
    {
        $query = $this->_em->createQuery('SELECT COUNT (c.id) FROM AppBundle:competence c');

        $repBuilder = $this->createQueryBuilder('c')
            ->addSelect('COUNT(c)')
            ->getQuery()
            ->getSingleResult()[1];

        $emBuilder = $this->_em->createQueryBuilder()
            ->select('COUNT(c.id)')
            ->from($this->_entityName, 'c')
            ->getQuery()
            ->getSingleScalarResult();

        //return $query->getSingleScalarResult();
        //return $repBuilder;
        return $emBuilder;
    }

}
