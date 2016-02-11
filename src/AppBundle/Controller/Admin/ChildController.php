<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route(path="/children")
 */
class ChildController
{
    /**
     * @Route("/list/{page}/{size}", name="admin_homepage")
     * @Template
     */
    public function listAction()
    {

    }
}
