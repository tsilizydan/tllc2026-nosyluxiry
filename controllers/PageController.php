<?php
class PageController extends Controller
{
    public function about(): void
    {
        $this->view('pages.about', ['pageTitle' => $this->setTitle(__('nav.about'))]);
    }

    public function trust(): void
    {
        $this->view('pages.trust', ['pageTitle' => $this->setTitle(__('nav.trust'))]);
    }

    public function contact(): void
    {
        $this->view('pages.contact', ['pageTitle' => $this->setTitle(__('contact.title'))]);
    }

    public function contactSubmit(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/contact'); return; }
        $db = Database::getInstance();
        $db->insert('contact_messages', [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'subject' => $this->input('subject'),
            'message' => $this->input('message'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        Session::flash('success', __('contact.success'));
        $this->redirect('/contact');
    }

    public function switchLang(string $code): void
    {
        Language::set($code);
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        header('Location: ' . $referer);
        exit;
    }

    public function newsletter(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $email = $this->input('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Invalid email address.');
            $this->redirect('/');
            return;
        }
        $db = Database::getInstance();
        try {
            $db->insert('newsletter_subscribers', ['email' => $email]);
        } catch (Exception $e) { /* duplicate email — ignore */ }
        Session::flash('success', 'Successfully subscribed!');
        $this->redirect('/');
    }

    public function partners(): void
    {
        require_once MODELS_PATH . '/Partner.php';
        $partnerModel = new Partner();
        $this->view('pages.partners', [
            'pageTitle' => $this->setTitle('Our Partners'),
            'grouped' => $partnerModel->activeGrouped(),
            'typeLabels' => Partner::typeLabels(),
        ]);
    }
}
