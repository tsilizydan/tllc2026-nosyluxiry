<?php
/**
 * Setting — Site configuration key-value store
 */
class Setting extends Model
{
    protected string $table = 'settings';

    /**
     * Get a setting value by key
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $row = $this->first("setting_key = ?", [$key]);
        return $row ? $row->setting_value : $default;
    }

    /**
     * Set a setting value (insert or update)
     */
    public function set(string $key, ?string $value): void
    {
        $existing = $this->first("setting_key = ?", [$key]);
        if ($existing) {
            $this->db->update($this->table, ['setting_value' => $value], 'setting_key = ?', [$key]);
        } else {
            $this->db->insert($this->table, [
                'setting_key' => $key,
                'setting_value' => $value,
            ]);
        }
    }

    /**
     * Get all settings in a group
     */
    public function getGroup(string $group): array
    {
        return $this->where("setting_group = ?", [$group], 'setting_key ASC');
    }

    /**
     * Save an array of key => value pairs for a group
     */
    public function saveGroup(string $group, array $data): void
    {
        foreach ($data as $key => $value) {
            $existing = $this->first("setting_key = ?", [$key]);
            if ($existing) {
                $this->db->update($this->table, [
                    'setting_value' => $value,
                    'setting_group' => $group,
                ], 'setting_key = ?', [$key]);
            } else {
                $this->db->insert($this->table, [
                    'setting_key' => $key,
                    'setting_value' => $value,
                    'setting_group' => $group,
                ]);
            }
        }
    }

    /**
     * Get all settings as key => value array
     */
    public function allAsArray(): array
    {
        $rows = $this->all('setting_key ASC');
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    /**
     * Get all settings grouped by setting_group
     */
    public function allGrouped(): array
    {
        $rows = $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY setting_group, setting_key");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row->setting_group][$row->setting_key] = $row->setting_value;
        }
        return $grouped;
    }
}
