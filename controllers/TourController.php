<?php
require_once MODELS_PATH . '/Tour.php';
require_once MODELS_PATH . '/Destination.php';

class TourController extends Controller
{
    public function index(): void
    {
        $tourModel = new Tour();
        $destModel = new Destination();
        $filters = [
            'type' => $this->query('type'),
            'destination' => $this->query('destination'),
            'min_price' => $this->query('min_price'),
            'max_price' => $this->query('max_price'),
            'duration' => $this->query('duration'),
            'sort' => $this->query('sort', 'featured'),
        ];
        $result = $tourModel->getFiltered($filters, $this->getPage());
        $this->view('tours.index', [
            'pageTitle' => $this->setTitle(__('tours.title')),
            'tours' => $result['items'],
            'pagination' => $result,
            'destinations' => $destModel->all('name ASC'),
            'filters' => $filters,
        ]);
    }

    public function show(string $slug): void
    {
        $tourModel = new Tour();
        $tour = $tourModel->withDestination($slug);
        if (!$tour) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }
        $itinerary = $tourModel->getItinerary($tour->id);
        $reviews = $tourModel->getReviews($tour->id);
        $this->view('tours.show', [
            'pageTitle' => $this->setTitle($tour->name),
            'tour' => $tour,
            'itinerary' => $itinerary,
            'reviews' => $reviews,
            'included' => json_decode($tour->included ?? '[]', true),
            'excluded' => json_decode($tour->excluded ?? '[]', true),
            'highlights' => json_decode($tour->highlights ?? '[]', true),
        ]);
    }

    public function apiList(): void
    {
        $tourModel = new Tour();
        $filters = ['type' => $this->query('type'), 'sort' => $this->query('sort', 'featured')];
        $result = $tourModel->getFiltered($filters, $this->getPage());
        $this->json($result);
    }

    public function submitReview(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $this->requireAuth();
        $db = Database::getInstance();
        $db->insert('reviews', [
            'tour_id' => (int) $this->input('tour_id'),
            'user_id' => Auth::id(),
            'reviewer_name' => Session::get('user_name'),
            'rating' => (int) $this->input('rating'),
            'title' => $this->input('title'),
            'comment' => $this->input('comment'),
            'is_verified' => Auth::check() ? 1 : 0,
        ]);
        $this->json(['success' => true, 'message' => 'Review submitted for approval.']);
    }
}
