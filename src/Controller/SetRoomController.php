<?php

namespace App\Controller;

use App\Entity\SetRoom;
use App\Form\SetRoomType;
use App\Repository\SetRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/set/room")
 */
class SetRoomController extends AbstractController
{
    /**
     * @Route("/", name="set_room_index", methods={"GET"})
     */
    public function index(SetRoomRepository $setRoomRepository): Response
    {

        return $this->render('set_room/index.html.twig', [
            'set_rooms' => $setRoomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="set_room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $setRoom = new SetRoom();
        $form = $this->createForm(SetRoomType::class, $setRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($setRoom);
            $entityManager->flush();

            return $this->redirectToRoute('set_room_index');
        }

        return $this->render('set_room/new.html.twig', [
            'set_room' => $setRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="set_room_show", methods={"GET"})
     */
    public function show(SetRoom $setRoom): Response
    {
        return $this->render('set_room/show.html.twig', [
            'set_room' => $setRoom,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="set_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SetRoom $setRoom): Response
    {
        $form = $this->createForm(SetRoomType::class, $setRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('set_room_index');
        }

        return $this->render('set_room/edit.html.twig', [
            'set_room' => $setRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="set_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SetRoom $setRoom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$setRoom->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($setRoom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('set_room_index');
    }
}
