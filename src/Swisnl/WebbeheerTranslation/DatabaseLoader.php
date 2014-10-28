<?php

namespace Swisnl\WebbeheerTranslation;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Translation\LoaderInterface;

class DatabaseLoader implements LoaderInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var string
     */
    private $groups;

    /**
     * @var string
     */
    private $translations;

    /**
     * @var Builder
     */
    private $query;

    /**
     * @param DatabaseManager $db
     * @param string          $schema Schema defined in the configuration
     */
    public function __construct(DatabaseManager $db, $schema)
    {
		$this->db           = $db->connection();
        $this->schema       = $schema;
    }

    /**
     * Load the messages for the given locale and group.
     *
     * @param  string $locale
     * @param  string $group
     * @param  string $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        try {
			$query = $this->db->table($this->schema['table'])
				->where($this->schema['fields']['locale'], $locale)
				->where($this->schema['fields']['group'], $group)
				->where($this->schema['fields']['content'], '!=', "");

			return $query->lists($this->schema['fields']['content'], $this->schema['fields']['item']);
		} catch(QueryException $e) {
			return array();
		}
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        // Hints not needed for database loader
    }
}