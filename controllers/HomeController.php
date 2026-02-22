<?php
require_once MODELS_PATH . '/Tour.php';
require_once MODELS_PATH . '/Destination.php';
require_once MODELS_PATH . '/Review.php';
require_once MODELS_PATH . '/BlogPost.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $tourModel = new Tour();
        $destModel = new Destination();
        $reviewModel = new Review();
        $blogModel = new BlogPost();

        $this->view('home.index', [
            'pageTitle' => APP_NAME . ' — ' . APP_TAGLINE,
            'featuredTours' => $tourModel->featured(6),
            'destinations' => $destModel->withTourCount(),
            'testimonials' => $reviewModel->featured(4),
            'recentPosts' => $blogModel->recent(3),
        ]);
    }
}
