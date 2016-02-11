<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController
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
