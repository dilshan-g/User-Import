<?php
  
  require 'Database.php';
  require 'Helper.php';
  
  //Get the options from the command line.
  // `::` for the optional arguments and parameters
  $short_options = "u::p::h::";
  $long_options = ["file::", "create_table::", "help", "dry-run"];
  
  $options_list = getopt($short_options, $long_options);
  
  // If no options provided fail the script.
  if(!$options_list) {
    echo "One or more options required to run the script successfully.\n";
    echo "Please enter option --help to see the usage.\n";
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
  $db = new Database($config, $options_list['u'], $options_list['p'], $dry_run);
  
  // Create the table `users`.
  if (!empty($options_list['create_table'])) {
    $query = "CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            surname VARCHAR(50) NOT NULL,
            email VARCHAR(50) NOT NULL UNIQUE)";
    $db->createTable($query);
  }
  
  // Insert the users into the table `users`.
  if (!empty($options_list['file'])) {
    $csv = Helper::dataSourceManipulation($options_list['file']);
    $insert_query = "INSERT INTO users (name, surname, email) VALUES (:name, :surname, :email) ON DUPLICATE KEY UPDATE email=email";
    $db->insertUsers($insert_query, $csv);
  }