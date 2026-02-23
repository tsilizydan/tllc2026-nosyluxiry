<div class="admin-header">
    <h1><i class="fas fa-route"></i> Trip Requests</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Type</th><th>Budget</th><th>Travel Dates</th><th>Status</th><th>Submitted</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($requests)): ?>
                    <tr><td colspan="8" style="text-align:center;color:#666;padding:2rem;">No trip requests yet.</td></tr>
                <?php else: foreach ($requests as $r): ?>
                    <tr x-data="{ open: false }">
                        <td><strong style="color:#fff;"><?= e($r->name ?? '—') ?></strong></td>
                        <td><a href="mailto:<?= e($r->email ?? '') ?>" style="color:#d4af37;"><?= e($r->email ?? '—') ?></a></td>
                        <td style="text-transform:capitalize;"><?= e($r->trip_type ?? '—') ?></td>
                        <td><?= $r->budget ? formatPrice($r->budget) : '—' ?></td>
                        <td style="white-space:nowrap;font-size:0.8rem;">
                            <?= $r->start_date ? formatDate($r->start_date) : '—' ?>
                            <?php if ($r->end_date): ?> → <?= formatDate($r->end_date) ?><?php endif; ?>
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
                                    <strong style="color:#888;font-size:0.75rem;display:block;margin-bottom:0.25rem;">Details</strong>
                                    <p style="color:#ccc;font-size:0.85rem;line-height:1.6;"><?= e($r->details ?? 'No additional details.') ?></p>
                                    <?php if ($r->guests): ?><p style="color:#888;font-size:0.8rem;"><i class="fas fa-users"></i> <?= e($r->guests) ?> guests</p><?php endif; ?>
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
                                                <option value="confirmed" <?= $r->status === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
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
