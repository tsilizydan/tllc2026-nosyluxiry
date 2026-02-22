<?php
require_once MODELS_PATH . '/BlogPost.php';

class BlogController extends Controller
{
    public function index(): void
    {
        $model = new BlogPost();
        $result = $model->published($this->getPage());
        $this->view('blog.index', [
            'pageTitle' => $this->setTitle(__('nav.blog')),
            'posts' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function show(string $slug): void
    {
        $model = new BlogPost();
        $post = $model->withCategory($slug);
        if (!$post) { http_response_code(404); include VIEWS_PATH . '/errors/404.php'; return; }
        $model->incrementViews($post->id);
        $recent = $model->recent(3);
        $this->view('blog.show', [
            'pageTitle' => $this->setTitle($post->title),
            'post' => $post,
            'recentPosts' => $recent,
        ]);
    }
}
