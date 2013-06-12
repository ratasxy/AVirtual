<?php

namespace OLabs\AVirtBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OLabs\AVirtBundle\Entity\Video;
use OLabs\AVirtBundle\Form\VideoType;
use OLabs\AVirtBundle\Entity\Course;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


/**
 * Video controller.
 *
 * @Route("/course/{course_id}/video")
 */
class VideoController extends Controller
{
    /**
     * Lists all Video entities.
     *
     * @Route("/", name="video", defaults={"_format"="json"})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($course_id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('AVirtBundle:Course')->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $entities = $em->getRepository('AVirtBundle:Video')->findby(array("course" => $course_id));

        $data = array();
        foreach($entities as $item) {
            $data[] = array(
                'id' => $item->getId(),
                'name' => $item->getName(),
                'url' => $item->getUrl(),
                'type' => $item->getType(),
            );
        }

        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, array(new JsonEncoder()));

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * Creates a new Video entity.
     *
     * @Route("/", name="video_create")
     * @Method("POST")
     * @Template("AVirtBundle:Video:new.html.twig")
     */
    public function createAction(Request $request, $course_id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('AVirtBundle:Course')->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $entity  = new Video();

        $form = $this->createForm(new VideoType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->updatedTimestamps();
            $entity->setCourse($course);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $course->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Video entity.
     *
     * @Route("/new", name="video_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($course_id)
    {
        $em = $this->getDoctrine()->getManager();
        $course = $em->getRepository('AVirtBundle:Course')->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $entity = new Video();
        $entity->setType("youtube");
        $form   = $this->createForm(new VideoType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'course' => $course,
        );
    }

    /**
     * Finds and displays a Video entity.
     *
     * @Route("/{id}", name="video_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($course_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('AVirtBundle:Course')->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $entity = $em->getRepository('AVirtBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'course' => $course,
        );
    }

    /**
     * Displays a form to edit an existing Video entity.
     *
     * @Route("/{id}/edit", name="video_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($course_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $course = $em->getRepository('AVirtBundle:Course')->find($course_id);

        if (!$course) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $entity = $em->getRepository('AVirtBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $editForm = $this->createForm(new VideoType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'course' => $course,
        );
    }

    /**
     * Edits an existing Video entity.
     *
     * @Route("/{id}", name="video_update")
     * @Method("PUT")
     * @Template("AVirtBundle:Video:edit.html.twig")
     */
    public function updateAction(Request $request, $course_id, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AVirtBundle:Video')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Video entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new VideoType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $entity->updatedTimestamps();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $course_id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Video entity.
     *
     * @Route("/{id}", name="video_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $course_id, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AVirtBundle:Video')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Video entity.');
            }

            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('course_show', array('id' => $entity->getCourse()->getId())));
        }

        return $this->redirect($this->generateUrl('course'));
    }

    /**
     * Creates a form to delete a Video entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
