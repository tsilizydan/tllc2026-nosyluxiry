<?php
class TripBuilderController extends Controller
{
    public function index(): void
    {
        $this->view('trip-builder.index', ['pageTitle' => $this->setTitle(__('nav.trip_builder'))]);
    }

    public function submit(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/trip-builder'); return; }
        $db = Database::getInstance();
        $db->insert('trip_requests', [
            'user_id' => Auth::id(),
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'destinations' => json_encode($this->input('destinations', [])),
            'travel_dates' => $this->input('travel_dates'),
            'duration_days' => (int) $this->input('duration', 7),
            'group_size' => (int) $this->input('group_size', 2),
            'budget_range' => $this->input('budget_range'),
            'interests' => json_encode($this->input('interests', [])),
            'accommodation_type' => $this->input('accommodation_type'),
            'special_requests' => $this->input('special_requests'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        Session::flash('success', 'Your custom trip request has been submitted! We\'ll prepare a personalized quote within 24 hours.');
        $this->redirect('/trip-builder');
    }
}
