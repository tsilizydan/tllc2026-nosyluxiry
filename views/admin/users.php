<!-- Admin Users -->
<div class="admin-header">
    <h1>Users</h1>
</div>

<div class="table-container">
    <?php if (!empty($users)): ?>
    <table class="admin-table">
        <thead><tr><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Joined</th></tr></thead>
        <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:var(--space-3);">
                        <div style="width:32px;height:32px;border-radius:var(--radius-full);background:var(--color-gold-muted);display:flex;align-items:center;justify-content:center;color:var(--color-gold);font-weight:700;font-size:var(--text-sm);">
                            <?= strtoupper(substr($u->first_name ?? 'U', 0, 1)) ?>
                        </div>
                        <strong style="color:var(--color-white);"><?= e(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?></strong>
                    </div>
                </td>
                <td><a href="mailto:<?= e($u->email) ?>"><?= e($u->email) ?></a></td>
                <td><?= e($u->phone ?? '-') ?></td>
                <td><span class="badge <?= $u->role === 'admin' ? 'badge-warning' : 'badge-info' ?>"><?= ucfirst($u->role) ?></span></td>
                <td><span class="badge <?= $u->status === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= ucfirst($u->status) ?></span></td>
                <td style="white-space:nowrap;"><?= date('M d, Y', strtotime($u->created_at)) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No users.</div>
    <?php endif; ?>
</div>
