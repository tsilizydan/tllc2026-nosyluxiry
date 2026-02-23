<?php
require_once MODELS_PATH . '/Booking.php';
require_once MODELS_PATH . '/Tour.php';
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Review.php';
require_once MODELS_PATH . '/BlogPost.php';
require_once MODELS_PATH . '/Destination.php';
require_once MODELS_PATH . '/Payment.php';
require_once MODELS_PATH . '/Setting.php';
require_once MODELS_PATH . '/Partner.php';
require_once MODELS_PATH . '/Ad.php';

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check() || !Auth::isAdmin()) {
            Session::flash('error', 'Access denied.');
            header('Location: ' . url('/login'));
            exit;
        }
    }

    // ─── FILE UPLOAD UTILITY ───
    private function handleUpload(string $field, string $subdir = 'general'): ?string
    {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $file = $_FILES[$field];
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            Session::flash('error', 'File too large (max 10MB).');
            return null;
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
            Session::flash('error', 'Invalid image type.');
            return null;
        }
        $ext = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'jpg',
        };
        $dir = UPLOADS_PATH . '/' . $subdir;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $path = $dir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $subdir . '/' . $filename;
        }
        return null;
    }

    // ══════════════════════════════════════
    // DASHBOARD
    // ══════════════════════════════════════
    public function dashboard(): void
    {
        $bookingModel = new Booking();
        $db = Database::getInstance();

        $stats = $bookingModel->stats();
        $recentBookings = $bookingModel->recentBookings(10);
        $totalUsers = $db->fetch("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
        $totalTours = $db->fetch("SELECT COUNT(*) as total FROM tours WHERE status = 'active'");
        $pendingReviews = $db->fetch("SELECT COUNT(*) as total FROM reviews WHERE is_approved = 0");
        $newMessages = $db->fetch("SELECT COUNT(*) as total FROM contact_messages WHERE is_read = 0");
        $tripRequests = $db->fetch("SELECT COUNT(*) as total FROM trip_requests WHERE status = 'new'");

        $this->view('admin.dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'layout' => 'admin',
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'totalUsers' => $totalUsers->total ?? 0,
            'totalTours' => $totalTours->total ?? 0,
            'pendingReviews' => $pendingReviews->total ?? 0,
            'newMessages' => $newMessages->total ?? 0,
            'tripRequests' => $tripRequests->total ?? 0,
        ]);
    }

    // ══════════════════════════════════════
    // TOURS CRUD
    // ══════════════════════════════════════
    public function tours(): void
    {
        $tourModel = new Tour();
        $result = $tourModel->paginate($this->getPage(), 20, '1', [], 'name ASC');
        $this->view('admin.tours', [
            'pageTitle' => 'Manage Tours',
            'layout' => 'admin',
            'tours' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function tourCreate(): void
    {
        $destModel = new Destination();
        $this->view('admin.tours-form', [
            'pageTitle' => 'Create Tour',
            'layout' => 'admin',
            'tour' => null,
            'destinations' => $destModel->where("status = 'active'", [], 'name ASC'),
        ]);
    }

    public function tourStore(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/tours'); return; }
        $tourModel = new Tour();
        $data = [
            'name' => $this->input('name'),
            'slug' => $tourModel->generateSlug($this->input('name')),
            'destination_id' => $this->input('destination_id') ?: null,
            'subtitle' => $this->input('subtitle'),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'type' => $this->input('type'),
            'duration_days' => (int) $this->input('duration_days', 1),
            'price' => (float) $this->input('price'),
            'sale_price' => $this->input('sale_price') ? (float) $this->input('sale_price') : null,
            'group_size_min' => (int) $this->input('group_size_min', 1),
            'group_size_max' => (int) $this->input('group_size_max', 12),
            'difficulty' => $this->input('difficulty', 'moderate'),
            'included' => $this->input('included') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('included'))))) : null,
            'excluded' => $this->input('excluded') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('excluded'))))) : null,
            'highlights' => $this->input('highlights') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('highlights'))))) : null,
            'departure_location' => $this->input('departure_location'),
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'is_bestseller' => $this->input('is_bestseller') ? 1 : 0,
            'status' => $this->input('status', 'draft'),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
        ];
        $img = $this->handleUpload('image', 'tours');
        if ($img) $data['image'] = $img;
        $tourModel->create($data);
        Session::flash('success', 'Tour created successfully.');
        $this->redirect('/admin/tours');
    }

    public function tourEdit(string $id): void
    {
        $tourModel = new Tour();
        $tour = $tourModel->find((int)$id);
        if (!$tour) { Session::flash('error', 'Tour not found.'); $this->redirect('/admin/tours'); return; }
        $destModel = new Destination();
        $this->view('admin.tours-form', [
            'pageTitle' => 'Edit Tour',
            'layout' => 'admin',
            'tour' => $tour,
            'destinations' => $destModel->where("status = 'active'", [], 'name ASC'),
        ]);
    }

    public function tourUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/tours'); return; }
        $tourModel = new Tour();
        $tour = $tourModel->find((int)$id);
        if (!$tour) { $this->redirect('/admin/tours'); return; }
        $data = [
            'name' => $this->input('name'),
            'slug' => $tourModel->generateSlug($this->input('name'), (int)$id),
            'destination_id' => $this->input('destination_id') ?: null,
            'subtitle' => $this->input('subtitle'),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'type' => $this->input('type'),
            'duration_days' => (int) $this->input('duration_days', 1),
            'price' => (float) $this->input('price'),
            'sale_price' => $this->input('sale_price') ? (float) $this->input('sale_price') : null,
            'group_size_min' => (int) $this->input('group_size_min', 1),
            'group_size_max' => (int) $this->input('group_size_max', 12),
            'difficulty' => $this->input('difficulty', 'moderate'),
            'included' => $this->input('included') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('included'))))) : null,
            'excluded' => $this->input('excluded') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('excluded'))))) : null,
            'highlights' => $this->input('highlights') ? json_encode(array_filter(array_map('trim', explode("\n", $this->input('highlights'))))) : null,
            'departure_location' => $this->input('departure_location'),
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'is_bestseller' => $this->input('is_bestseller') ? 1 : 0,
            'status' => $this->input('status', 'draft'),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
        ];
        $img = $this->handleUpload('image', 'tours');
        if ($img) $data['image'] = $img;
        $tourModel->update((int)$id, $data);
        Session::flash('success', 'Tour updated successfully.');
        $this->redirect('/admin/tours');
    }

    public function tourDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/tours'); return; }
        $tourModel = new Tour();
        $tourModel->delete((int)$id);
        Session::flash('success', 'Tour deleted.');
        $this->redirect('/admin/tours');
    }

    // ══════════════════════════════════════
    // DESTINATIONS CRUD
    // ══════════════════════════════════════
    public function destinations(): void
    {
        $model = new Destination();
        $result = $model->paginate($this->getPage(), 20, '1', [], 'sort_order ASC, name ASC');
        $this->view('admin.destinations', [
            'pageTitle' => 'Manage Destinations',
            'layout' => 'admin',
            'destinations' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function destinationCreate(): void
    {
        $this->view('admin.destinations-form', [
            'pageTitle' => 'Create Destination',
            'layout' => 'admin',
            'destination' => null,
        ]);
    }

    public function destinationStore(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/destinations'); return; }
        $model = new Destination();
        $data = [
            'name' => $this->input('name'),
            'slug' => $model->generateSlug($this->input('name')),
            'region' => $this->input('region'),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'best_time' => $this->input('best_time'),
            'map_lat' => $this->input('map_lat') ?: null,
            'map_lng' => $this->input('map_lng') ?: null,
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'status' => $this->input('status', 'draft'),
            'sort_order' => (int) $this->input('sort_order', 0),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
        ];
        $img = $this->handleUpload('image', 'destinations');
        if ($img) $data['image'] = $img;
        $model->create($data);
        Session::flash('success', 'Destination created successfully.');
        $this->redirect('/admin/destinations');
    }

    public function destinationEdit(string $id): void
    {
        $model = new Destination();
        $dest = $model->find((int)$id);
        if (!$dest) { Session::flash('error', 'Destination not found.'); $this->redirect('/admin/destinations'); return; }
        $this->view('admin.destinations-form', [
            'pageTitle' => 'Edit Destination',
            'layout' => 'admin',
            'destination' => $dest,
        ]);
    }

    public function destinationUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/destinations'); return; }
        $model = new Destination();
        $data = [
            'name' => $this->input('name'),
            'slug' => $model->generateSlug($this->input('name'), (int)$id),
            'region' => $this->input('region'),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'best_time' => $this->input('best_time'),
            'map_lat' => $this->input('map_lat') ?: null,
            'map_lng' => $this->input('map_lng') ?: null,
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'status' => $this->input('status', 'draft'),
            'sort_order' => (int) $this->input('sort_order', 0),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
        ];
        $img = $this->handleUpload('image', 'destinations');
        if ($img) $data['image'] = $img;
        $model->update((int)$id, $data);
        Session::flash('success', 'Destination updated.');
        $this->redirect('/admin/destinations');
    }

    public function destinationDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/destinations'); return; }
        $model = new Destination();
        $model->delete((int)$id);
        Session::flash('success', 'Destination deleted.');
        $this->redirect('/admin/destinations');
    }

    // ══════════════════════════════════════
    // BOOKINGS MANAGEMENT
    // ══════════════════════════════════════
    public function bookings(): void
    {
        $bookingModel = new Booking();
        $where = '1=1';
        $params = [];
        $status = $this->query('status');
        if ($status) { $where .= ' AND status = ?'; $params[] = $status; }
        $result = $bookingModel->paginate($this->getPage(), 20, $where, $params, 'created_at DESC');
        $this->view('admin.bookings', [
            'pageTitle' => 'Manage Bookings',
            'layout' => 'admin',
            'bookings' => $result['items'],
            'pagination' => $result,
            'filterStatus' => $status,
        ]);
    }

    public function bookingView(string $id): void
    {
        $bookingModel = new Booking();
        $booking = $this->db->fetch(
            "SELECT b.*, t.name as tour_name, t.slug as tour_slug, t.image as tour_image, u.first_name, u.last_name, u.email as user_email
             FROM bookings b
             JOIN tours t ON b.tour_id = t.id
             LEFT JOIN users u ON b.user_id = u.id
             WHERE b.id = ?", [(int)$id]
        );
        if (!$booking) { Session::flash('error', 'Booking not found.'); $this->redirect('/admin/bookings'); return; }
        $paymentModel = new Payment();
        $payments = $paymentModel->forBooking((int)$id);
        $this->view('admin.booking-view', [
            'pageTitle' => 'Booking #' . $booking->reference,
            'layout' => 'admin',
            'booking' => $booking,
            'payments' => $payments,
        ]);
    }

    public function updateBookingStatus(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $status = $this->input('status');
        $allowed = ['pending', 'confirmed', 'paid', 'completed', 'cancelled', 'refunded'];
        if (!in_array($status, $allowed, true)) {
            Session::flash('error', 'Invalid status value.');
            $this->redirect('/admin/bookings');
            return;
        }
        $id = (int) $this->input('id');

        // Update payment_status if booking is confirmed/paid
        $paymentStatus = null;
        if ($status === 'paid') $paymentStatus = 'paid';
        if ($status === 'refunded') $paymentStatus = 'refunded';

        $updateData = ['status' => $status];
        if ($paymentStatus) $updateData['payment_status'] = $paymentStatus;

        $this->db->update('bookings', $updateData, 'id = ?', [$id]);
        Session::flash('success', 'Booking status updated.');
        $this->redirect('/admin/bookings');
    }

    // ══════════════════════════════════════
    // USERS MANAGEMENT
    // ══════════════════════════════════════
    public function users(): void
    {
        $userModel = new User();
        $result = $userModel->paginate($this->getPage(), 20, '1', [], 'created_at DESC');
        $this->view('admin.users', [
            'pageTitle' => 'Manage Users',
            'layout' => 'admin',
            'users' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function userToggleStatus(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/users'); return; }
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [(int)$id]);
        if (!$user) { $this->redirect('/admin/users'); return; }
        $newStatus = $user->status === 'active' ? 'banned' : 'active';
        $this->db->update('users', ['status' => $newStatus], 'id = ?', [(int)$id]);
        Session::flash('success', 'User status changed to ' . $newStatus . '.');
        $this->redirect('/admin/users');
    }

    // ══════════════════════════════════════
    // REVIEWS MANAGEMENT
    // ══════════════════════════════════════
    public function reviews(): void
    {
        $reviews = $this->db->fetchAll(
            "SELECT r.*, t.name as tour_name
             FROM reviews r
             LEFT JOIN tours t ON r.tour_id = t.id
             ORDER BY r.created_at DESC
             LIMIT 100"
        );
        $this->view('admin.reviews', [
            'pageTitle' => 'Manage Reviews',
            'layout' => 'admin',
            'reviews' => $reviews,
        ]);
    }

    public function approveReview(): void
    {
        if (!$this->validateCsrf()) { $this->json(['error' => 'Invalid token'], 403); return; }
        $id = (int) $this->input('id');
        $action = $this->input('action', 'approve');
        if ($action === 'reject') {
            $this->db->delete('reviews', 'id = ?', [$id]);
            Session::flash('success', 'Review deleted.');
        } else {
            $this->db->update('reviews', ['is_approved' => 1], 'id = ?', [$id]);
            Session::flash('success', 'Review approved.');
        }
        $this->redirect('/admin/reviews');
    }

    // ══════════════════════════════════════
    // MESSAGES
    // ══════════════════════════════════════
    public function messages(): void
    {
        $messages = $this->db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 100");
        $this->view('admin.messages', [
            'pageTitle' => 'Messages',
            'layout' => 'admin',
            'messages' => $messages,
        ]);
    }

    public function messageRead(string $id): void
    {
        $this->db->update('contact_messages', ['is_read' => 1], 'id = ?', [(int)$id]);
        Session::flash('success', 'Message marked as read.');
        $this->redirect('/admin/messages');
    }

    public function messageDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/messages'); return; }
        $this->db->delete('contact_messages', 'id = ?', [(int)$id]);
        Session::flash('success', 'Message deleted.');
        $this->redirect('/admin/messages');
    }

    // ══════════════════════════════════════
    // BLOG CRUD
    // ══════════════════════════════════════
    public function blogPosts(): void
    {
        $model = new BlogPost();
        $result = $model->paginate($this->getPage(), 20, '1', [], 'created_at DESC');
        $this->view('admin.blog', [
            'pageTitle' => 'Manage Blog',
            'layout' => 'admin',
            'posts' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function blogCreate(): void
    {
        $categories = $this->db->fetchAll("SELECT * FROM blog_categories ORDER BY name");
        $this->view('admin.blog-form', [
            'pageTitle' => 'Create Blog Post',
            'layout' => 'admin',
            'post' => null,
            'categories' => $categories,
        ]);
    }

    public function blogStore(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/blog'); return; }
        $model = new BlogPost();
        $data = [
            'title' => $this->input('title'),
            'slug' => $model->generateSlug($this->input('title')),
            'category_id' => $this->input('category_id') ?: null,
            'author_id' => Auth::id(),
            'excerpt' => $this->input('excerpt'),
            'content' => $this->input('content'),
            'tags' => $this->input('tags'),
            'status' => $this->input('status', 'draft'),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
            'published_at' => $this->input('status') === 'published' ? date('Y-m-d H:i:s') : null,
        ];
        $img = $this->handleUpload('image', 'blog');
        if ($img) $data['image'] = $img;
        $model->create($data);
        Session::flash('success', 'Blog post created.');
        $this->redirect('/admin/blog');
    }

    public function blogEdit(string $id): void
    {
        $model = new BlogPost();
        $post = $model->find((int)$id);
        if (!$post) { Session::flash('error', 'Post not found.'); $this->redirect('/admin/blog'); return; }
        $categories = $this->db->fetchAll("SELECT * FROM blog_categories ORDER BY name");
        $this->view('admin.blog-form', [
            'pageTitle' => 'Edit Blog Post',
            'layout' => 'admin',
            'post' => $post,
            'categories' => $categories,
        ]);
    }

    public function blogUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/blog'); return; }
        $model = new BlogPost();
        $post = $model->find((int)$id);
        if (!$post) { $this->redirect('/admin/blog'); return; }
        $data = [
            'title' => $this->input('title'),
            'slug' => $model->generateSlug($this->input('title'), (int)$id),
            'category_id' => $this->input('category_id') ?: null,
            'excerpt' => $this->input('excerpt'),
            'content' => $this->input('content'),
            'tags' => $this->input('tags'),
            'status' => $this->input('status', 'draft'),
            'meta_title' => $this->input('meta_title'),
            'meta_description' => $this->input('meta_description'),
        ];
        if ($this->input('status') === 'published' && !$post->published_at) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        $img = $this->handleUpload('image', 'blog');
        if ($img) $data['image'] = $img;
        $model->update((int)$id, $data);
        Session::flash('success', 'Blog post updated.');
        $this->redirect('/admin/blog');
    }

    public function blogDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/blog'); return; }
        $model = new BlogPost();
        $model->delete((int)$id);
        Session::flash('success', 'Blog post deleted.');
        $this->redirect('/admin/blog');
    }

    // ══════════════════════════════════════
    // PAYMENTS
    // ══════════════════════════════════════
    public function payments(): void
    {
        $paymentModel = new Payment();
        $filters = [
            'status' => $this->query('status'),
            'gateway' => $this->query('gateway'),
            'date_from' => $this->query('date_from'),
            'date_to' => $this->query('date_to'),
        ];
        $result = $paymentModel->paginatedTransactions($this->getPage(), 20, $filters);
        $gatewayStats = $paymentModel->totalByGateway();
        $this->view('admin.payments', [
            'pageTitle' => 'Payments & Transactions',
            'layout' => 'admin',
            'payments' => $result['items'],
            'pagination' => $result,
            'filters' => $filters,
            'gatewayStats' => $gatewayStats,
        ]);
    }

    public function paymentValidate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/payments'); return; }
        $paymentModel = new Payment();
        $payment = $paymentModel->find((int)$id);
        if (!$payment) { $this->redirect('/admin/payments'); return; }
        $paymentModel->markCompleted((int)$id, 'MANUAL-' . date('YmdHis'));
        // Also update booking status
        $this->db->update('bookings', [
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ], 'id = ?', [$payment->booking_id]);
        Session::flash('success', 'Payment validated and booking confirmed.');
        $this->redirect('/admin/payments');
    }

    // ══════════════════════════════════════
    // PARTNERS CRUD
    // ══════════════════════════════════════
    public function partners(): void
    {
        $model = new Partner();
        $result = $model->adminList($this->getPage());
        $this->view('admin.partners', [
            'pageTitle' => 'Manage Partners',
            'layout' => 'admin',
            'partners' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function partnerCreate(): void
    {
        $this->view('admin.partners-form', [
            'pageTitle' => 'Add Partner',
            'layout' => 'admin',
            'partner' => null,
        ]);
    }

    public function partnerStore(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/partners'); return; }
        $model = new Partner();
        $data = [
            'type' => $this->input('type'),
            'name' => $this->input('name'),
            'slug' => $model->generateSlug($this->input('name')),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'location' => $this->input('location'),
            'contact_email' => $this->input('contact_email'),
            'contact_phone' => $this->input('contact_phone'),
            'website' => $this->input('website'),
            'rating' => $this->input('rating') ?: null,
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'status' => $this->input('status', 'active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        $img = $this->handleUpload('image', 'partners');
        if ($img) $data['image'] = $img;
        $model->create($data);
        Session::flash('success', 'Partner added.');
        $this->redirect('/admin/partners');
    }

    public function partnerEdit(string $id): void
    {
        $model = new Partner();
        $partner = $model->find((int)$id);
        if (!$partner) { Session::flash('error', 'Partner not found.'); $this->redirect('/admin/partners'); return; }
        $this->view('admin.partners-form', [
            'pageTitle' => 'Edit Partner',
            'layout' => 'admin',
            'partner' => $partner,
        ]);
    }

    public function partnerUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/partners'); return; }
        $model = new Partner();
        $data = [
            'type' => $this->input('type'),
            'name' => $this->input('name'),
            'slug' => $model->generateSlug($this->input('name'), (int)$id),
            'description' => $this->input('description'),
            'short_description' => $this->input('short_description'),
            'location' => $this->input('location'),
            'contact_email' => $this->input('contact_email'),
            'contact_phone' => $this->input('contact_phone'),
            'website' => $this->input('website'),
            'rating' => $this->input('rating') ?: null,
            'is_featured' => $this->input('is_featured') ? 1 : 0,
            'status' => $this->input('status', 'active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        $img = $this->handleUpload('image', 'partners');
        if ($img) $data['image'] = $img;
        $model->update((int)$id, $data);
        Session::flash('success', 'Partner updated.');
        $this->redirect('/admin/partners');
    }

    public function partnerDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/partners'); return; }
        $model = new Partner();
        $model->delete((int)$id);
        Session::flash('success', 'Partner deleted.');
        $this->redirect('/admin/partners');
    }

    // ══════════════════════════════════════
    // ADS CRUD
    // ══════════════════════════════════════
    public function ads(): void
    {
        $model = new Ad();
        $result = $model->adminList($this->getPage());
        $this->view('admin.ads', [
            'pageTitle' => 'Manage Ads',
            'layout' => 'admin',
            'ads' => $result['items'],
            'pagination' => $result,
        ]);
    }

    public function adCreate(): void
    {
        $this->view('admin.ads-form', [
            'pageTitle' => 'Create Ad',
            'layout' => 'admin',
            'ad' => null,
        ]);
    }

    public function adStore(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/ads'); return; }
        $model = new Ad();
        $data = [
            'title' => $this->input('title'),
            'link' => $this->input('link'),
            'placement' => $this->input('placement', 'sidebar'),
            'html_content' => $this->input('html_content'),
            'start_date' => $this->input('start_date') ?: null,
            'end_date' => $this->input('end_date') ?: null,
            'is_active' => $this->input('is_active') ? 1 : 0,
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        $img = $this->handleUpload('image', 'ads');
        if ($img) $data['image'] = $img;
        $model->create($data);
        Session::flash('success', 'Ad created.');
        $this->redirect('/admin/ads');
    }

    public function adEdit(string $id): void
    {
        $model = new Ad();
        $ad = $model->find((int)$id);
        if (!$ad) { Session::flash('error', 'Ad not found.'); $this->redirect('/admin/ads'); return; }
        $this->view('admin.ads-form', [
            'pageTitle' => 'Edit Ad',
            'layout' => 'admin',
            'ad' => $ad,
        ]);
    }

    public function adUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/ads'); return; }
        $model = new Ad();
        $data = [
            'title' => $this->input('title'),
            'link' => $this->input('link'),
            'placement' => $this->input('placement', 'sidebar'),
            'html_content' => $this->input('html_content'),
            'start_date' => $this->input('start_date') ?: null,
            'end_date' => $this->input('end_date') ?: null,
            'is_active' => $this->input('is_active') ? 1 : 0,
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        $img = $this->handleUpload('image', 'ads');
        if ($img) $data['image'] = $img;
        $model->update((int)$id, $data);
        Session::flash('success', 'Ad updated.');
        $this->redirect('/admin/ads');
    }

    public function adDelete(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/ads'); return; }
        $model = new Ad();
        $model->delete((int)$id);
        Session::flash('success', 'Ad deleted.');
        $this->redirect('/admin/ads');
    }

    // ══════════════════════════════════════
    // SETTINGS
    // ══════════════════════════════════════
    public function settings(): void
    {
        $model = new Setting();
        $settings = $model->allAsArray();
        $paymentMethods = $this->db->fetchAll("SELECT * FROM payment_methods ORDER BY sort_order");
        $this->view('admin.settings', [
            'pageTitle' => 'Settings',
            'layout' => 'admin',
            'settings' => $settings,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function settingsSave(): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/settings'); return; }
        $model = new Setting();
        $group = $this->input('group', 'general');

        // Collect all setting_* fields
        $data = [];
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'setting_')) {
                $settingKey = substr($key, 8); // Remove 'setting_' prefix
                $data[$settingKey] = $value;
            }
        }

        if (!empty($data)) {
            $model->saveGroup($group, $data);
        }

        // Handle payment method toggles
        if ($group === 'booking') {
            $activeMethods = $this->input('active_payment_methods') ?? [];
            if (is_array($activeMethods)) {
                $this->db->query("UPDATE payment_methods SET is_active = 0");
                foreach ($activeMethods as $code) {
                    $this->db->update('payment_methods', ['is_active' => 1], 'code = ?', [$code]);
                }
            }
        }

        Session::flash('success', 'Settings saved successfully.');
        $this->redirect('/admin/settings?tab=' . $group);
    }

    // ══════════════════════════════════════
    // TRIP REQUESTS
    // ══════════════════════════════════════
    public function tripRequests(): void
    {
        $requests = $this->db->fetchAll("SELECT * FROM trip_requests ORDER BY created_at DESC LIMIT 100");
        $this->view('admin.trip-requests', [
            'pageTitle' => 'Trip Requests',
            'layout' => 'admin',
            'requests' => $requests,
        ]);
    }

    public function tripRequestUpdate(string $id): void
    {
        if (!$this->validateCsrf()) { Session::flash('error', 'Invalid token.'); $this->redirect('/admin/trip-requests'); return; }
        $this->db->update('trip_requests', [
            'status' => $this->input('status'),
            'admin_notes' => $this->input('admin_notes'),
        ], 'id = ?', [(int)$id]);
        Session::flash('success', 'Trip request updated.');
        $this->redirect('/admin/trip-requests');
    }
}
