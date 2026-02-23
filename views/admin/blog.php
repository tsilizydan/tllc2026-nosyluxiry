<div class="admin-header">
    <h1><i class="fas fa-newspaper"></i> Blog Posts</h1>
    <a href="<?= url('/admin/blog/create') ?>" class="btn-admin btn-admin-gold"><i class="fas fa-plus"></i> New Post</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr><th>Image</th><th>Title</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#666;padding:2rem;">No blog posts yet.</td></tr>
                <?php else: foreach ($posts as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p->image): ?>
                                <img src="<?= upload_url($p->image) ?>" class="thumb" alt="">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#1a1a2e;border-radius:8px;display:flex;align-items:center;justify-content:center;"><i class="fas fa-image" style="color:#333;"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><strong style="color:#fff;"><?= e($p->title) ?></strong></td>
                        <td><?= e($p->author_name ?? 'Admin') ?></td>
                        <td><span class="status-badge status-<?= $p->status ?>"><?= $p->status ?></span></td>
                        <td style="white-space:nowrap;"><?= formatDate($p->created_at) ?></td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <a href="<?= url('/admin/blog/edit/' . $p->id) ?>" class="btn-admin btn-admin-sm btn-admin-outline"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="<?= url('/admin/blog/delete/' . $p->id) ?>" onsubmit="return confirm('Delete this post?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-admin btn-admin-sm btn-admin-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
