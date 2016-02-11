<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->isGranted(UserInterface::ROLE_SUPER_ADMIN)) {
            return new RedirectResponse($this->generateUrl('admin_homepage'));
        }

        return new RedirectResponse($this->generateUrl('fos_user_security_login'));
    }
}
