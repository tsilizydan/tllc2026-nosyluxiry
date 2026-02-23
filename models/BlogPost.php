<?php
class BlogPost extends Model { protected string $table = 'blog_posts';

    public function published(int $page = 1): array
    {
        $perPage = ITEMS_PER_PAGE;
        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM blog_posts WHERE status = 'published'");
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $items = $this->db->fetchAll(
            "SELECT bp.*, bc.name as category_name,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             LEFT JOIN users u ON bp.author_id = u.id
             WHERE bp.status = 'published'
             ORDER BY bp.published_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );

        return compact('items', 'total', 'page', 'totalPages');
    }

    public function recent(int $limit = 3): array {
        return $this->db->fetchAll(
            "SELECT bp.*, bc.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as author_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             LEFT JOIN users u ON bp.author_id = u.id
             WHERE bp.status = 'published'
             ORDER BY bp.published_at DESC LIMIT {$limit}"
        );
    }

    public function withCategory(string $slug): ?object {
        return $this->db->fetch(
            "SELECT bp.*, bc.name as category_name, CONCAT(u.first_name, ' ', u.last_name) as author_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.id
             LEFT JOIN users u ON bp.author_id = u.id
             WHERE bp.slug = ?", [$slug]
        );
    }

    public function incrementViews(int $id): void { $this->db->query("UPDATE blog_posts SET views = views + 1 WHERE id = ?", [$id]); }
}
