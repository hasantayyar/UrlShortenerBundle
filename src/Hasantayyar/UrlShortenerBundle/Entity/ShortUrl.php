<?php

namespace Hasantayyar\UrlShortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\Collection,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="ShortUrl",indexes={@ORM\Index(columns={"code"})})
 */
class ShortUrl
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="url", type="string")
     */
    protected $long;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=200)
     */
    protected $short;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set long
     *
     * @param string $long
     */
    public function setLong($long)
    {
        $this->long = $long;
    }

    /**
     * Get long
     *
     * @return string 
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Set short
     *
     * @param string $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * Get short
     *
     * @return string 
     */
    public function getShort()
    {
        return $this->short;
    }

}
