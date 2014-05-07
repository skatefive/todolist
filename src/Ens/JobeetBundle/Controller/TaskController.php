<?php

namespace Ens\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ens\JobeetBundle\Entity\Task;
use Ens\JobeetBundle\Form\TaskType;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{
    /**
     * Lists all Task entities.
     *
     */
    public function indexAction(Request $request, $status = null, $time = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = null;

        $periods = array('6 tundi' => 6,
                        '12 tundi' => 12,
                        '24 tundi' => 24,
                        '2 päeva'  => 48,
                        '4 päeva'  => 72
                        );            
    

        // status filter
        if($status && is_numeric($status))
            $entities = $em->getRepository('EnsJobeetBundle:Task')->findByStatus($status);

        // time filter
        if($time && is_numeric($time))
        {
            // echo date('Y-m-d H:i:s', strtotime("-" . $time . " hour"));
            $entities = $em->getRepository('EnsJobeetBundle:Task')->getTasksByTimestamp(strtotime("+" . $time . " hour"));
        }

         
        // string search
        $searchForm = $this->createSearchForm();
        $searchForm->handleRequest($request);

        if ($searchForm->isValid()) 
        {   
             $string = $searchForm["string"]->getData();
            
             $entities = false;
             $entities = $em->getRepository('EnsJobeetBundle:Task')->getSeachResults($string);
        }   

        if($entities === null)
            $entities = $em->getRepository('EnsJobeetBundle:Task')->findAll();

        $entitiesCount = count($em->getRepository('EnsJobeetBundle:Task')->findAll());
        $openEntitiesCount = count($em->getRepository('EnsJobeetBundle:Task')->findByStatus(1));
        $inProgressEntitiesCount = count($em->getRepository('EnsJobeetBundle:Task')->findByStatus(2));
        $closednEntitiesCount = count($em->getRepository('EnsJobeetBundle:Task')->findByStatus(3));
            

        return $this->render('EnsJobeetBundle:Task:index.html.twig', array(
            'entities' => $entities,
            'entitiesCount' => $entitiesCount,
            'openEntitiesCount' => $openEntitiesCount,
            'inProgressEntitiesCount' => $inProgressEntitiesCount,
            'closednEntitiesCount' => $closednEntitiesCount,
            'search_form' => $searchForm->createView(),
            'periods'       => $periods
        ));
    }


    /**
     * Creates a new Task entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Task();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
                'Your changes were saved!'
            );

            return $this->redirect($this->generateUrl('ens_task_show', array('id' => $entity->getId())));
        }

        return $this->render('EnsJobeetBundle:Task:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('ens_task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Create',
            'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createCreateForm($entity);

        return $this->render('EnsJobeetBundle:Task:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Task entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EnsJobeetBundle:Task:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EnsJobeetBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Task entity.
    *
    * @param Task $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('ens_task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Update',
            'attr' => array('class' => 'btn btn-success')
            ));

        return $form;
    }
    /**
     * Edits an existing Task entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
                'Your changes were saved!'
            );

            return $this->redirect($this->generateUrl('ens_task_edit', array('id' => $id)));
        }

        return $this->render('EnsJobeetBundle:Task:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Task entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EnsJobeetBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
              'notice',
                'Your changes were saved!'
            );
        }

        return $this->redirect($this->generateUrl('ens_task'));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ens_task_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }

    private function createSearchForm($string = null)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ens_task_search' ))
            ->setMethod('POST')
            ->add('string')
            ->add('submit', 'submit')
            ->getForm()
        ;
    }    
}
