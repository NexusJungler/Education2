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
        // Par défaut, une relation est facultative(une classe sans utilisateur peut exister), on force la relation en ajoutant l'annotation JoinColumn
        // le propriétaire de la relation est celui qui contient la colonne référence. De façon systématique, c'est le côté Many d'une relation Many-To-One qui est le propriétaire
        // seule l'entité propriétaire est modifiée dans une relation unidirectionnelle, il faudra utiliser l'annotation One-To-Many dans l'entité inverse dans une relation bidirectionnelle
        // et ajouter mappedBy(inverse) & inversedBy(propriétaire) pour renseigner l'attribut de l'autre entité qui pointe sur elle.
        // requête SQL générée ==> "ALTER TABLE classe ADD FOREIGN KEY (user_id) REFERENCES User (id)"
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

