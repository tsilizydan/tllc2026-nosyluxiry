<!-- Admin Messages -->
<div class="admin-header">
    <h1>Contact Messages</h1>
</div>

<div class="table-container">
    <?php if (!empty($messages)): ?>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th></tr></thead>
        <tbody>
            <?php foreach ($messages as $m): ?>
            <tr>
                <td><strong style="color:var(--color-white);"><?= e($m->name) ?></strong></td>
                <td><a href="mailto:<?= e($m->email) ?>"><?= e($m->email) ?></a></td>
                <td><?= e($m->subject ?? '-') ?></td>
                <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($m->message) ?></td>
                <td style="white-space:nowrap;"><?= timeAgo($m->created_at) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding:var(--space-10);text-align:center;color:var(--color-gray-400);">No messages.</div>
    <?php endif; ?>
</div>
