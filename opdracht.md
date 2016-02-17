# Opdracht
- Applicatie opstarten
- `Application` omtoveren


### Applicatie opstarten

- Elke stap is niet volledig. Aanpassen waar je dat nodig acht.
- Deze methodiek gebruikt Laravel ook.
- [Laravel bootstrap classes](https://github.com/laravel/framework/tree/5.2/src/Illuminate/Foundation/Bootstrap)
- [Laravel console kernel](https://github.com/laravel/framework/blob/5.2/src/Illuminate/Foundation/Console/Kernel.php)
- [Laravel artisan voert kernel handle uit](https://github.com/laravel/laravel/blob/master/artisan)


**10** `Raphaelb\Foundation\Kernel` class
- class property aanmaken: `bootstrappers` (array)
- methods aanmaken: `handle()` 

**20** `Raphaelb\Foundation\Bootstrap` directory/namespace
- Hier komen de bootstrappers classes

**25** `Raphaelb\Foundation\Bootstrap\BootstrapInterface` interface
- voeg de methode `bootstrap(Application $app)` toe 

**30** `Raphaelb\Foundation\Bootstrap\LoadConfiguration implements BootstrapInterface` class
- Deze gaat de configuratie laden. Wat momenteel in jouw `Application` gebeurt moet dus eruit

**40** `Raphaelb\Foundation\Bootstrap\RegisterProviders implements BootstrapInterface` class
- Deze gaat je providers laden

**45** `Raphaelb\Foundation\Kernel` class
- Voeg alle classes in `Raphaelb\Foundation\Bootstrap` toe aan de `bootstrappers` array
- Zorg dat `handle()` methode door alle `bootstrappers` loopt
  - elke `bootstrapper` is een class name
  - elke `bootstrapper` instancieer je via je `Application` make('bootstrapper')
  - elke bootstrapper 


**50** `bin/raaftisan` aanpassen
Voorbeeld:
```php
# new Application instance
$app = new Application;

# new Kernel instance
$kernel = $app->make('Raphaelb\Foundation\Kernel')

# uitvoeren handle(). zorgt er dus voor dat al je Bootstrap classes uitgevoerd worden
$kernel->handle(); 

# artisan runnen
$artisan = $app->make('artisan');
$artisan->add(New ConfigCommand());
$artisan->run();
```