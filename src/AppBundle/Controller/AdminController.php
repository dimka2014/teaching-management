<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class AdminController
{
    /**
     * @Route("/", name="admin_homepage")
     * @Template
     */
    public function homepageAction()
    {
        return [];
    }
}