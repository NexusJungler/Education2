<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\categorieRepository")
 */
class categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=100)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="acronyme", type="string", length=3)
     */
    private $acronyme;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\discipline", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $discipline;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return categorie
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set acronyme
     *
     * @param string $acronyme
     *
     * @return categorie
     */
    public function setAcronyme($acronyme)
    {
        $this->acronyme = $acronyme;

        return $this;
    }

    /**
     * Get acronyme
     *
     * @return string
     */
    public function getAcronyme()
    {
        return $this->acronyme;
    }

    /**
     * Set discipline
     *
     * @param integer $discipline
     *
     * @return categorie
     */
    public function setDiscipline($discipline)
    {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline
     *
     * @return int
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }
}

