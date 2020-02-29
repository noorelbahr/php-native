<?php

error_reporting(0);

require_once './config/app.php';
require_once './core/Database.php';
require_once './database/migrations/Migration.php';
require_once './database/seeds/Seeder.php';

// Migrate tables
$migration = new Migration();
$migration->migrate();

// Seed default data
$seeder = new Seeder();
$seeder->seed();

die(
    "\n" .
    "- - - - - - - - - -\n" .
    "- - - Success - - -\n" .
    "- - - - - - - - - -\n"
);
