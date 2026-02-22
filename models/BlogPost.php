<?php
class BlogPost extends Model { protected string $table = 'blog_posts';
    public function published(int $page = 1): array { return $this->paginate($page, ITEMS_PER_PAGE, "status = 'published'", [], 'published_at DESC'); }
    public function recent(int $limit = 3): array { return $this->db->fetchAll("SELECT bp.*, bc.name as category_name FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id WHERE bp.status = 'published' ORDER BY bp.published_at DESC LIMIT {$limit}"); }
    public function withCategory(string $slug): ?object { return $this->db->fetch("SELECT bp.*, bc.name as category_name, u.first_name as author_first, u.last_name as author_last FROM blog_posts bp LEFT JOIN blog_categories bc ON bp.category_id = bc.id LEFT JOIN users u ON bp.author_id = u.id WHERE bp.slug = ?", [$slug]); }
    public function incrementViews(int $id): void { $this->db->query("UPDATE blog_posts SET views = views + 1 WHERE id = ?", [$id]); }
}
