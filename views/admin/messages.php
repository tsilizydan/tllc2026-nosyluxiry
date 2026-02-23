<div class="admin-header">
    <h1><i class="fas fa-envelope"></i> Messages</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th></th><th>From</th><th>Email</th><th>Subject</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#666;padding:2rem;">No messages yet.</td></tr>
                <?php else: foreach ($messages as $m): ?>
                <tr x-data="{ expanded: false }" style="<?= empty($m->is_read) ? 'background:rgba(212,175,55,0.03);' : '' ?>">
                    <td>
                        <?php if (empty($m->is_read)): ?>
                            <span style="display:inline-block;width:8px;height:8px;background:#d4af37;border-radius:50%;"></span>
                        <?php endif; ?>
                    </td>
                    <td><strong style="color:<?= empty($m->is_read) ? '#fff' : '#888' ?>;"><?= e($m->name) ?></strong></td>
                    <td><a href="mailto:<?= e($m->email) ?>" style="color:#d4af37;font-size:0.8rem;"><?= e($m->email) ?></a></td>
                    <td>
                        <span @click="expanded = !expanded" style="cursor:pointer;color:#ccc;"><?= e($m->subject ?? 'No subject') ?></span>
                    </td>
                    <td style="white-space:nowrap;"><?= formatDate($m->created_at) ?></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button @click="expanded = !expanded" class="btn-admin btn-admin-sm btn-admin-outline"><i class="fas fa-eye"></i></button>
                            <?php if (empty($m->is_read)): ?>
                                <form method="POST" action="<?= url('/admin/messages/read/' . $m->id) ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-success"><i class="fas fa-check"></i></button>
                                </form>
                            <?php endif; ?>
                            <form method="POST" action="<?= url('/admin/messages/delete/' . $m->id) ?>" onsubmit="return confirm('Delete this message?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr x-show="expanded" x-transition style="background:rgba(212,175,55,0.02);">
                    <td colspan="6" style="padding:1rem 1.5rem;">
                        <p style="color:#ccc;font-size:0.85rem;line-height:1.7;white-space:pre-wrap;"><?= e($m->message) ?></p>
                        <?php if ($m->phone ?? false): ?>
                            <p style="margin-top:0.75rem;color:#888;font-size:0.8rem;"><i class="fas fa-phone"></i> <?= e($m->phone) ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
