<?php

namespace Hasantayyar\UrlShortenerBundle\Service;

use Doctrine\ORM\EntityManager;
use Hasantayyar\UrlShortenerBundle\Entity\ShortUrl;

class Shortener
{

    /**
     *
     * @var integer
     */
    private $urlLength = 3;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Hasantayyar\UrlShortenerBundle\Entity\ShortUrl
     */
    private $lastUrl;

    /**
     * DI constructor
     *
     * @param \Doctrine\ORM\EntityManager  $em        Entity manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Returns short url.
     *
     * If extension can't find appropriate short url it increases length of short
     *
     * @param string $url
     *
     * @return string
     */
    public function shorten($url)
    {

        if ($urlObject = $this->getUrl($url)) {
            return '/$' . $urlObject->getShort();
        }

        $repository = $this->em->getRepository('HasantayyarUrlShortenerBundle:ShortUrl');

        while (true) {
            $shortUrl = $this->generateShortCode($url);

            if (null == $repository->findOneBy(array('short' => $shortUrl))) {
                break;
            }
            $this->increaseShortCodeLength();
        }

        $this->saveUrl($shortUrl, $url);

        return '/$' . $shortUrl;
    }

    /**
     * Returns long url for short equivalent
     *
     * @param string $shortUrl
     *
     * @return null|string
     */
    public function getLong($shortUrl)
    {
        $this->lastUrl = $this->em->getRepository('HasantayyarUrlShortenerBundle:ShortUrl')->findOneBy(array('short' => $shortUrl));

        return $this->lastUrl ? $this->lastUrl->getLong() : null;
    }

    private function getUrl($longUrl)
    {
        $repository = $this->em->getRepository('HasantayyarUrlShortenerBundle:ShortUrl');
        return $repository->findOneBy(array('long' => $longUrl));
    }

    /**
     * Saves new short url to DB
     *
     * @param string $shortUrl Short url
     * @param string $longUrl  Long url
     */
    private function saveUrl($shortUrl, $longUrl)
    {
        $urlObject = new ShortUrl();
        $urlObject->setLong($longUrl);
        $urlObject->setShort($shortUrl);

        $this->em->persist($urlObject);
        $this->em->flush();
    }

    /**
     * Shorten URL method.
     *
     * @param string $longUrl long url to shorten
     *
     * @return string short url
     */
    public function generateShortCode($longUrl)
    {
        $url = md5($longUrl);
        $tmpHash = '';
        do {
            $tmpHash.= $url;
            $tmpHash = str_replace(array('+', '/', '='), '', base64_encode(crc32($tmpHash)));
        } while (strlen($tmpHash) < $this->getShortCodeLength());
        return substr($tmpHash, 0, $this->getShortCodeLength());
    }

    /**
     * Gets short URL minimum length.
     *
     * @return int
     */
    public function getShortCodeLength()
    {
        return $this->urlLength;
    }

    /**
     * Sets short URL minimum length.
     *
     * @param int $length minimum length of short url
     */
    public function setShortCodeLength($length)
    {
        $this->urlLength = $length;
    }

    /**
     * Increase short URL length
     *
     * @return int
     */
    public function increaseShortCodeLength()
    {
        return ++$this->urlLength;
    }

}
