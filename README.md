webbeheer-translation
=====================

### Installation

Add repository

```javascript
  	"repositories": [
		{
			"type": "vcs",
			"url":  "git@github.com:swisnl/webbeheer-translation.git"
		}
	]
		
```

Add dependency

```javscript
	"require": {
	  "swisnl/webbeheer-translation": "dev-master"
	}
```

### Configuration

Publish the config & migrations.

```bash
	php artisan publish:config swisnl/webbeheer-translation
	php artisan publish:migrations swisnl/webbeheer-translation
```

You can change the table schema by editing the published migration (or by creating your own) and updating the configuration file. Make sure to run the migration after you're done.

Example configuration file:

```php

<?php

return [
    /**
     * Specify the schema used by the translator
     */
    'schema' => [
        'table'  => 'translations',
        'fields' => [
            'locale'  => 'locale',
            'group'   => 'group',
            'label'   => 'label',
            'content' => 'content',
        ]
    ]
];

```
