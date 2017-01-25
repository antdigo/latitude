<?php
declare(strict_types=1);

namespace Latitude\QueryBuilder;

class InsertQuery implements Statement
{
    use Traits\CanCreatePlaceholders;
    use Traits\CanEscapeIdentifiers;

    /**
     * Create a new insert query.
     */
    public static function make(string $table, array $map): InsertQuery
    {
        $query = new static();
        $query->table($table);
        if ($map) {
            $query->map($map);
        }
        return $query;
    }

    /**
     * Set the table to insert into.
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set the columns and values to insert.
     */
    public function map(array $map): self
    {
        $this->columns = \array_keys($map);
        $this->params = \array_values($map);
        return $this;
    }

    // Statement
    public function sql(): string
    {
        return \sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->escapeIdentifier($this->table),
            $this->escapeIdentifiers($this->columns),
            $this->createPlaceholders(\count($this->params))
        );
    }

    // Statement
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $params = [];
}