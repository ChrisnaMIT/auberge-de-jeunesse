<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ClientRoomController extends AbstractController
{
    #[Route('/rooms/{id}/availability', name: 'client_room_availability')]
    public function availability(Room $room): Response
    {
        return $this->render('client/rooms/availability.html.twig', [
            'room' => $room,
        ]);
    }


    //


    #[Route('/api/room/{id}/availability', name: 'api_room_availability')]
    public function apiAvailability(Room $room, BookingRepository $bookingRepository): JsonResponse
    {
        $today = new \DateTime();
        $end = (new \DateTime())->modify('+12 months');

        $events = [];

        $period = new \DatePeriod($today, new \DateInterval('P1D'), $end);

        foreach ($period as $day) {

            $bookings = $bookingRepository->findActiveBookingsForRoomOnDate($room, $day);

            // Total lits
            $totalBeds = $room->getBeds()->count();

            // Lits occupés ce jour-là
            $bedsTaken = 0;
            foreach ($bookings as $booking) {
                $bedsTaken += $booking->getNumberOfBedsReserved();
            }

            $bedsAvailable = max(0, $totalBeds - $bedsTaken);

            $events[] = [
                'title' => $bedsAvailable . ' lits',
                'start' => $day->format('Y-m-d'),
                'beds_available' => $bedsAvailable,
            ];
        }

        return new JsonResponse($events);
    }


    //


    #[Route('/rooms/{id}/book', name: 'client_room_book')]
    public function book(Room $room, Request $request): Response
    {
        $date = $request->query->get('date');

        return $this->render('client/rooms/book.html.twig', [
            'room' => $room,
            'date' => $date
        ]);
    }



}
