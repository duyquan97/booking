<?php

namespace App\Controller;

use App\Entity\PriceRoom;
use App\Form\PriceRoomType;
use App\Repository\PriceRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/price/room")
 */
class PriceRoomController extends AbstractController
{
    /**
     * @Route("/", name="price_room_index", methods={"GET"})
     */
    public function index(PriceRoomRepository $priceRoomRepository): Response
    {
        return $this->render('price_room/index.html.twig', [
            'price_rooms' => $priceRoomRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="price_room_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $priceRoom = new PriceRoom();
        $form = $this->createForm(PriceRoomType::class, $priceRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($priceRoom);
            $entityManager->flush();

            return $this->redirectToRoute('price_room_index');
        }

        return $this->render('price_room/new.html.twig', [
            'price_room' => $priceRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="price_room_show", methods={"GET"})
     */
    public function show(PriceRoom $priceRoom): Response
    {
        return $this->render('price_room/show.html.twig', [
            'price_room' => $priceRoom,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="price_room_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PriceRoom $priceRoom): Response
    {
        $form = $this->createForm(PriceRoomType::class, $priceRoom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('price_room_index');
        }

        return $this->render('price_room/edit.html.twig', [
            'price_room' => $priceRoom,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="price_room_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PriceRoom $priceRoom): Response
    {
        if ($this->isCsrfTokenValid('delete'.$priceRoom->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($priceRoom);
            $entityManager->flush();
        }

        return $this->redirectToRoute('price_room_index');
    }
}
