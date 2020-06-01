<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\RoomRepository;
use App\Repository\SetRoomRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="booking_new", methods={"GET","POST"})
     * @IsGranted("CREATE_BOOKING")
     */
    public function new(Request $request, RoomRepository $roomRepository, SetRoomRepository $setRoomRepository): Response
    {
        $session = new Session();
        !empty($request->getSession()->get('booking')) ? $info =$request->getSession()->get('booking') : $info = [];
        if (empty($info)) {
            return $this->redirectToRoute('room_index');
        }
        $user = $this->getUser();
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user) {
                $booking->setUser($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($booking);
                $entityManager->flush();
                if ($booking->getId() != null) {
                    $listSetRoom = $setRoomRepository->findListBooking($form->get('fromDate')->getData(),$form->get('toDate')->getData(),$form->get('room')->getData());

                    foreach ($listSetRoom as $list) {
                        $list->setRoomCOunt($list->getRoomCount()-$form->get('roomCount')->getData());
                        $entityManager->flush();
                    }
                    $session->remove('booking');
                }

                return $this->redirectToRoute('booking_index');
            }
        }


        return $this->render('booking/new.html.twig', [
            'info' => $info,
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_show", methods={"GET"})
     * @IsGranted("SHOW_BOOKING", subject="booking")
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }

    /**
     * @Route("/check", name="booking_check", methods={"POST"})
     * @IsGranted("CREATE_BOOKING")
     */
    public function checkBooking(Request $request, RoomRepository $roomRepository, SetRoomRepository $setRoomRepository) {
        $fromDate = date_create($request->request->get('fromDate'));
        $toDate = date_create($request->request->get('toDate'));
        $person = $request->request->get('person');
        $roomCount = $request->request->get('roomCount');
        $room = $roomRepository->find($request->request->get('roomId'));

        $datediff = abs(strtotime($request->request->get('fromDate')) - strtotime($request->request->get('toDate')));
        $countDay   = floor($datediff / (60 * 60 * 24));
        $setRooms = $setRoomRepository->checkRoomCount($fromDate, $toDate, $room, $person, $roomCount);
        if (count($setRooms) == $countDay) {
            $price = 0;
            foreach ($setRooms as $setRoom) {
                $price += $setRoom['price'] - ($setRoom['discount'] * $setRoom['price'] / 100);
            }
            $session = new Session();
            $infoBooking = [
                'room'      => $request->request->get('roomId'),
                'fromDate'  => $fromDate,
                'toDate'    => $toDate,
                'person'    => $person,
                'roomCount' => $roomCount,
                'price'     => $price
            ];
            $session->set('booking', $infoBooking);

            return $this->redirectToRoute('booking_new');
        }

        $this->addFlash('error', 'Thông tin đặt phòng chưa phù hợp, vui lòng kiểm tra lại!');
        return $this->redirectToRoute('room_show',[
            'id' => $request->request->get('roomId')
        ]);
    }
}
