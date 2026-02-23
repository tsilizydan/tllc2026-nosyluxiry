<!-- Booking Checkout -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('booking.title') ?></p>
        <h1>Book: <?= e($tour->name) ?></h1>
        <div class="breadcrumb">
            <a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span>
            <a href="<?= url('/tours/' . $tour->slug) ?>"><?= e($tour->name) ?></a><span>/</span>
            <span><?= __('booking.title') ?></span>
        </div>
    </div>
</div>

<section class="section section-darker">
    <div class="container" style="max-width:900px;" x-data="bookingWizard()" data-price="<?= $tour->sale_price ?? $tour->price ?>">
        <!-- Steps -->
        <div class="booking-steps" style="margin-bottom:var(--space-10);">
            <div class="booking-step" :class="{ 'active': step === 1, 'completed': step > 1 }"><span class="step-number">1</span><span><?= __('booking.step1') ?></span></div>
            <div class="booking-step" :class="{ 'active': step === 2, 'completed': step > 2 }"><span class="step-number">2</span><span><?= __('booking.step2') ?></span></div>
            <div class="booking-step" :class="{ 'active': step === 3 }"><span class="step-number">3</span><span><?= __('booking.step3') ?></span></div>
        </div>

        <form action="<?= url('/booking/' . $tour->id) ?>" method="POST">
            <?= csrf_field() ?>

            <div style="display:grid;grid-template-columns:1.5fr 1fr;gap:var(--space-8);">
                <!-- Form Steps -->
                <div>
                    <!-- Step 1: Travel Details -->
                    <div x-show="step === 1" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                        <h3 style="margin-bottom:var(--space-6);">Travel Details</h3>
                        <div class="form-group">
                            <label class="form-label"><?= __('booking.date') ?></label>
                            <input type="date" name="travel_date" class="form-control" x-model="formData.date" required min="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('booking.guests') ?></label>
                            <select name="guests" class="form-control" x-model="formData.guests">
                                <?php for ($i = 1; $i <= ($tour->group_size_max ?? 12); $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?> guest<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Special Requests</label>
                            <textarea name="special_requests" class="form-control" rows="3" x-model="formData.specialRequests" placeholder="Dietary requirements, accessibility needs..."></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Guest Info -->
                    <div x-show="step === 2" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                        <h3 style="margin-bottom:var(--space-6);">Your Information</h3>
                        <div class="form-group">
                            <label class="form-label"><?= __('auth.first_name') ?> & <?= __('auth.last_name') ?></label>
                            <input type="text" name="name" class="form-control" x-model="formData.name" required value="<?= Auth::check() ? e(Session::get('user_name', '')) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('auth.email') ?></label>
                            <input type="email" name="email" class="form-control" x-model="formData.email" required value="<?= Auth::check() ? e(Auth::user()->email ?? '') : '' ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= __('auth.phone') ?></label>
                            <input type="tel" name="phone" class="form-control" x-model="formData.phone">
                        </div>
                    </div>

                    <!-- Step 3: Payment -->
                    <div x-show="step === 3" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                        <h3 style="margin-bottom:var(--space-6);">Payment Method</h3>
                        <div style="display:flex;flex-direction:column;gap:var(--space-3);">
                            <?php if (!empty(STRIPE_SECRET_KEY)): ?>
                            <label style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border:2px solid var(--color-dark-border);border-radius:var(--radius-md);cursor:pointer;transition:border-color 0.2s;" @click="formData.paymentMethod = 'stripe'" :style="formData.paymentMethod === 'stripe' ? 'border-color:var(--color-gold);' : ''">
                                <input type="radio" name="payment_method" value="stripe" x-model="formData.paymentMethod">
                                <i class="fab fa-cc-stripe" style="font-size:1.5rem;color:#6772e5;"></i>
                                <div>
                                    <strong style="color:var(--color-white);">Credit / Debit Card</strong>
                                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);">Secure payment via Stripe</div>
                                </div>
                            </label>
                            <?php endif; ?>

                            <?php if (!empty(PAYPAL_CLIENT_ID)): ?>
                            <label style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border:2px solid var(--color-dark-border);border-radius:var(--radius-md);cursor:pointer;transition:border-color 0.2s;" @click="formData.paymentMethod = 'paypal'" :style="formData.paymentMethod === 'paypal' ? 'border-color:var(--color-gold);' : ''">
                                <input type="radio" name="payment_method" value="paypal" x-model="formData.paymentMethod">
                                <i class="fab fa-paypal" style="font-size:1.5rem;color:#003087;"></i>
                                <div>
                                    <strong style="color:var(--color-white);">PayPal</strong>
                                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);">Pay with your PayPal account</div>
                                </div>
                            </label>
                            <?php endif; ?>

                            <label style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border:2px solid var(--color-dark-border);border-radius:var(--radius-md);cursor:pointer;transition:border-color 0.2s;" @click="formData.paymentMethod = 'bank_transfer'" :style="formData.paymentMethod === 'bank_transfer' ? 'border-color:var(--color-gold);' : ''">
                                <input type="radio" name="payment_method" value="bank_transfer" x-model="formData.paymentMethod" <?= empty(STRIPE_SECRET_KEY) && empty(PAYPAL_CLIENT_ID) ? 'checked' : '' ?>>
                                <i class="fas fa-building-columns text-gold" style="font-size:1.3rem;"></i>
                                <div>
                                    <strong style="color:var(--color-white);">Bank Transfer</strong>
                                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);">We'll send you bank details after booking</div>
                                </div>
                            </label>

                            <label style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-4);background:var(--color-dark-surface);border:2px solid var(--color-dark-border);border-radius:var(--radius-md);cursor:pointer;transition:border-color 0.2s;" @click="formData.paymentMethod = 'pay_on_arrival'" :style="formData.paymentMethod === 'pay_on_arrival' ? 'border-color:var(--color-gold);' : ''">
                                <input type="radio" name="payment_method" value="pay_on_arrival" x-model="formData.paymentMethod">
                                <i class="fas fa-handshake text-gold" style="font-size:1.3rem;"></i>
                                <div>
                                    <strong style="color:var(--color-white);">Pay on Arrival</strong>
                                    <div style="font-size:var(--text-xs);color:var(--color-gray-500);"><?= PAYMENT_DEPOSIT_PERCENT ?>% deposit required to confirm</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div style="display:flex;justify-content:space-between;margin-top:var(--space-6);">
                        <button type="button" @click="prevStep()" x-show="step > 1" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</button>
                        <div></div>
                        <button type="button" @click="nextStep()" x-show="step < 3" class="btn btn-primary">Continue <i class="fas fa-arrow-right"></i></button>
                        <button type="submit" x-show="step === 3" class="btn btn-primary btn-lg"><i class="fas fa-check"></i> Confirm Booking</button>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div style="position:sticky;top:100px;">
                    <div style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-6);">
                        <h4 style="margin-bottom:var(--space-4);">Booking Summary</h4>
                        <div style="display:flex;gap:var(--space-4);margin-bottom:var(--space-4);">
                            <img src="<?= !empty($tour->image) ? upload_url($tour->image) : 'https://images.unsplash.com/photo-1504893524553-b855bce32c67?w=200&q=80' ?>" style="width:80px;height:60px;object-fit:cover;border-radius:var(--radius-md);">
                            <div>
                                <strong style="display:block;color:var(--color-white);font-size:var(--text-sm);"><?= e($tour->name) ?></strong>
                                <span style="font-size:var(--text-xs);color:var(--color-gray-400);"><?= $tour->duration_days ?> days</span>
                            </div>
                        </div>
                        <div style="border-top:1px solid var(--color-dark-border);padding-top:var(--space-4);">
                            <div style="display:flex;justify-content:space-between;font-size:var(--text-sm);color:var(--color-gray-300);margin-bottom:var(--space-2);">
                                <span>Price × <span x-text="formData.guests"></span> guest(s)</span>
                                <span x-text="'€' + totalPrice"></span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-weight:700;color:var(--color-gold);font-size:var(--text-xl);margin-top:var(--space-3);border-top:1px solid var(--color-dark-border);padding-top:var(--space-3);">
                                <span>Total</span>
                                <span x-text="'€' + totalPrice"></span>
                            </div>
                        </div>
                        <p style="margin-top:var(--space-4);font-size:var(--text-xs);color:var(--color-gray-500);"><i class="fas fa-shield-halved text-gold"></i> Free cancellation up to 30 days before</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
