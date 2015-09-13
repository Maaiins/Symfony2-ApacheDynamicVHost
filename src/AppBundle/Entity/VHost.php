<?php
/**
 * VHost Entity Class
 *
 * This file is part of the Symfony-ApacheDynamicVHost project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Entity
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VHost
 *
 * @category   Entity
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 *
 * @ORM\Entity
 * @ORM\Table(name="vhost")
 */
class VHost
{
    /**
     * @var Integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=16)
     */
    private $id;

    /**
     * @var String
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(name="prefix", type="string", length=255)
     */
    private $prefix;

    /**
     * @var String
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     *
     * @ORM\Column(name="project", type="string", length=255, nullable=false)
     */
    private $project;

    /**
     * @var String
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var Boolean
     *
     * @Assert\Type("boolean")
     *
     * @ORM\Column(name="symlinks", type="boolean")
     */
    private $symlinks = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param String $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return String
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param String $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return String
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param String $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return boolean
     */
    public function isSymlinks()
    {
        return $this->symlinks;
    }

    /**
     * @param boolean $symlinks
     */
    public function setSymlinks($symlinks)
    {
        $this->symlinks = $symlinks;
    }

    /**
     * @return String
     */
    public function getFileName()
    {
        $prefix = '';

        if (!empty($this->prefix)) {
            $prefix .= $this->prefix . '.';
        }

        $suffix = '';

        if (!empty($this->domain)) {
            $suffix .= '.' . $this->domain;
        }

        return $prefix . $this->project . $suffix . '.conf';
    }
}