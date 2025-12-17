<?php

declare(strict_types=1);

// Cross-platform local launcher for `make` and `mingw32-make`.
$root = dirname(__DIR__);
chdir($root);

function fail(string $message): never
{
    fwrite(STDERR, "Error: {$message}\n");
    exit(1);
}

function run(string $command): void
{
    echo "> {$command}\n";
    passthru($command, $status);
    if ($status !== 0) {
        fail("Command failed with exit code {$status}: {$command}");
    }
}

$mode = $argv[1] ?? 'run';
if (!in_array($mode, ['run', 'setup'], true)) {
    fail('Expected run or setup.');
}

if (!is_file('vendor/autoload.php')) {
    run('composer install --no-interaction --prefer-dist');
}

if (!is_file('.env')) {
    if (!copy('.env.example', '.env')) {
        fail('Could not create .env from .env.example.');
    }
    echo "Created .env from .env.example\n";
}

$env = file_get_contents('.env');
if ($env === false) {
    fail('Could not read .env.');
}

if (preg_match('/^APP_KEY=\s*$/m', $env)) {
    run('php artisan key:generate');
}

if (preg_match('/^DB_CONNECTION=sqlite\s*$/m', $env)) {
    $database = 'database/database.sqlite';
    $freshDatabase = !is_file($database);
    if ($freshDatabase && touch($database) === false) {
        fail('Could not create the SQLite database.');
    }
    run('php artisan migrate --force');
    if ($freshDatabase) {
        run('php artisan db:seed --force');
    }
} else {
    echo "Using the database configured in .env. Run 'php artisan migrate' if its schema needs updating.\n";
}

$vite = PHP_OS_FAMILY === 'Windows' ? 'node_modules/.bin/vite.cmd' : 'node_modules/.bin/vite';
if (!is_file($vite)) {
    run('npm ci');
}
run('npm run build');

if ($mode === 'setup') {
    echo "Setup complete. Run 'make' to start Servora.\n";
    exit(0);
}

$port = getenv('PORT') ?: '8000';
if (!ctype_digit($port) || (int) $port < 1 || (int) $port > 65535) {
    fail('PORT must be a number from 1 to 65535.');
}

$socket = @fsockopen('127.0.0.1', (int) $port, $errorCode, $errorText, 1);
if ($socket !== false) {
    fclose($socket);
    $response = @file_get_contents("http://127.0.0.1:{$port}/", false, stream_context_create([
        'http' => ['timeout' => 15, 'ignore_errors' => true],
    ]));
    if ($response !== false && str_contains($response, 'Servora')) {
        echo "Servora is already running at http://127.0.0.1:{$port}\n";
        exit(0);
    }
    fail("Port {$port} is already in use. Set PORT to a free port and run make again.");
}

echo "Starting Servora at http://127.0.0.1:{$port} (press Ctrl+C to stop)\n";
run('php artisan serve --host=127.0.0.1 --port='.$port);
