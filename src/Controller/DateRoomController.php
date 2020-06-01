<?php

namespace App\Controller;

use App\Entity\DateRoom;
use App\Form\DateRoomType;
use App\Repository\DateRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/date/room")
 */
class DateRoomController extends AbstractController
{
    /**
     * @Route("/", name="date_room_index", methods={"GET"})
     */
    public function index(DateRoomRepository $dateRoomRepository): Response
    {
        return $this->render('date_room/index.html.twig', [
            'date_rooms' => $dateRoomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="date_room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dateRoom = new DateRoom();
        $form = $this->createForm(DateRoomType::class, $dateRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dateRoom);
            $entityManager->flush();

            return $this->redirectToRoute('date_room_index');
        }

        return $this->render('date_room/new.html.twig', [
            'date_room' => $dateRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="date_room_show", methods={"GET"})
     */
    public function show(DateRoom $dateRoom): Response
    {
        return $this->render('date_room/show.html.twig', [
            'date_room' => $dateRoom,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="date_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DateRoom $dateRoom): Response
    {
        $form = $this->createForm(DateRoomType::class, $dateRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('date_room_index');
        }

        return $this->render('date_room/edit.html.twig', [
            'date_room' => $dateRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="date_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DateRoom $dateRoom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dateRoom->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dateRoom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('date_room_index');
    }

}
