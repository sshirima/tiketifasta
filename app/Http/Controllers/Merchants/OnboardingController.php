<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/11/2018
 * Time: 10:42 AM
 */

namespace App\Http\Controllers\Merchants;


use App\Models\Ticket;
use App\Services\Tickets\TicketManager;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class OnboardingController extends BaseController
{
    use TicketManager;

    /**
     * OnboardingController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayForm()
    {
        return view('users.pages.onboarding_form');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTicketInformation(Request $request)
    {
        $ticket = null;

        if (!$request->has('ticket_reference')) {
            return view('users.pages.onboarding_form')->withErrors(['ticket reference required']);
        }

        $ticket = Ticket::where(['ticket_ref' => $request->get('ticket_reference')])->first();

        if (!isset($ticket)) {
            return view('users.pages.onboarding_form')->withErrors(['Ticket not found, reference: ' . $request->get('ticket_reference')]);
        }

        $booking = $ticket->booking;
        $trip = $booking->trip;

        return view('users.pages.onboarding_confirm')->with(['ticket' => $ticket, 'booking' => $booking, 'trip' => $trip]);
    }


    /**
     * @param Request $request
     * @return $this|array
     */
    public function confirmBoarded(Request $request)
    {
        if (!$request->has('ticket_reference')) {
            return back()->withErrors(['ticket reference required']);
        }

        $ticket = Ticket::where(['ticket_ref' => $request->get('ticket_reference')])->first();

        if (!isset($ticket)) {
            return back()->withErrors(['ticket not found']);
        }

        $this->setTicketBoarded($ticket);

        return view('users.pages.onboarding_confirm_response');
    }
}