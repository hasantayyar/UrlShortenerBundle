<?php

namespace Hasantayyar\UrlShortenerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Hasantayyar\UrlShortenerBundle\Service\Shortener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortController extends Controller
{

    /**
     * Redirects user to long equivalent
     *
     * @param string $shortUrl A short url to be redirected to
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function redirectAction($shortUrl)
    {
        $shortener = new Shortener($this->getDoctrine()->getManager());
        $longUrl = $shortener->getLong($shortUrl);
        if (!$longUrl) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('No short url found ' . $shortUrl);
        }
        return $this->redirect($longUrl);
    }

    public function shortenAction(Request $request)
    {
        $shortener = new Shortener($this->getDoctrine()->getManager());
        $url = $request->get("url");
        $code = $shortener->shorten($url);
        return new Response($code);
    }

    public function deleteAction()
    {
        
    }

}
