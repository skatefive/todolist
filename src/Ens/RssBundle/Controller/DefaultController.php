<?php

namespace Ens\RssBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($limit)
    {
        // fetch the FeedReader
        $reader = $this->container->get('debril.reader');

        // this date is used to fetch only the latest items
        $date = new \DateTime();        

        // the feed you want to ready
        $url = 'http://mekaia.com/knet.xml';
//        $url = 'http://localhost/knet.xml';

        // now fetch its (fresh) content
        $feed = $reader->getFeedContent($url, $limit);

        // the $content object contains as many Item instances as you have fresh articles in the feed
        $items = $feed->getItems();

        return $this->render('EnsRssBundle:Default:index.html.twig', array(
            'items' => $items,
        ));        
         
        
    }
}
