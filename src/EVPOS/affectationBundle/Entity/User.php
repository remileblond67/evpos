<?php
// src/AppBundle/Entity/User.php

namespace EVPOS\affectationBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="evpos_user")
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30, nullable=false)
     * @ORM\Id
     */
    private $username;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}
