<?php
/**
 * Routes — URL to Controller mapping
 */

// ─── Public Routes ───
$router->get('/', 'HomeController@index', 'home');
$router->get('/destinations', 'DestinationController@index', 'destinations');
$router->get('/destinations/{slug}', 'DestinationController@show', 'destination.show');
$router->get('/tours', 'TourController@index', 'tours');
$router->get('/tours/{slug}', 'TourController@show', 'tour.show');
$router->get('/trip-builder', 'TripBuilderController@index', 'trip.builder');
$router->post('/trip-builder', 'TripBuilderController@submit', 'trip.builder.submit');
$router->get('/about', 'PageController@about', 'about');
$router->get('/trust', 'PageController@trust', 'trust');
$router->get('/contact', 'PageController@contact', 'contact');
$router->post('/contact', 'PageController@contactSubmit', 'contact.submit');
$router->get('/blog', 'BlogController@index', 'blog');
$router->get('/blog/{slug}', 'BlogController@show', 'blog.show');

// ─── Booking Routes ───
$router->get('/booking/{tourId}', 'BookingController@create', 'booking.create');
$router->post('/booking/{tourId}', 'BookingController@store', 'booking.store');
$router->get('/booking/confirmation/{reference}', 'BookingController@confirmation', 'booking.confirmation');

// ─── Payment Routes ───
$router->post('/payment/process/{bookingId}', 'PaymentController@process', 'payment.process');
$router->get('/payment/stripe/success/{reference}', 'PaymentController@stripeSuccess', 'payment.stripe.success');
$router->get('/payment/stripe/cancel/{reference}', 'PaymentController@stripeCancel', 'payment.stripe.cancel');
$router->post('/payment/stripe/webhook', 'PaymentController@stripeWebhook', 'payment.stripe.webhook');
$router->get('/payment/paypal/return/{reference}', 'PaymentController@paypalReturn', 'payment.paypal.return');
$router->get('/payment/paypal/cancel/{reference}', 'PaymentController@paypalCancel', 'payment.paypal.cancel');
$router->get('/payment/bank/{reference}', 'PaymentController@bankInstructions', 'payment.bank');
$router->get('/payment/cash/{reference}', 'PaymentController@cashDeposit', 'payment.cash');

// ─── Auth Routes ───
$router->get('/login', 'AuthController@loginForm', 'login');
$router->post('/login', 'AuthController@login', 'login.submit');
$router->get('/register', 'AuthController@registerForm', 'register');
$router->post('/register', 'AuthController@register', 'register.submit');
$router->get('/logout', 'AuthController@logout', 'logout');

// ─── User Account Routes ───
$router->get('/account', 'AccountController@dashboard', 'account');
$router->get('/account/profile', 'AccountController@profile', 'account.profile');
$router->post('/account/profile', 'AccountController@updateProfile', 'account.profile.update');
$router->post('/account/password', 'AccountController@changePassword', 'account.password');
$router->get('/account/bookings', 'AccountController@bookings', 'account.bookings');
$router->post('/account/bookings/cancel', 'AccountController@cancelBooking', 'account.bookings.cancel');
$router->get('/account/bookings/{reference}/review', 'AccountController@reviewForm', 'account.review');
$router->post('/account/bookings/{reference}/review', 'AccountController@submitReview', 'account.review.submit');
$router->get('/account/wishlist', 'AccountController@wishlist', 'account.wishlist');
$router->post('/account/wishlist/toggle', 'AccountController@toggleWishlist', 'account.wishlist.toggle');

// ─── Partners ───
$router->get('/partners', 'PageController@partners', 'partners');

// ─── Language Switch ───
$router->get('/switch-lang/{code}', 'PageController@switchLang', 'lang.switch');

// ─── Newsletter ───
$router->post('/newsletter', 'PageController@newsletter', 'newsletter');

// ─── API (AJAX) ───
$router->get('/api/tours', 'TourController@apiList', 'api.tours');
$router->get('/api/availability/{tourId}', 'BookingController@checkAvailability', 'api.availability');
$router->post('/api/review', 'TourController@submitReview', 'api.review');

// ─── Admin Routes ───
$router->get('/admin', 'AdminController@dashboard', 'admin.dashboard');

// Tours CRUD
$router->get('/admin/tours', 'AdminController@tours', 'admin.tours');
$router->get('/admin/tours/create', 'AdminController@tourCreate', 'admin.tours.create');
$router->post('/admin/tours/create', 'AdminController@tourStore', 'admin.tours.store');
$router->get('/admin/tours/edit/{id}', 'AdminController@tourEdit', 'admin.tours.edit');
$router->post('/admin/tours/edit/{id}', 'AdminController@tourUpdate', 'admin.tours.update');
$router->post('/admin/tours/delete/{id}', 'AdminController@tourDelete', 'admin.tours.delete');

// Destinations CRUD
$router->get('/admin/destinations', 'AdminController@destinations', 'admin.destinations');
$router->get('/admin/destinations/create', 'AdminController@destinationCreate', 'admin.destinations.create');
$router->post('/admin/destinations/create', 'AdminController@destinationStore', 'admin.destinations.store');
$router->get('/admin/destinations/edit/{id}', 'AdminController@destinationEdit', 'admin.destinations.edit');
$router->post('/admin/destinations/edit/{id}', 'AdminController@destinationUpdate', 'admin.destinations.update');
$router->post('/admin/destinations/delete/{id}', 'AdminController@destinationDelete', 'admin.destinations.delete');

// Bookings
$router->get('/admin/bookings', 'AdminController@bookings', 'admin.bookings');
$router->get('/admin/bookings/view/{id}', 'AdminController@bookingView', 'admin.bookings.view');
$router->post('/admin/bookings/status', 'AdminController@updateBookingStatus', 'admin.bookings.status');

// Users
$router->get('/admin/users', 'AdminController@users', 'admin.users');
$router->post('/admin/users/toggle/{id}', 'AdminController@userToggleStatus', 'admin.users.toggle');

// Reviews
$router->get('/admin/reviews', 'AdminController@reviews', 'admin.reviews');
$router->post('/admin/reviews/approve', 'AdminController@approveReview', 'admin.reviews.approve');

// Messages
$router->get('/admin/messages', 'AdminController@messages', 'admin.messages');
$router->post('/admin/messages/read/{id}', 'AdminController@messageRead', 'admin.messages.read');
$router->post('/admin/messages/delete/{id}', 'AdminController@messageDelete', 'admin.messages.delete');

// Blog CRUD
$router->get('/admin/blog', 'AdminController@blogPosts', 'admin.blog');
$router->get('/admin/blog/create', 'AdminController@blogCreate', 'admin.blog.create');
$router->post('/admin/blog/create', 'AdminController@blogStore', 'admin.blog.store');
$router->get('/admin/blog/edit/{id}', 'AdminController@blogEdit', 'admin.blog.edit');
$router->post('/admin/blog/edit/{id}', 'AdminController@blogUpdate', 'admin.blog.update');
$router->post('/admin/blog/delete/{id}', 'AdminController@blogDelete', 'admin.blog.delete');

// Payments
$router->get('/admin/payments', 'AdminController@payments', 'admin.payments');
$router->post('/admin/payments/validate/{id}', 'AdminController@paymentValidate', 'admin.payments.validate');

// Partners CRUD
$router->get('/admin/partners', 'AdminController@partners', 'admin.partners');
$router->get('/admin/partners/create', 'AdminController@partnerCreate', 'admin.partners.create');
$router->post('/admin/partners/create', 'AdminController@partnerStore', 'admin.partners.store');
$router->get('/admin/partners/edit/{id}', 'AdminController@partnerEdit', 'admin.partners.edit');
$router->post('/admin/partners/edit/{id}', 'AdminController@partnerUpdate', 'admin.partners.update');
$router->post('/admin/partners/delete/{id}', 'AdminController@partnerDelete', 'admin.partners.delete');

// Ads CRUD
$router->get('/admin/ads', 'AdminController@ads', 'admin.ads');
$router->get('/admin/ads/create', 'AdminController@adCreate', 'admin.ads.create');
$router->post('/admin/ads/create', 'AdminController@adStore', 'admin.ads.store');
$router->get('/admin/ads/edit/{id}', 'AdminController@adEdit', 'admin.ads.edit');
$router->post('/admin/ads/edit/{id}', 'AdminController@adUpdate', 'admin.ads.update');
$router->post('/admin/ads/delete/{id}', 'AdminController@adDelete', 'admin.ads.delete');

// Settings
$router->get('/admin/settings', 'AdminController@settings', 'admin.settings');
$router->post('/admin/settings', 'AdminController@settingsSave', 'admin.settings.save');

// Trip Requests
$router->get('/admin/trip-requests', 'AdminController@tripRequests', 'admin.trip-requests');
$router->post('/admin/trip-requests/update/{id}', 'AdminController@tripRequestUpdate', 'admin.trip-requests.update');

