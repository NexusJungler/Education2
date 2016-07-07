<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * validation
 *
 * @ORM\Table(name="validation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\validationRepository")
 */
class validation
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\eleve", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $eleve;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Competence", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $competence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="successone", type="date")
     */
    private $successone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="successtwo", type="date")
     */
    private $successtwo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="successtree", type="date")
     */
    private $successtree;

    /**
     * @var int
     *
     * @ORM\Column(name="compensationone", type="smallint")
     */
    private $compensationone;

    /**
     * @var int
     *
     * @ORM\Column(name="compensationtwo", type="smallint")
     */
    private $compensationtwo;

    /**
     * @var int
     *
     * @ORM\Column(name="compensationtree", type="smallint")
     */
    private $compensationtree;

    /**
     * @var int
     *
     * @ORM\Column(name="etat", type="smallint")
     */
    private $etat;


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
     * Set eleve
     *
     * @param integer $eleve
     *
     * @return validation
     */
    public function setEleve($eleve)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return int
     */
    public function getEleve()
    {
        return $this->eleve;
    }

    /**
     * Set competence
     *
     * @param integer $competence
     *
     * @return validation
     */
    public function setCompetence($competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return int
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    /**
     * Set successone
     *
     * @param \DateTime $successone
     *
     * @return validation
     */
    public function setSuccessone($successone)
    {
        $this->successone = $successone;

        return $this;
    }

    /**
     * Get successone
     *
     * @return \DateTime
     */
    public function getSuccessone()
    {
        return $this->successone;
    }

    /**
     * Set successtwo
     *
     * @param \DateTime $successtwo
     *
     * @return validation
     */
    public function setSuccesstwo($successtwo)
    {
        $this->successtwo = $successtwo;

        return $this;
    }

    /**
     * Get successtwo
     *
     * @return \DateTime
     */
    public function getSuccesstwo()
    {
        return $this->successtwo;
    }

    /**
     * Set successtree
     *
     * @param \DateTime $successtree
     *
     * @return validation
     */
    public function setSuccesstree($successtree)
    {
        $this->successtree = $successtree;

        return $this;
    }

    /**
     * Get successtree
     *
     * @return \DateTime
     */
    public function getSuccesstree()
    {
        return $this->successtree;
    }

    /**
     * Set compensationone
     *
     * @param integer $compensationone
     *
     * @return validation
     */
    public function setCompensationone($compensationone)
    {
        $this->compensationone = $compensationone;

        return $this;
    }

    /**
     * Get compensationone
     *
     * @return int
     */
    public function getCompensationone()
    {
        return $this->compensationone;
    }

    /**
     * Set compensationtwo
     *
     * @param integer $compensationtwo
     *
     * @return validation
     */
    public function setCompensationtwo($compensationtwo)
    {
        $this->compensationtwo = $compensationtwo;

        return $this;
    }

    /**
     * Get compensationtwo
     *
     * @return int
     */
    public function getCompensationtwo()
    {
        return $this->compensationtwo;
    }

    /**
     * Set compensationtree
     *
     * @param integer $compensationtree
     *
     * @return validation
     */
    public function setCompensationtree($compensationtree)
    {
        $this->compensationtree = $compensationtree;

        return $this;
    }

    /**
     * Get compensationtree
     *
     * @return int
     */
    public function getCompensationtree()
    {
        return $this->compensationtree;
    }

    /**
     * Set etat
     *
     * @param integer $etat
     *
     * @return validation
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }
}

