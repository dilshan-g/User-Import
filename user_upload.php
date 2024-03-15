<?php
  
  require 'Database.php';
  
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
    displayHelp();
    exit;
  }
  
  // Function to display the script usage information.
  function displayHelp(): void
  {
    echo PHP_EOL;
    echo "Usage:".PHP_EOL;
    echo "php user_upload.php [arguments] [options]".PHP_EOL;
    echo PHP_EOL;
    echo "Arguments:".PHP_EOL;
    echo "  --file, --file=<arg>                   CSV file name to be imported".PHP_EOL;
    echo "  --create_table, --create_table=<arg>   MySQL table name to be created".PHP_EOL;
    echo "  -u, -u=<arg>                           MySQL username".PHP_EOL;
    echo "  -p, -p=<arg>                           MySQL password".PHP_EOL;
    echo "  -h, -h=<arg>                           MySQL hostname".PHP_EOL;
    echo PHP_EOL;
    echo "Options:".PHP_EOL;
    echo "  --dry-run   Runs the whole script without changing the database.".PHP_EOL;
    echo "  --about     More information about this script".PHP_EOL;
    echo PHP_EOL;
    echo "Example:".PHP_EOL;
    echo "php user_upload.php --file=users.csv --create_table=users -uuser -ppass -hmysql".PHP_EOL;
    echo PHP_EOL;
  }
  
  // If the `--dry-run` option is not selected, proceed to the import.
  if (!isset($options_list['dry-run'])) {
    echo "Are you sure you want to import " . $options_list['file'] . " into the database?".PHP_EOL;
    echo "Actions:".PHP_EOL;
    echo "A table name called " . $options_list['create_table'] . " will be created.".PHP_EOL;
    echo $options_list['file'] . " data will be imported to the newly created table.".PHP_EOL;
    echo "Append `--dry-run` to the command to see the execution without altering the database.".PHP_EOL;
    echo "Type 'yes' to continue: ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    if(trim($line) != 'yes'){
      echo "ABORTING!".PHP_EOL;
      exit;
    }
    echo PHP_EOL;
    echo "Running the database updates now..".PHP_EOL;
  } else {
    echo "This action will not make any changes to the database.".PHP_EOL;
    echo "Mocking the execution...".PHP_EOL;
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