<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Tache
{
	/**
	 * @ORM\Column(name="type", type="string", length=31)
	 */
	protected $type;

	/**
	 * @ORM\Column(name="date", type="date")
	 */
	protected $date;

	/**
	 * @ORM\ManyToOne(targetEntity="Commercial", inversedBy="acronyme")
	 * @ORM\JoinColumn(name="commercial", referencedColumnName="acronyme")
	 */
	protected $commercial;

	/**
	 * @ORM\ManyToOne(targetEntity="Affaire", inversedBy="id")
	 * @ORM\JoinColumn(name="affaire", referencedColumnName="id")
	 */
	protected $affaire;

	/**
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	public function setType($type)
	{
		$this->type = $type;
	}
	public function getType()
	{
		return $this->type;
	}

	public function setDate($date)
	{
		$this->date = $date;
	}
	public function getDate()
	{
		return $this->date;
	}

	public function setCommercial($commercial)
	{
		$this->commercial = $commercial;
	}
	public function getCommercial()
	{
		return $this->commercial;
	}

	public function setAffaire($affaire)
	{
		$this->affaire = $affaire;
	}
	public function getAffaire()
	{
		return $this->affaire;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	public function getId()
	{
		return $this->id;
	}
}
