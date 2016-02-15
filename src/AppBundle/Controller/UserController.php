<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/admin/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/all", name="all_users")
     * @Method("GET")
     * @Template(template="AppBundle:User:index.html.twig")
     */
    public function allAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->getAllPaginatedUsers($request->get('page', 1), $this->getParameter('page_size'));

        return [
            'title' => $this->get('translator')->trans('users.all_users'),
            'users' => $users,
        ];
    }

    /**
     * @Route("/new", name="new_users")
     * @Method("GET")
     * @Template(template="AppBundle:User:index.html.twig")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->getNewPaginatedUsers($request->get('page', 1), $this->getParameter('page_size'));

        return [
            'title' => $this->get('translator')->trans('users.new_users'),
            'users' => $users,
        ];
    }


    /**
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return [
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ];
    }

}
