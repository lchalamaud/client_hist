<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Commercial
{
	/**
	 * @ORM\Column(name="nom", type="string", length=31)
	 */
	protected $nom;

	/**
	 * @ORM\OneToMany(targetEntity="Tache", mappedBy="commercial")
	 * @ORM\OneToMany(targetEntity="Affaire", mappedBy="commercial")
	 * @ORM\Column(name="acronyme", type="string", length=5)
	 * @ORM\Id
	 */
	protected $acronyme;

	/**
	 * @ORM\Column(name="couleur", type="integer", length=2)
	 */
	protected $couleur;

	public function setNom($nom)
	{
		$this->nom = $nom;
	}
	public function getNom()
	{
		return $this->nom;
	}

	public function setAcronyme($acronyme)
	{
		$this->acronyme = $acronyme;
	}
	public function getAcronyme()
	{
		return $this->acronyme;
	}

	public function setCouleur($couleur)
	{
		$this->couleur = $couleur;
	}
	public function getCouleur()
	{
		return $this->couleur;
	}


}
