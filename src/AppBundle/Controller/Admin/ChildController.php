<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(path="/children")
 */
class ChildController extends Controller
{
    /**
     * @Route("/list/{page}", name="child_list", requirements={"page"="\d+"}, defaults={"page"=1})
     * @Template
     */
    public function listAction($page)
    {
        $children = $this
            ->get('doctrine')
            ->getRepository('AppBundle:Child')
            ->listPaginated($page, $this->getParameter('page_size'));

        return [
            'children' => $children
        ];
    }
}
