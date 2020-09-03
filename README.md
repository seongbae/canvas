# Canvas

Canvas is an admin panel built with Laravel. It comes with admin dashboard for managing users, roles & permissions, media items, simple pages, and system logs. 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Installing

Create a new Laravel application:

```
laravel new blog
```

Update .env with database connection information.

Install Canvas via Composer:

```
composer require seongbae/canvas
```

Create a database, update .env and run migration:

```
php artisan migrate
```

Run the Canvas installer:

```
php artisan canvas:install
```

The installer runs the migrations, creates storage symlink, publishes package files, and seed the database with initial data.

At this point, visit your app URL and log in with:
 
 ```
Username: admin@admin.com
Password: password
 ```

Optionally, you can publish vendor files:

```
php artisan vendor:publish --provider "Seongbae\Canvas\CanvasServiceProvider"
```

## Generating CRUD 

Below command and syntax is taken from the Crudify project. 

Run `canvas:generate` for a new model:

    php artisan canvas:generate Model
    
This will generate:

- Controller
- Datatable
- Form Request
- Model
- Factory
- Migration
- Seeder
- View Files
- Navbar Link
- Routes

Don't forget to migrate after updating the new migration file.  

**Tip: use the `--force` in order to replace existing generated files e.g. `php artisan canvas:generate Model --force`**
**Tip: use the `--admin` to have a menu item created in the backend.**
 
## Built With

* [Laravel 7](https://www.laravel.com) - The web framework used
* [AdminLTE](https://adminlte.io/) - Admin Dashboard
* [Crudify](https://github.com/kejojedi/crudify) - CRUD generation

## Contributing

Please read [CONTRIBUTING](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/seongbae/canvas/tags). 

## Authors

* **Seong Bae** - [seong@lnidigital.com](seong@lnidigital.com)

## License

This project is licensed under the MIT License.