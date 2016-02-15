<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Child;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->isGranted(UserInterface::ROLE_SUPER_ADMIN)) {
            return $this->redirectToRoute('child_index');
        }
        if ($this->isGranted(UserInterface::ROLE_DEFAULT)) {
            return $this->redirectToRoute('my_children');
        }

        return $this->redirectToRoute('fos_user_security_login');
    }

    /**
     * @Route("/my-children", name="my_children")
     * @Template()
     */
    public function myChildrenAction()
    {
        return [
            'children' => $this->getDoctrine()->getRepository('AppBundle:Child')->getAllChildrenByUser($this->getUser())
        ];
    }

    /**
     * @Route("/my-children/{childId}/payments", name="my_children_payments")
     * @Method("GET")
     * @ParamConverter("child", class="AppBundle:Child", options={"id" = "childId"})
     * @Template
     */
    public function childrenPaymentsAction(Child $child, Request $request)
    {
        $payments = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Payment')
            ->getPaginatedPaymentsByChild($child, $request->get('page', 1), $this->getParameter('page_size'));

        return [
            'child' => $child,
            'payments' => $payments
        ];
    }

    /**
     * @Route("/my-children/{childId}/attendence", name="my_children_attendence")
     * @Method("GET")
     * @ParamConverter("child", class="AppBundle:Child", options={"id" = "childId"})
     * @Template
     */
    public function childrenAttendenceAction(Child $child, Request $request)
    {
        $attendences = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Attendence')
            ->listPaginatedByChild($child, $request->get('page', 1), $this->getParameter('page_size'));

        return [
            'attendences' => $attendences,
            'child' => $child,
        ];
    }
}
