<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\JoinColumn(name="preference")
     * @ORM\OneToOne(targetEntity="Preference", cascade={"persist", "remove"}, mappedBy="id")
     */
    protected $preference;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setPreference($preference)
    {
        $this->preference = $preference;
    }
    public function getPreference()
    {
        return $this->preference;
    }
}