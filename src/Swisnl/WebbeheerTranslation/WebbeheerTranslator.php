<?php

namespace Swisnl\WebbeheerTranslation;

use Illuminate\Translation\LoaderInterface;

class WebbeheerTranslator extends \Illuminate\Translation\Translator
{
    /**
     * @var LoaderInterface
     */
    protected $databaseLoader;

    /**
     * @var
     */
    protected $fileLoader;

    /**
     * @var array
     */
    protected $databaseLoaded = [];

    /**
     * @var array
     */
    protected $fileLoaded = [];

    /**
     * @param LoaderInterface $databaseLoader
     * @param LoaderInterface $fileLoader
     * @param                 $locale
     */
    public function __construct(LoaderInterface $databaseLoader, LoaderInterface $fileLoader, $locale)
    {
        $this->fileLoader     = $fileLoader;
        $this->databaseLoader = $databaseLoader;
        $this->locale         = $locale;
    }

    /**
     * @return LoaderInterface
     */
    public function getDatabaseLoader()
    {
        return $this->databaseLoader;
    }

    /**
     * @return mixed
     */
    public function getFileLoader()
    {
        return $this->fileLoader;
    }

    /**
     * Load the specified language group from the database.
     *
     * @param  string $namespace
     * @param  string $group
     * @param  string $locale
     * @return void
     */
    public function loadFromDatabase($namespace, $group, $locale)
    {
        if (isset($this->databaseLoaded[$namespace][$group][$locale])) {
            return;
        }

        $lines = $this->getDatabaseLoader()->load($locale, $group, $namespace);
        $this->setDatabaseLoaded($namespace, $group, $locale, $lines);

    }

    /**
     * Load the specified language group from the fallback loader.
     *
     * @param  string $namespace
     * @param  string $group
     * @param  string $locale
     * @return void
     */
    public function loadFromFallback($namespace, $group, $locale)
    {
        if (isset($this->fileLoaded[$namespace][$group][$locale])) {
            return;
        }

        $lines = $this->getFileLoader()->load($locale, $group, $namespace);
        $this->setFileLoaded($namespace, $group, $locale, $lines);
    }

    /**
     * @param $namespace
     * @param $group
     * @param $locale
     * @param $lines
     */
    public function setDatabaseLoaded($namespace, $group, $locale, $lines)
    {
        $this->loaded[$namespace][$group][$locale]         = $lines;
        $this->databaseLoaded[$namespace][$group][$locale] = true;
    }

    /**
     * @param $namespace
     * @param $group
     * @param $locale
     * @param $lines
     */
    public function setFileLoaded($namespace, $group, $locale, $lines)
    {
        $this->loaded[$namespace][$group][$locale]
            = array_merge($lines, $this->loaded[$namespace][$group][$locale]);
        $this->fileLoaded[$namespace][$group][$locale] = $lines;
    }

    /**
     * Get the translation for the given key.
     *
     * @param  string $key
     * @param  array  $replace
     * @param  string $locale
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null)
    {
        list($namespace, $group, $item) = $this->parseKey($key);

        // Here we will get the locale that should be used for the language line. If one
        // was not passed, we will use the default locales which was given to us when
        // the translator was instantiated. Then, we can load the lines and return.
        $line = $this->getLineFromDatabase($replace, $locale, $namespace, $group, $item);

        // If the line doesn't exist in the database we will use the fallback loader and add any missing keys
        if (!$line) {
            $line = $this->getLineFromFallback($replace, $locale, $namespace, $group, $item);
        }

        // If the line doesn't exist, we will return back the key which was requested as
        // that will be quick to spot in the UI if language keys are wrong or missing
        // from the application's language files. Otherwise we can return the line.
        if (!isset($line)) {
            return $key;
        }

        return $line;
    }

    /**
     * @param array $replace
     * @param       $locale
     * @param       $namespace
     * @param       $group
     * @param       $item
     * @return null|string
     */
    protected function getLineFromDatabase(array $replace, $locale, $namespace, $group, $item)
    {
        foreach ($this->parseLocale($locale) as $locale) {
            $this->loadFromDatabase($namespace, $group, $locale);
            $line = $this->getLine($namespace, $group, $locale, $item, $replace);

            if (!is_null($line)) {
                return $line;
            }
        }
    }

    /**
     * @param array $replace
     * @param       $locale
     * @param       $namespace
     * @param       $group
     * @param       $item
     * @return null|string
     */
    protected function getLineFromFallback(array $replace, $locale, $namespace, $group, $item)
    {
        foreach ($this->parseLocale($locale) as $locale) {
            $this->loadFromFallback($namespace, $group, $locale);
            $line = $this->getLine($namespace, $group, $locale, $item, $replace);

            if (!is_null($line)) {
                return $line;
            }
        }
    }

    /**
     * Add a new namespace to the file loader.
     *
     * @param  string $namespace
     * @param  string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->fileLoader->addNamespace($namespace, $hint);
    }
} 