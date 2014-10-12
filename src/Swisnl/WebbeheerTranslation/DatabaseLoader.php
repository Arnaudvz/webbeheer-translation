<?php

namespace Swisnl\WebbeheerTranslation;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
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
     * @param string          $groups
     * @param string          $translations
     */
    public function __construct(DatabaseManager $db, $groups, $translations)
    {
        $this->db           = $db->connection();
        $this->groups       = $groups;
        $this->translations = $translations;
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
        $query = $this->db->table($this->translations)
            ->where('lang', $locale)
            ->where('group', $group);

        return $query->lists('value', 'key');
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