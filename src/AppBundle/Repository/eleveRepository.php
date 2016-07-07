<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class eleveRepository extends EntityRepository
{
    public function getStudentOrdered($class)
    {
        /************ fast query *************/
        $qbf = $this->createQueryBuilder('s');
        $qbf
            ->where('s.classe = :class')
            ->setParameter('class', $class)
            ->orderBy('s.nom', 'ASC');

        /************ detail query *************/
        $qbd = $this->_em->createQueryBuilder()
            ->select('s.id, s.nom, s.prenom')
            ->from($this->_entityName, 's')
            ->where('s.classe = :class')
            ->setParameter('class', $class)
            ->orderBy('s.nom', 'ASC');

        return $qbd
            ->getQuery()
            ->getResult();
    }
    public function getSuccess($student)
    {
        $nbrComp = $this->_em
            ->getRepository('AppBundle:Competence')
            ->getNbrCompetence();
        $result = null;
        $work = $this->getChartData($student);
        foreach($work as $value) {
            $result += $value;
        }
        $result /= $nbrComp;
        return (int)($result);
    }
    public function getCount($stud, $dscp)
    {
        $query = $this->_em->createQuery('SELECT COUNT(c.id) FROM AppBundle:Competence c JOIN AppBundle:categorie g WITH g.discipline = :discipline AND g.id = c.categorie JOIN AppBundle:validation v WITH v.eleve = :eleve WHERE c.id = v.competence');
        $query->setParameters([
            'eleve' => $stud,
            'discipline' => $dscp
        ]);
        return $query
            ->getSingleScalarResult();
    }
    public function getChartData($stud) {
        // attention, la prise en compte du nombre de diciplines n'est pas implémentée ici! le 7 doit être remplacé par un count(disciplines)
        $result = [];
        for($i=0; $i<7; $i++) {
            $result[$i] = $this->getCount($stud, $i+1);
        }
        return $result;
    }

}
