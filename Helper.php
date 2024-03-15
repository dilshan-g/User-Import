<?php
  
  /**
   * House the helper functions for the script.
   */
  class Helper
  {
    /**
     * Validates the email address for each user.
     *
     * @param string $email
     *  Email to be validated.
     * @return mixed
     *  Return false if not valid, returns the email if valid.
     */
    public static function checkValidEmail(string $email): mixed
    {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Function to display the script usage information.
     *
     * @return void
     */
    public static function displayHelpInfo(): void
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
    
    /**
     * Displays the information on running the script.
     * Prompts the user to confirm or abort the execution.
     *
     * @param array $options
     *  CLI options entered by the user.
     *
     * @return void
     */
    public static function displayImportActionPrompt(array $options): void
    {
      echo "Are you sure you want to import " . $options['file'] . " into the database?".PHP_EOL;
      echo "Actions:".PHP_EOL;
      echo "A table name called " . $options['create_table'] . " will be created.".PHP_EOL;
      echo $options['file'] . " data will be imported to the newly created table.".PHP_EOL;
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
    }
    
    /**
     * Displays info about the `--dry-run`.
     *
     * @return void
     */
    public static function displayDryRunMessage(): void
    {
      echo "This action will not make any changes to the database.".PHP_EOL;
      echo "Mocking the execution...".PHP_EOL;
    }
    
    /**
     * Reads the user entered CSV and creates an Array with headers removed.
     *
     * @param string $source
     *  File name added by the user.
     *
     * @return array
     *  Array of users to be inserted.
     */
    public static function dataSourceManipulation(string $source): array
    {
      $lines = file($source, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
      $csv = array_map('str_getcsv', $lines);
      
      // Remove the first row as it has the header values.
      array_shift($csv);
      return $csv;
    }
  }