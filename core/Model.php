<?php
/**
 * Model — Base model class
 */
class Model
{
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find by primary key
     */
    public function find(int $id): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * Find by slug
     */
    public function findBySlug(string $slug): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE slug = ?",
            [$slug]
        );
    }

    /**
     * Get all rows
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} ORDER BY {$orderBy}"
        );
    }

    /**
     * Get rows with conditions
     */
    public function where(string $where, array $params = [], string $orderBy = 'id DESC'): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy}",
            $params
        );
    }

    /**
     * Get first row matching conditions
     */
    public function first(string $where, array $params = []): ?object
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1",
            $params
        );
    }

    /**
     * Paginated query
     */
    public function paginate(int $page = 1, int $perPage = ITEMS_PER_PAGE, string $where = '1', array $params = [], string $orderBy = 'id DESC'): array
    {
        $total = $this->db->count($this->table, $where, $params);
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $items = $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    /**
     * Create a new record
     */
    public function create(array $data): int
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update a record by ID
     */
    public function update(int $id, array $data): int
    {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = ?", [$id]);
    }

    /**
     * Delete a record by ID
     */
    public function delete(int $id): int
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    /**
     * Count records
     */
    public function count(string $where = '1', array $params = []): int
    {
        return $this->db->count($this->table, $where, $params);
    }

    /**
     * Generate URL-friendly slug
     */
    public function generateSlug(string $text, ?int $excludeId = null): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
        $original = $slug;
        $counter = 1;

        while (true) {
            $where = "slug = ?";
            $params = [$slug];
            if ($excludeId) {
                $where .= " AND {$this->primaryKey} != ?";
                $params[] = $excludeId;
            }
            $existing = $this->db->count($this->table, $where, $params);
            if ($existing === 0) break;
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}
