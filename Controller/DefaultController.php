<?php

namespace ClubsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ClubsBundle:Default:index.html.twig');
    }
    public function ListAction()
    {
        return $this->render('@Clubs/Default/club.html.twig');
    }
}
