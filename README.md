# Servora

Servora is a local services marketplace built with Laravel and Vite.

## Run locally

Install PHP 8.2+, Composer, Node.js and GNU Make. From the project directory, run:

```sh
make
```

Open <http://127.0.0.1:8000>. The command creates `.env` and a local SQLite database when needed, installs missing dependencies, builds assets, applies SQLite migrations, and starts Laravel. Press Ctrl+C to stop the server. On Windows, `mingw32-make` works too. To use another port, set the `PORT` environment variable before running `make`.

Run `make setup` to prepare the project without starting the server, or `make test` to run the PHP tests. If `.env` is configured for MySQL, create or select its database and run `php artisan migrate` before using the application.

