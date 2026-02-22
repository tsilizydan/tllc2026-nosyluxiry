<?php
require_once MODELS_PATH . '/Destination.php';
require_once MODELS_PATH . '/Tour.php';

class DestinationController extends Controller
{
    public function index(): void
    {
        $model = new Destination();
        $this->view('destinations.index', [
            'pageTitle' => $this->setTitle(__('nav.destinations')),
            'destinations' => $model->withTourCount(),
        ]);
    }

    public function show(string $slug): void
    {
        $model = new Destination();
        $dest = $model->findBySlug($slug);
        if (!$dest) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }
        $tourModel = new Tour();
        $tours = $tourModel->where("destination_id = ? AND status = 'active'", [$dest->id]);
        $this->view('destinations.show', [
            'pageTitle' => $this->setTitle($dest->name),
            'destination' => $dest,
            'tours' => $tours,
        ]);
    }
}
