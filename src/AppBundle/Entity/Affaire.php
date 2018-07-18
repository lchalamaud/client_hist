<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Affaire
{
	/**
	 * @ORM\Column(name="civilite", type="string", length=3)
	 */
	protected $civilite;

	/**
	 * @ORM\Column(name="nom", type="string", length=31)
	 */
	protected $nom;

	/**
	 * @ORM\Column(name="societe", type="string", length=63)
	 */
	protected $societe;

	/**
	 * @ORM\Column(name="telephone", type="string", length=20)
	 */
	protected $telephone;

	/**
	 * @ORM\Column(name="email", type="string", length=64)
	 */
	protected $email;

	/**
	 * @ORM\Column(name="rue", type="string", length=255)
	 */
	protected $rue;

	/**
	 * @ORM\Column(name="complement", type="string", length=255, nullable=true)
	 */
	protected $complement;

	/**
	 * @ORM\Column(name="cp", type="integer", length=5)
	 */
	protected $cp;

	/**
	 * @ORM\Column(name="ville", type="string", length=63)
	 */
	protected $ville;

	/**
	 * @ORM\Column(name="debut", type="date")
	 */
	protected $debut;

	/**
	 * @ORM\Column(name="commentaire", type="text", nullable=true)
	 */
	protected $commentaire;

	/**
	 * @ORM\Column(name="devis_type", type="string", length=31)
	 */
	protected $devis_Type;

	/**
	 * @ORM\Column(name="system_type", type="string", length=21)
	 */
	protected $system_Type;

	/**
	 * @ORM\Column(name="nb_controller", type="integer", length=3)
	 */
	protected $nb_Controller;

	/**
	 * @ORM\Column(name="provenance", type="string", length=15)
	 */
	protected $provenance;

	/**
	 * @ORM\OneToMany(targetEntity="Tache", mappedBy="affaire")
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="Commercial", inversedBy="acronyme")
	 * @ORM\JoinColumn(name="commercial", referencedColumnName="acronyme", nullable=true)
	 */
	protected $commercial;

	/**
	 * @ORM\Column(name="état", type="string", length=9)
	 */
	protected $etat;

	/**
	 * @ORM\Column(name="info", type="text", nullable=true)
	 */
	protected $info;

	/**
	 * @ORM\Column(name="numDossier", type="integer", length=5, nullable=true)
	 */
	protected $numDossier;

	public function setCivilite($civilite)
	{
		$this->civilite = $civilite;
	}
	public function getCivilite()
	{
		return $this->civilite;
	}

	public function setNom($nom)
	{
		$this->nom = $nom;
	}
	public function getNom()
	{
		return $this->nom;
	}

	public function setSociete($societe)
	{
		$this->societe = $societe;
	}
	public function getSociete()
	{
		return $this->societe;
	}

	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}
	public function getTelephone()
	{
		return $this->telephone;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}
	public function getEmail()
	{
		return $this->email;
	}

	public function setRue($rue)
	{
		$this->rue = $rue;
	}
	public function getRue()
	{
		return $this->rue;
	}

	public function setComplement($complement)
    {
        $this->complement = $complement;
    }
    public function getComplement()
    {
        return $this->complement;
    }

	public function setCP($cp)
	{
		$this->cp = $cp;
	}
	public function getCP()
	{
		return $this->cp;
	}

	public function setVille($ville)
	{
		$this->ville = $ville;
	}
	public function getVille()
	{
		return $this->ville;
	}

	public function setDebut($debut)
	{
		$this->debut = $debut;
	}

	public function getDebut()
	{
		return $this->debut;
	}

	public function setCommentaire($commentaire)
	{
		$this->commentaire = $commentaire;
	}
	public function getCommentaire()
	{
		return $this->commentaire;
	}

	public function setDevisType($devis_Type)
	{
		$this->devis_Type = $devis_Type;
	}
	public function getDevisType()
	{
		return $this->devis_Type;
	}

	public function setSystemType($system_Type)
	{
		$this->system_Type = $system_Type;
	}
	public function getSystemType()
	{
		return $this->system_Type;
	}

	public function setNbController($nb_Controller)
	{
		$this->nb_Controller = $nb_Controller;
	}
	public function getNbController()
	{
		return $this->nb_Controller;
	}

	public function setProvenance($provenance)
	{
		$this->provenance = $provenance;
	}
	public function getProvenance()
	{
		return $this->provenance;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	public function getId()
	{
		return $this->id;
	}

	public function setCommercial($commercial)
	{
		$this->commercial = $commercial;
	}
	public function getCommercial()
	{
		return $this->commercial;
	}

	public function setEtat($etat)
	{
		$this->etat = $etat;
	}
	public function getEtat()
	{
		return $this->etat;
	}

	public function setInfo($info)
	{
		$this->info = $info;
	}
	public function getInfo()
	{
		return $this->info;
	}

	public function setNumDossier($numDossier)
	{
		$this->numDossier = $numDossier;
	}
	public function getNumDossier()
	{
		return $this->numDossier;
	}

	public function affaireToArray( $rappel ){
		return array(
                'civilite' => $this->getCivilite(),
                'nom' => $this->getNom(),
                'societe' => $this->getSociete(),

                'rue' => $this->getRue(),
                'complement' => $this->getComplement(),
                'cp' => $this->getCP(),
                'ville' => $this->getVille(),

                'mail' => $this->getEmail(),
                'telephone' => $this->getTelephone(),

                'nb_controller' => $this->getNbController(),
                'devi_type' => $this->getDevisType(),
                'system_type' => $this->getSystemType(),
                'provenance' => $this->getProvenance(),

                'debut' => $this->getDebut(),
                'etat' => $this->getEtat(),
                'rappel' => $rappel,
                'commercial' => $this->getCommercial(),
                'commentaire' => $this->getCommentaire(),
                'info' => $this->getInfo(),
                'id' => $this->getId()
            );
	}


}
