<div class="admin-header">
    <h1><i class="fas fa-route"></i> Trip Requests</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Destinations</th><th>Budget</th><th>Travel Dates</th><th>Status</th><th>Submitted</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#666;padding:2rem;">No trip requests yet.</td></tr>
                <?php else: foreach ($requests as $r): ?>
                    <tr x-data="{ open: false }">
                        <td><strong style="color:#fff;"><?= e($r->name ?? '—') ?></strong></td>
                        <td><a href="mailto:<?= e($r->email ?? '') ?>" style="color:#d4af37;"><?= e($r->email ?? '—') ?></a></td>
                        <td style="text-transform:capitalize;">
                            <?php
                            $dests = $r->destinations ?? null;
                            if ($dests && is_string($dests)) {
                                $decoded = json_decode($dests, true);
                                echo e(is_array($decoded) ? implode(', ', $decoded) : $dests);
                            } else {
                                echo '—';
                            }
                            ?>
                        </td>
                        <td><?= !empty($r->budget_range) ? e($r->budget_range) : '—' ?></td>
                        <td style="white-space:nowrap;font-size:0.8rem;">
                            <?= !empty($r->travel_dates) ? e($r->travel_dates) : '—' ?>
                        </td>
                        <td><span class="status-badge status-<?= $r->status ?>"><?= ucfirst($r->status) ?></span></td>
                        <td style="white-space:nowrap;"><?= formatDate($r->created_at) ?></td>
                        <td>
                            <button @click="open = !open" class="btn-admin btn-admin-sm btn-admin-outline">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Expanded Details -->
                    <tr x-show="open" x-transition style="background:rgba(212,175,55,0.03);">
                        <td colspan="8" style="padding:1rem 1.5rem;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
                                <div>
                                    <strong style="color:#888;font-size:0.75rem;display:block;margin-bottom:0.5rem;">Request Details</strong>
                                    <?php if (!empty($r->duration_days)): ?>
                                        <p style="color:#ccc;font-size:0.85rem;"><i class="fas fa-clock" style="color:#d4af37;width:16px;"></i> <?= e($r->duration_days) ?> days</p>
                                    <?php endif; ?>
                                    <?php if (!empty($r->group_size)): ?>
                                        <p style="color:#ccc;font-size:0.85rem;"><i class="fas fa-users" style="color:#d4af37;width:16px;"></i> <?= e($r->group_size) ?> guests</p>
                                    <?php endif; ?>
                                    <?php if (!empty($r->accommodation_type)): ?>
                                        <p style="color:#ccc;font-size:0.85rem;"><i class="fas fa-bed" style="color:#d4af37;width:16px;"></i> <?= e($r->accommodation_type) ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($r->phone)): ?>
                                        <p style="color:#ccc;font-size:0.85rem;"><i class="fas fa-phone" style="color:#d4af37;width:16px;"></i> <?= e($r->phone) ?></p>
                                    <?php endif; ?>
                                    <?php
                                    $interests = $r->interests ?? null;
                                    if ($interests && is_string($interests)) {
                                        $interestArr = json_decode($interests, true);
                                        if (is_array($interestArr) && !empty($interestArr)):
                                    ?>
                                        <p style="color:#888;font-size:0.75rem;margin-top:0.5rem;">Interests:</p>
                                        <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                            <?php foreach ($interestArr as $interest): ?>
                                                <span style="background:rgba(212,175,55,0.1);color:#d4af37;padding:2px 8px;border-radius:4px;font-size:0.75rem;"><?= e($interest) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; } ?>
                                    <?php if (!empty($r->special_requests)): ?>
                                        <p style="color:#888;font-size:0.75rem;margin-top:0.75rem;">Special Requests:</p>
                                        <p style="color:#ccc;font-size:0.85rem;line-height:1.6;"><?= e($r->special_requests) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <form method="POST" action="<?= url('/admin/trip-requests/update/' . $r->id) ?>" class="admin-form">
                                        <?= csrf_field() ?>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status">
                                                <option value="new" <?= $r->status === 'new' ? 'selected' : '' ?>>New</option>
                                                <option value="contacted" <?= $r->status === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                                <option value="quoted" <?= $r->status === 'quoted' ? 'selected' : '' ?>>Quoted</option>
                                                <option value="booked" <?= ($r->status ?? '') === 'booked' ? 'selected' : '' ?>>Booked</option>
                                                <option value="closed" <?= $r->status === 'closed' ? 'selected' : '' ?>>Closed</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Admin Notes</label>
                                            <textarea name="admin_notes" rows="2"><?= e($r->admin_notes ?? '') ?></textarea>
                                        </div>
                                        <button type="submit" class="btn-admin btn-admin-sm btn-admin-gold"><i class="fas fa-save"></i> Update</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
