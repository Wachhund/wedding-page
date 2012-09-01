<?php

namespace Micha149\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

class GalleryController extends Controller
{

    /**
     * @Route("/")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction() {
    
        $manager     = $this->get('file_manager');
        $events      = array();
        
        foreach ($manager->getEvents() as $event) {
            $images   = $manager->getImagesByEvent($event);
            $events[] = array(
                'event'  => $event,
                'images' => array_splice($images, 0, 5)
            );
        }

        return array(
            'galleryTitle' => $this->container->getParameter('gallery_title'),
            'events'  => $events
        );
    }
    
    /**
     * @Route("/{event}")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function eventAction($event)
    {
        $manager = $this->get('file_manager');

        return array(
            'galleryTitle' => $this->container->getParameter('gallery_title'),
            'baseUrl' => $manager->getBaseUrl(),
            'files'   => $manager->getImagesByEvent($event),
            'events'  => $manager->getEvents(),
            'currentEvent' => $event,
        ); 
    }
}
