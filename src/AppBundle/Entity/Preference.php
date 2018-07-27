<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Preference
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\OneToOne(targetEntity="User", inversedBy="preference")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="temps", type="string", length=10, nullable=true)
	 */
	protected $temps = null;

	/**
	 * @ORM\Column(name="comm", type="string", length=5, nullable=true)
	 */
	protected $comm = null;

	/**
	 * @ORM\Column(name="en_cours", type="boolean")
	 */
	protected $en_cours = true;

	/**
	 * @ORM\Column(name="oublie", type="boolean")
	 */
	protected $oublie = true;

	/**
	 * @ORM\Column(name="suspendu", type="boolean")
	 */
	protected $suspendu = false;

	/**
	 * @ORM\Column(name="fin", type="boolean")
	 */
	protected $fin = false;

	/**
	 * @ORM\Column(name="signe", type="boolean")
	 */
	protected $signe = false;

	/**
	 * @ORM\Column(name="signeEC", type="boolean")
	 */
	protected $signeEC = true;

	public function setTemps($temps)
	{
		$this->temps = $temps;
	}
	public function getTemps()
	{
		return $this->temps;
	}

	public function setComm($comm)
	{
		$this->comm = $comm;
	}
	public function getComm()
	{
		return $this->comm;
	}

	public function setEn_cours($en_cours)
	{
		$this->en_cours = $en_cours;
	}
	public function getEn_cours()
	{
		return $this->en_cours;
	}

	public function setOublie($oublie)
	{
		$this->oublie = $oublie;
	}
	public function getOublie()
	{
		return $this->oublie;
	}

	public function setSuspendu($suspendu)
	{
		$this->suspendu = $suspendu;
	}
	public function getSuspendu()
	{
		return $this->suspendu;
	}

	public function setFin($fin)
	{
		$this->fin = $fin;
	}
	public function getFin()
	{
		return $this->fin;
	}

	public function setSigne($signe)
	{
		$this->signe = $signe;
	}
	public function getSigne()
	{
		return $this->signe;
	}

	public function setSigneEC($signeEC)
	{
		$this->signeEC = $signeEC;
	}
	public function getSigneEC()
	{
		return $this->signeEC;
	}

}