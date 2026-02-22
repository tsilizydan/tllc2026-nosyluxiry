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

// ─── Auth Routes ───
$router->get('/login', 'AuthController@loginForm', 'login');
$router->post('/login', 'AuthController@login', 'login.submit');
$router->get('/register', 'AuthController@registerForm', 'register');
$router->post('/register', 'AuthController@register', 'register.submit');
$router->get('/logout', 'AuthController@logout', 'logout');

// ─── User Account Routes ───
$router->get('/account', 'AccountController@dashboard', 'account');
$router->get('/account/bookings', 'AccountController@bookings', 'account.bookings');
$router->get('/account/wishlist', 'AccountController@wishlist', 'account.wishlist');
$router->post('/account/wishlist/toggle', 'AccountController@toggleWishlist', 'account.wishlist.toggle');

// ─── Language Switch ───
$router->get('/lang/{code}', 'PageController@switchLang', 'lang.switch');

// ─── Newsletter ───
$router->post('/newsletter', 'PageController@newsletter', 'newsletter');

// ─── API (AJAX) ───
$router->get('/api/tours', 'TourController@apiList', 'api.tours');
$router->get('/api/availability/{tourId}', 'BookingController@checkAvailability', 'api.availability');
$router->post('/api/review', 'TourController@submitReview', 'api.review');

// ─── Admin Routes ───
$router->get('/admin', 'AdminController@dashboard', 'admin.dashboard');
$router->get('/admin/tours', 'AdminController@tours', 'admin.tours');
$router->get('/admin/bookings', 'AdminController@bookings', 'admin.bookings');
$router->post('/admin/bookings/status', 'AdminController@updateBookingStatus', 'admin.bookings.status');
$router->get('/admin/users', 'AdminController@users', 'admin.users');
$router->get('/admin/reviews', 'AdminController@reviews', 'admin.reviews');
$router->post('/admin/reviews/approve', 'AdminController@approveReview', 'admin.reviews.approve');
$router->get('/admin/messages', 'AdminController@messages', 'admin.messages');
$router->get('/admin/settings', 'AdminController@settings', 'admin.settings');
