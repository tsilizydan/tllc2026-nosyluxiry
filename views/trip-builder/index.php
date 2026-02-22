<!-- Trip Builder Page -->
<div class="page-header">
    <div class="container">
        <p class="subtitle"><?= __('trip_builder.title') ?></p>
        <h1><?= __('trip_builder.subtitle') ?></h1>
        <div class="breadcrumb"><a href="<?= url('/') ?>"><?= __('nav.home') ?></a><span>/</span><span><?= __('nav.trip_builder') ?></span></div>
    </div>
</div>

<section class="section section-darker" x-data="tripBuilder()">
    <div class="container container-narrow" style="max-width:800px;">
        <!-- Progress Steps -->
        <div class="booking-steps" style="margin-bottom:var(--space-10);">
            <div class="booking-step" :class="{ 'active': step === 1, 'completed': step > 1 }">
                <span class="step-number">1</span><span>Destinations</span>
            </div>
            <div class="booking-step" :class="{ 'active': step === 2, 'completed': step > 2 }">
                <span class="step-number">2</span><span>Preferences</span>
            </div>
            <div class="booking-step" :class="{ 'active': step === 3, 'completed': step > 3 }">
                <span class="step-number">3</span><span>Interests</span>
            </div>
            <div class="booking-step" :class="{ 'active': step === 4 }">
                <span class="step-number">4</span><span>Submit</span>
            </div>
        </div>

        <form action="<?= url('/trip-builder') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Step 1: Destinations -->
            <div x-show="step === 1" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                <h3 style="margin-bottom:var(--space-6);">Where do you want to <span class="text-gold">explore?</span></h3>
                <div class="grid grid-3" style="gap:var(--space-4);">
                    <?php
                    $dests = ['Nosy Be', 'Tsingy de Bemaraha', 'Isalo National Park', 'Andasibe-Mantadia', 'Avenue of Baobabs', 'Île Sainte-Marie', 'Antananarivo', 'Ranomafana', 'Morondava'];
                    foreach ($dests as $d): ?>
                    <div @click="toggleDestination('<?= $d ?>')"
                         :class="{ 'active': formData.destinations.includes('<?= $d ?>') }"
                         class="filter-btn" style="cursor:pointer;text-align:center;user-select:none;">
                        <input type="checkbox" name="destinations[]" value="<?= $d ?>" style="display:none;"
                               :checked="formData.destinations.includes('<?= $d ?>')">
                        <span><?= $d ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Validation error -->
                <p x-show="errors.destinations" x-text="errors.destinations"
                   style="color:var(--color-coral);font-size:var(--text-sm);margin-top:var(--space-4);text-align:center;"></p>
            </div>

            <!-- Step 2: Preferences -->
            <div x-show="step === 2" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                <h3 style="margin-bottom:var(--space-6);">Tell us your <span class="text-gold">preferences</span></h3>
                <div class="grid grid-2" style="gap:var(--space-4);">
                    <div class="form-group">
                        <label class="form-label">Travel Dates</label>
                        <input type="date" name="travel_dates" class="form-control" x-model="formData.travelDates" min="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                        <p x-show="errors.travelDates" x-text="errors.travelDates" style="color:var(--color-coral);font-size:var(--text-xs);margin-top:var(--space-1);"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (days)</label>
                        <input type="number" name="duration" class="form-control" x-model="formData.duration" min="3" max="30">
                        <p x-show="errors.duration" x-text="errors.duration" style="color:var(--color-coral);font-size:var(--text-xs);margin-top:var(--space-1);"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Group Size</label>
                        <input type="number" name="group_size" class="form-control" x-model="formData.groupSize" min="1" max="20">
                        <p x-show="errors.groupSize" x-text="errors.groupSize" style="color:var(--color-coral);font-size:var(--text-xs);margin-top:var(--space-1);"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Budget Range</label>
                        <select name="budget_range" class="form-control" x-model="formData.budgetRange">
                            <option value="budget">Budget ($100-150/day/person)</option>
                            <option value="mid">Mid-range ($150-300/day/person)</option>
                            <option value="luxury">Luxury ($300-500/day/person)</option>
                            <option value="ultra">Ultra-Luxury ($500+/day/person)</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Accommodation Type</label>
                        <select name="accommodation_type" class="form-control" x-model="formData.accommodationType">
                            <option value="luxury">Luxury Lodges & Boutique Hotels</option>
                            <option value="eco">Eco-Lodges & Tented Camps</option>
                            <option value="mixed">Mix of Both</option>
                        </select>
                    </div>
                </div>
                <p style="margin-top:var(--space-6);padding:var(--space-4);background:var(--color-gold-muted);border-radius:var(--radius-md);text-align:center;color:var(--color-gold);">
                    <i class="fas fa-calculator"></i> Estimated: <strong>€<span x-text="estimatedPrice.toLocaleString()"></span></strong> total
                </p>
            </div>

            <!-- Step 3: Interests -->
            <div x-show="step === 3" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                <h3 style="margin-bottom:var(--space-6);">What are your <span class="text-gold">interests?</span></h3>
                <div class="grid grid-3" style="gap:var(--space-3);">
                    <?php
                    $interests = ['Wildlife & Lemurs', 'Diving & Snorkeling', 'Hiking & Trekking', 'Cultural Immersion', 'Photography', 'Relaxation & Spa', 'Bird Watching', 'Water Sports', 'Culinary Experiences', 'Night Photography', 'Volunteering', 'Whale Watching'];
                    foreach ($interests as $interest): ?>
                    <div @click="toggleInterest('<?= $interest ?>')"
                         :class="{ 'active': formData.interests.includes('<?= $interest ?>') }"
                         class="filter-btn" style="cursor:pointer;text-align:center;user-select:none;">
                        <input type="checkbox" name="interests[]" value="<?= $interest ?>" style="display:none;">
                        <span><?= $interest ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Validation error -->
                <p x-show="errors.interests" x-text="errors.interests"
                   style="color:var(--color-coral);font-size:var(--text-sm);margin-top:var(--space-4);text-align:center;"></p>
            </div>

            <!-- Step 4: Contact Details -->
            <div x-show="step === 4" style="background:var(--color-dark-card);border:1px solid var(--color-dark-border);border-radius:var(--radius-lg);padding:var(--space-8);">
                <h3 style="margin-bottom:var(--space-6);">Almost there! Your <span class="text-gold">details</span></h3>
                <div class="grid grid-2" style="gap:var(--space-4);">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" x-model="formData.name" required>
                        <p x-show="errors.name" x-text="errors.name" style="color:var(--color-coral);font-size:var(--text-xs);margin-top:var(--space-1);"></p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" x-model="formData.email" required>
                        <p x-show="errors.email" x-text="errors.email" style="color:var(--color-coral);font-size:var(--text-xs);margin-top:var(--space-1);"></p>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Phone (with country code)</label>
                        <input type="tel" name="phone" class="form-control" x-model="formData.phone">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Special Requests</label>
                        <textarea name="special_requests" class="form-control" rows="4" x-model="formData.specialRequests" placeholder="Dietary requirements, accessibility needs, special celebrations..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:var(--space-6);flex-wrap:wrap;gap:var(--space-3);">
                <button type="button" @click="prevStep()" x-show="step > 1" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Previous
                </button>
                <div></div>
                <button type="button" @click="nextStep()" x-show="step < 4"
                        class="btn btn-primary"
                        :class="{ 'btn-disabled': !canProceed }"
                        :style="!canProceed ? 'opacity:0.5;cursor:not-allowed;' : ''">
                    Next <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" x-show="step === 4" class="btn btn-primary btn-lg"
                        :disabled="!canProceed"
                        :style="!canProceed ? 'opacity:0.5;cursor:not-allowed;' : ''">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</section>
