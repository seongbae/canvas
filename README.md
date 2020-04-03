# Canvas

Canvas is an open source CMS starter kit built with Laravel. It provides a starting point for building an advanced website or your own content management system.

Canvas comes with admin dashboard for managing users, roles & permissions, media items, simple pages, and system logs. You can use Canvas to build simple websites or build more advanced websites. It also has support for building modules.

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

Run the Canvas installer:

```
php artisan canvas:install
```

The installer runs the migrations, creates storage symlink, publishes package files, and seed the database with initial data.

At this point, if you go to the site at http://blog_url, you will see the Canvas landing page. You can access the backend by going to /login and using admin@admin.com as login and password as password.

## Built With

* [Laravel 6](https://www.laravel.com) - The web framework used
* [AdminLTE](https://adminlte.io/) - Admin Dashboard

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Seong Bae** - [seong@lnidigital.com](seong@lnidigital.com)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details