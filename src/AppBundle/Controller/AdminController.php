<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminController
{
    /**
     * @Route("/admin/", name="admin_homepage")
     * @Template
     */
    public function homepageAction()
    {
        return [];
    }
}
