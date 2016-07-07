<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * eleve
 *
 * @ORM\Table(name="eleve")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\eleveRepository")
 */
class eleve
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
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Votre nom doit contenir au minimum {{ limit }} caractères"
     * )
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     * @Assert\Length(
     *      min = 4,
     *      max = 30,
     *      minMessage = "Votre prénom doit contenir au minimum {{ limit }} caractères"
     * )
     * @ORM\Column(name="prenom", type="string", length=30)
     *
     */
    private $prenom;

    /**
     * @var string
     * @Assert\File(
     * maxSize = "1024k",
     * mimeTypes={ "image/jpeg", "image/jpg" },
     * mimeTypesMessage = "Le format du fichier est invalide!"
     * )
     * @ORM\Column(name="photo", type="string", length=100)
     */
    private $photo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="age", type="date")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="voie", type="string", length=10)
     */
    private $voie;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;

    /**
     * @var string
     * @Assert\Length(
     *      min = 5,
     *      max = 25,
     *      minMessage = "Le complément d'adresse doit contenir au minimum {{ limit }} caractères."
     * )
     * @ORM\Column(name="complement", type="string", length=50)
     */
    private $complement;

    /**
     * @var int
     *
     * @ORM\Column(name="codepost", type="integer")
     */
    private $codepost;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=25)
     */
    private $ville;

    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\classe", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $classe;


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
     * @return eleve
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return eleve
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return eleve
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set age
     *
     * @param \DateTime $age
     *
     * @return eleve
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return \DateTime
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set voie
     *
     * @param string $voie
     *
     * @return eleve
     */
    public function setVoie($voie)
    {
        $this->voie = $voie;

        return $this;
    }

    /**
     * Get voie
     *
     * @return string
     */
    public function getVoie()
    {
        return $this->voie;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return eleve
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set complement
     *
     * @param string $complement
     *
     * @return eleve
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;

        return $this;
    }

    /**
     * Get complement
     *
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Set codepost
     *
     * @param integer $codepost
     *
     * @return eleve
     */
    public function setCodepost($codepost)
    {
        $this->codepost = $codepost;

        return $this;
    }

    /**
     * Get codepost
     *
     * @return int
     */
    public function getCodepost()
    {
        return $this->codepost;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return eleve
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set classe
     *
     * @param integer $classe
     *
     * @return eleve
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return int
     */
    public function getClasse()
    {
        return $this->classe;
    }
}

