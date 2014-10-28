<?php

namespace Swisnl\WebbeheerTranslation;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseManager;


class DatabaseSaver
{
    /**
     * @var Connection
     */
    private $db;

	/**
	 * @var array
	 */
	private $schema;

	/**
	 * @var bool
	 */
	static $enabled = true;


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
     * Save the message for the given locale and group.
     *
     * @param  string $locale
     * @param  string $group
     * @param  string $item
     * @return bool
     */
    public function save($locale, $group, $item)
    {

		if(!self::$enabled) {
			return false;
		}

		try{
			return $this->db->table($this->schema['table'])->insert([
				$this->schema['fields']['locale'] => $locale,
				$this->schema['fields']['group'] => $group,
				$this->schema['fields']['item'] => $item,
			]);
		} catch(\Exception $e) {
			return false;
		}
    }

	/**
	 * disable the saver, probably for your testing purposes
	 */
	public static function disableSaver() {
		self::$enabled = false;
	}

	/**
	 * enable the saver, probably for your testing purposes
	 */
	public static function enableSaver() {
		self::$enabled = true;
	}
}