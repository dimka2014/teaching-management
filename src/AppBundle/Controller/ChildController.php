<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Child;
use AppBundle\Form\ChildType;

/**
 * Child controller.
 *
 * @Route("/admin/children")
 */
class ChildController extends Controller
{
    /**
     * Lists all Child entities.
     *
     * @Route("/", name="child_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $children = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Child')
            ->listPaginated($request->get('page', 1), $this->getParameter('page_size'));

        return [
            'children' => $children
        ];
    }

    /**
     * Creates a new Child entity.
     *
     * @Route("/new", name="child_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $child = new Child();
        $form = $this->createForm('AppBundle\Form\ChildType', $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.child_created'));

            return $this->redirectToRoute('child_index');
        }

        return [
            'child' => $child,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Child entity.
     *
     * @Route("/{id}", name="child_show")
     * @Method("GET")
     * @Template
     */
    public function showAction(Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);

        return [
            'child' => $child,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Child entity.
     *
     * @Route("/{id}/edit", name="child_edit")
     * @Method({"GET", "POST"})
     * @Template
     */
    public function editAction(Request $request, Child $child)
    {
        $deleteForm = $this->createDeleteForm($child);
        $editForm = $this->createForm('AppBundle\Form\ChildType', $child);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.edited'));

            return $this->redirectToRoute('child_edit', array('id' => $child->getId()));
        }

        return [
            'child' => $child,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Child entity.
     *
     * @Route("/{id}", name="child_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Child $child)
    {
        $form = $this->createDeleteForm($child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($child);
            $em->flush();
            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.deleted'));
        }

        return $this->redirectToRoute('child_index');
    }

    /**
     * Lists all Payment entities by child.
     *
     * @Route("/{id}/payments", name="payments_by_child_index")
     * @Method("GET")
     * @Template
     */
    public function paymentsIndexByChildAction(Child $child, Request $request)
    {
        $payment = new Payment();
        $form = $this->createForm('AppBundle\Form\PaymentType', $payment);

        $payments = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Payment')
            ->getPaginatedPaymentsByChild($child, $request->get('page', 1), $this->getParameter('page_size'));

        return [
            'child' => $child,
            'payments' => $payments,
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a new Payment entity.
     *
     * @Route("/{id}/payments/new", name="payment_new")
     * @Method({"POST"})
     */
    public function newPaymentAction(Child $child, Request $request)
    {
        $payment = new Payment($child);
        $form = $this->createForm('AppBundle\Form\PaymentType', $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $child->setBalance($child->getBalance() + $payment->getSum());

            $em->transactional(function(EntityManager $em) use ($child, $payment) {
                $em->persist($child);
                $em->persist($payment);
            });

            $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.payment_created'));
        } else {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
            foreach ($form->get('sum')->getErrors() as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('payments_by_child_index', ['id' => $child->getId()]);
    }

    /**
     * Creates a new Payment entity.
     *
     * @Route("/{id}/payments/{paymentId}", name="payment_delete")
     * @ParamConverter("payment", class="AppBundle:Payment", options={"id" = "paymentId"})
     * @Method({"DELETE"})
     */
    public function deletePaymentAction(Child $child, Payment $payment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $child->setBalance($child->getBalance() - $payment->getSum());
        $em->transactional(function(EntityManager $em) use ($child, $payment) {
            $em->persist($child);
            $em->remove($payment);
        });
        $this->addFlash('success', $this->get('translator.default')->trans('flash_messages.payment_removed'));

        return $this->redirectToRoute('payments_by_child_index', ['id' => $child->getId()]);
    }

    /**
     * Creates a form to delete a Child entity.
     *
     * @param Child $child The Child entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Child $child)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('child_delete', array('id' => $child->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
