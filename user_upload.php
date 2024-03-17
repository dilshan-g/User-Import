<?php
  
  require 'Database.php';
  require 'Helper.php';
  
  //Get the options from the command line.
  // `::` for the optional arguments and parameters
  $short_options = "u::p::h::";
  $long_options = ["file::", "create_table::", "help", "dry-run"];
  
  $options_list = getopt($short_options, $long_options);
  
  // Exit the script if the valid CSV not provided.
  if (isset($options_list['file']) && $options_list['file'] != "users.csv") {
    echo Helper::CLI_RED . $options_list['file'] . Helper::RESET_COLOUR . " does not exists. Please enter "  . Helper::CLI_GREEN . "`users.csv`" . Helper::RESET_COLOUR . " instead." .PHP_EOL;
    echo PHP_EOL;
    exit;
  }
  
  // Exit the script if the valid table name not provided.
  // TODO: Remove this validation once the we can dynamically accept the table name to create any table.
  if (isset($options_list['create_table']) && $options_list['create_table'] != "users") {
    echo Helper::CLI_RED . $options_list['create_table'] . Helper::RESET_COLOUR . " is an invalid table name. Please enter "  . Helper::CLI_GREEN . "`users`" . Helper::RESET_COLOUR . " instead." .PHP_EOL;
    echo PHP_EOL;
    exit;
  }

  
  // If no options provided fail the script.
  if(!$options_list) {
    echo Helper::CLI_PURPLE . Helper::displayANSIBrandTitle() . Helper::RESET_COLOUR.PHP_EOL;
    echo PHP_EOL;
    echo Helper::CLI_YELLOW . "One or more options required to run the script successfully." . Helper::RESET_COLOUR.PHP_EOL;
    echo "Please enter option " . Helper::CLI_GREEN . "`--help`" . Helper::RESET_COLOUR . " to see the usage.\n";
    echo PHP_EOL;
    exit;
  }
  
  if (isset($options_list['help'])) {
    Helper::displayHelpInfo();
    exit;
  }
  
  // If the `--dry-run` option is not selected, proceed to the import.
  if (!isset($options_list['dry-run'])) {
    Helper::displayImportActionPrompt($options_list);
  } else {
    Helper::displayDryRunMessage();
  }
  
  // The options are returning `FALSE` when it's added.
  // Changing it to `TRUE` to make sense.
  $dry_run = NULL;
  if (isset($options_list['dry-run'])) {
    $dry_run = TRUE;
  }
  
  // Configs to make the connection string.
  $config = [
    'host' => $options_list['h'],
    'dbname' => 'users',
  ];
  
  // Instantiate the DB connection.
  $db = new Database($config, $options_list['u'], $options_list['p'], $dry_run, $options_list['create_table']);
  
  // Create the table `users`.
  // TODO: Dynamically pass the table value to be able to create a table with any name.
  if (!empty($options_list['create_table'])) {
    $query = "CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            surname VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE)";
    $db->createTable($query);
  }
  
  // Insert the users into the table `users`.
  // TODO: Dynamically pass the table name rather than hardcoding.
  if (!empty($options_list['file'])) {
    $csv = Helper::dataSourceManipulation($options_list['file']);
    $insert_query = "INSERT INTO users (name, surname, email) VALUES (:name, :surname, :email) ON DUPLICATE KEY UPDATE email=email";
    $db->insertUsers($insert_query, $csv);
  }