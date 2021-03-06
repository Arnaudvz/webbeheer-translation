<?php

namespace Swisnl\WebbeheerTranslation;


class WebbeheerTranslationServiceProvider extends \Illuminate\Translation\TranslationServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('arnaudvz/webbeheer-translation');

        $this->registerLoader();
        $this->registerDatabaseLoader();
        $this->registerDatabaseSaver();

        $this->app->bindShared(
            'translator',
            function ($app) {
                $fileLoader = $app['translation.loader'];
                $databaseLoader = $app['translation.loader.database'];
				$databaseSaver = $app['translation.saver'];

                // When registering the translator component, we'll need to set the default
                // locale as well as the fallback locale. So, we'll grab the application
                // configuration so we can easily get both of these values from there.
                $locale = $app['config']['app.locale'];

                $trans = new WebbeheerTranslator($databaseLoader, $fileLoader, $databaseSaver, $locale);
                $trans->setFallback($app['config']['app.fallback_locale']);

                return $trans;
            }
        );
    }

    /**
     * Register a database loader
     *
     * @return void
     */
    protected function registerDatabaseLoader()
    {
        $this->app->bindShared(
            'translation.loader.database',
            function ($app) {
                return new DatabaseLoader(
                    $app['db'],
                    $app['config']['webbeheer-translation::schema']
                );
            }
        );
    }

	/**
	 * Register a database loader
	 *
	 * @return void
	 */
	protected function registerDatabaseSaver()
	{
		$this->app->bindShared(
			'translation.saver',
			function ($app) {
				return new DatabaseSaver(
					$app['db'],
					$app['config']['webbeheer-translation::schema']
				);
			}
		);
	}

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('translator', 'translation.loader', 'translation.loader.database', 'translation.saver');
    }
}
