<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\classeRepository")
 */
class classe
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
     * @ORM\Column(name="nom", type="string", length=4)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="annee", type="smallint")
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     * */
        // Par d�faut, une relation est facultative(une classe sans utilisateur peut exister), on force la relation en ajoutant l'annotation JoinColumn
        // le propri�taire de la relation est celui qui contient la colonne r�f�rence. De fa�on syst�matique, c'est le c�t� Many d'une relation Many-To-One qui est le propri�taire
        // seule l'entit� propri�taire est modifi�e dans une relation unidirectionnelle, il faudra utiliser l'annotation One-To-Many dans l'entit� inverse dans une relation bidirectionnelle
        // et ajouter mappedBy(inverse) & inversedBy(propri�taire) pour renseigner l'attribut de l'autre entit� qui pointe sur elle.
        // requ�te SQL g�n�r�e ==> "ALTER TABLE classe ADD FOREIGN KEY (user_id) REFERENCES User (id)"
    private $user;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return classe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return classe
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return int
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return classe
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }
}

