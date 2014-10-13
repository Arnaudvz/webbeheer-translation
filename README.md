webbeheer-translation
=====================

### Installation


```javascript
  	"repositories": [
		{
			"type": "vcs",
			"url":  "git@github.com:swisnl/webbeheer-translation.git"
		}
		
		"require": [
		  "swisnl/webbeheer-translation": "dev-master"
		]
		
```

### Configuration

Publish the config & migration and edit as necessary.

```bash
	php artisan publish:config swisnl/webbeheer-translation
	php artisan publish:migrations swisnl/webbeheer-translation
```
