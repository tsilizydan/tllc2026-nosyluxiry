<div class="admin-header">
    <h1><i class="fas fa-ticket"></i> Booking #<?= e($booking->reference) ?></h1>
    <a href="<?= url('/admin/bookings') ?>" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    <!-- Main Info -->
    <div>
        <div class="admin-card">
            <h3>Guest Information</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div><span style="color:#666;font-size:0.75rem;display:block;">Name</span><strong style="color:#fff;"><?= e($booking->guest_name) ?></strong></div>
                <div><span style="color:#666;font-size:0.75rem;display:block;">Email</span><a href="mailto:<?= e($booking->guest_email) ?>" style="color:#d4af37;"><?= e($booking->guest_email) ?></a></div>
                <div><span style="color:#666;font-size:0.75rem;display:block;">Phone</span><?= e($booking->guest_phone ?? '—') ?></div>
                <div><span style="color:#666;font-size:0.75rem;display:block;">Guests</span><?= e($booking->adults ?? 0) ?> adults, <?= e($booking->children ?? 0) ?> children</div>
            </div>
            <?php if ($booking->special_requests): ?>
                <div style="margin-top:1rem;"><span style="color:#666;font-size:0.75rem;display:block;">Special Requests</span><p style="color:#ccc;font-size:0.85rem;"><?= e($booking->special_requests) ?></p></div>
            <?php endif; ?>
        </div>

        <div class="admin-card">
            <h3>Tour Details</h3>
            <div style="display:flex;gap:1rem;align-items:center;">
                <?php if ($booking->tour_image): ?>
                    <img src="<?= upload_url($booking->tour_image) ?>" style="width:80px;height:80px;object-fit:cover;border-radius:10px;">
                <?php endif; ?>
                <div>
                    <strong style="color:#fff;font-size:1rem;"><?= e($booking->tour_name) ?></strong>
                    <div style="color:#666;font-size:0.8rem;margin-top:0.25rem;">
                        <i class="fas fa-calendar"></i> <?= formatDate($booking->booking_date) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <div class="admin-card">
            <h3>Payment History</h3>
            <?php if (empty($payments)): ?>
                <p style="color:#666;font-size:0.85rem;">No payments recorded.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead><tr><th>Gateway</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                            <tr>
                                <td style="text-transform:capitalize;"><?= e(str_replace('_', ' ', $p->gateway)) ?></td>
                                <td style="font-weight:600;color:#d4af37;"><?= formatPrice($p->amount) ?></td>
                                <td><span class="status-badge status-<?= $p->status ?>"><?= $p->status ?></span></td>
                                <td><?= formatDate($p->created_at) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <div class="admin-card">
            <h3>Booking Status</h3>
            <div style="text-align:center;margin:1rem 0;">
                <span class="status-badge status-<?= $booking->status ?>" style="font-size:0.85rem;padding:6px 16px;"><?= ucfirst($booking->status) ?></span>
            </div>
            <form method="POST" action="<?= url('/admin/bookings/status') ?>" class="admin-form">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $booking->id ?>">
                <div class="form-group">
                    <label>Change Status</label>
                    <select name="status">
                        <?php foreach (['pending','confirmed','paid','completed','cancelled','refunded'] as $s): ?>
                            <option value="<?= $s ?>" <?= $booking->status === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-admin btn-admin-gold" style="width:100%;justify-content:center;"><i class="fas fa-sync"></i> Update</button>
            </form>
        </div>

        <div class="admin-card">
            <h3>Summary</h3>
            <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <span style="color:#888;">Reference</span>
                <code style="color:#d4af37;"><?= e($booking->reference) ?></code>
            </div>
            <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <span style="color:#888;">Total</span>
                <strong style="color:#d4af37;font-size:1.1rem;"><?= formatPrice($booking->total_price) ?></strong>
            </div>
            <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.04);">
                <span style="color:#888;">Payment</span>
                <span class="status-badge status-<?= $booking->payment_status ?? 'pending' ?>"><?= ucfirst($booking->payment_status ?? 'pending') ?></span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:0.5rem 0;">
                <span style="color:#888;">Created</span>
                <span style="color:#ccc;"><?= formatDate($booking->created_at) ?></span>
            </div>
        </div>
    </div>
</div>
