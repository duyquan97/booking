<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Form\SearchType;
use App\Repository\PriceRoomRepository;
use App\Repository\RoomRepository;
use App\Repository\SetRoomRepository;
use Carbon\Carbon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/room")
 */
class RoomController extends AbstractController
{
    /**
     * @Route("/", name="room_index")
     */
    public function index(RoomRepository $roomRepository, Request $request, PriceRoomRepository $priceRoomRepository, SetRoomRepository $setRoomRepository): Response
    {

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $form->get('keyWord')->getData() ? $keyWord = $form->get('keyWord')->getData() : $keyWord = '';
        $form->get('fromPrice')->getData() ? $fromPrice = $form->get('fromPrice')->getData() : $fromPrice = '';
        $form->get('toPrice')->getData() ? $toPrice = $form->get('toPrice')->getData() : $toPrice = '';
        $form->get('fromDate')->getData() ? $fromDate = $form->get('fromDate')->getData() : $fromDate = '';
        $form->get('toDate')->getData() ? $toDate = $form->get('toDate')->getData() : $toDate = '';
        $listId = null;
        if ($toPrice != '' && $fromPrice != '' && $fromDate > $toPrice) {
            $this->addFlash('error', 'Giá phòng không hợp lệ, vui lòng kiểm tra lại!');
        }
        else if ($toDate != '' && $fromDate != '' && Carbon::parse($fromDate)->gt(Carbon::parse($toDate))) {
            $this->addFlash('error', 'Chọn ngày không hợp lệ, vui lòng kiểm tra lại!');
        }
        else {
            $session = new Session();
            $fromDate != '' ? $session->set('fromDate',Carbon::parse($fromDate)->toDateString()) : $session->set('fromDate','');
            $toDate != '' ? $session->set('toDate',Carbon::parse($toDate)->toDateString()) : $session->set('toDate','');
            $datediff = abs(strtotime(Carbon::parse($fromDate)->toDateString()) - strtotime(Carbon::parse($toDate)->toDateString()));
            $countDay   = floor($datediff / (60 * 60 * 24));

            $listId = $setRoomRepository->findByExampleField($fromDate,$toDate, $fromPrice, $toPrice, $countDay);

            if (($fromDate != '' && $toDate != '') || $toPrice != '' || $fromPrice != '') {
                if (empty($listId)) {
                    return $this->render('room/index.html.twig', [
                        'rooms' => [],
                        'form' => $form->createView()
                    ]);
                }
            }
        }

        $rooms = $roomRepository->findBySearch($keyWord, $listId);
        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/new", name="room_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_show", methods={"GET"})
     *
     */
    public function show(Room $room, Request $request, SetRoomRepository $setRoomRepository): Response
    {
        $session = new Session();
        $session->get('fromDate') ? $fromDate = $session->get('fromDate') : $fromDate = '';
        $session->get('toDate') ? $toDate = $session->get('toDate') : $toDate = '';
        return $this->render('room/show.html.twig', [
            'room' => $room,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="room_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Room $room): Response
    {
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('room_index');
        }

        return $this->render('room/edit.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="room_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Room $room): Response
    {
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($room);
            $entityManager->flush();
        }

        return $this->redirectToRoute('room_index');
    }
}
