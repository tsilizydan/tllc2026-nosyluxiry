<div class="admin-header">
    <h1><i class="fas fa-users"></i> Users</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#666;padding:2rem;">No users found.</td></tr>
                <?php else: foreach ($users as $u): ?>
                <tr>
                    <td>
                        <strong style="color:#fff;"><?= e($u->first_name . ' ' . $u->last_name) ?></strong>
                    </td>
                    <td><a href="mailto:<?= e($u->email) ?>" style="color:#d4af37;"><?= e($u->email) ?></a></td>
                    <td><span style="text-transform:capitalize;color:#888;"><?= e($u->role) ?></span></td>
                    <td><span class="status-badge status-<?= $u->status ?? 'active' ?>"><?= ucfirst($u->status ?? 'active') ?></span></td>
                    <td style="white-space:nowrap;"><?= formatDate($u->created_at) ?></td>
                    <td>
                        <?php if ($u->id !== Auth::id()): ?>
                        <form method="POST" action="<?= url('/admin/users/toggle/' . $u->id) ?>" onsubmit="return confirm('Change this user\'s status?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn-admin btn-admin-sm <?= ($u->status ?? 'active') === 'active' ? 'btn-admin-danger' : 'btn-admin-success' ?>">
                                <i class="fas fa-<?= ($u->status ?? 'active') === 'active' ? 'ban' : 'check' ?>"></i>
                                <?= ($u->status ?? 'active') === 'active' ? 'Ban' : 'Activate' ?>
                            </button>
                        </form>
                        <?php else: ?>
                            <span style="color:#555;font-size:0.75rem;">You</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
        <div class="admin-pagination">
            <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                <?php if ($i == $pagination['page']): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= url('/admin/users?page=' . $i) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
