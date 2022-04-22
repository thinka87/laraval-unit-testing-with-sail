## About this project

This project is made to test your knowledge of the laravel framework. It doesn't follow every best practices in order to allow fast and easy configuration for the person passing the tests.

## Requirements

Passing all the tests located in `test/Feature/` folder, using the command:
```zsh
sail artisan test --testsuite=Feature
```


To assist you passing the tests, we have placed `TODO XX: name_of_the_test_function`. For example:
```php
    public function test_home_screen_shows_welcome()
    {
        // ...
        $response->assertStatus(200);
        
        // ...
    }
```

Will be associated to the comment in `web.php`: 
```
// TODO 1: test_home_screen_shows_welcome
// point the main "/" URL to the HomeController method "index"
```
Most of the time this is a one liner is required, but not always. 

In some cases, __too much code is present and then it needs to be removed to get the test to pass__.

To submit the test for correction, you can submit your answers back in the way you find the most appropriate.

## Recommendations

We encourage you to use sail as this would require minimal configuration for you if you have installed docker.

### Encouraged installation process

1. Pull this repo
2. Install and start Docker Desktop https://www.docker.com/get-started in order to allow sail execution https://laravel.com/docs/8.x/sail No need to "install" sail, all the necessary config files are already present in this repo
3. Use `.env.example` as a template for your `.env`
4. Run `composer install` if you have composer locally, otherwise you can use docker to install dependencies https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects
5. Start environment with `./vendor/bin/sail up`. 
  * If it fails, make sure that port `:80` is not used by another process, if yes, we recommend that you turn it off:
    * `sudo /etc/init.d/apache2 stop`
    * `sudo nginx -s stop` 
  * If you ran this command without an `.env` or you can't connect to mysql run `./vendor/bin/sail down --rmi all -v` to delete misconfigured containers.
4. Generate key using `./vendor/bin/sail artisan key:generate`
5. Run tests using 
```
./vendor/bin/sail artisan test --testsuite=Feature` or `sail artisan test --testsuite=Feature --stop-on-failure
```
Or if you prefer to run one specific test 
```
sail artisan test --testsuite=Feature --filter RouteTest::test_home_screen_shows_welcome
```



