<?php
  
  /**
   * House the helper functions for the script.
   */
  class Helper
  {
    // ANSI escape codes for CLI text colors.
    // The concept was taken from ChatGPT.
    const RESET_COLOUR = "\033[0m";
    const CLI_RED = "\033[31m";
    const CLI_GREEN = "\033[32m";
    const CLI_YELLOW = "\033[33m";
    const CLI_BLUE = "\033[34m";
    const CLI_PURPLE = "\033[35m";
    const CLI_CYAN = "\033[36m";
    
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
      echo self::CLI_PURPLE . self::displayANSIBrandTitle() . self::RESET_COLOUR.PHP_EOL;
      echo PHP_EOL;
      echo self::CLI_YELLOW . "Usage:" . self::RESET_COLOUR.PHP_EOL;
      echo "php user_upload.php [arguments] [options]".PHP_EOL;
      echo PHP_EOL;
      echo self::CLI_YELLOW . "Arguments:" . self::RESET_COLOUR.PHP_EOL;
      echo self::CLI_GREEN . "  --file, --file=<arg>" . self::RESET_COLOUR . "                 CSV file name to be imported".PHP_EOL;
      echo self::CLI_GREEN . "  --create_table, --create_table=<arg>" . self::RESET_COLOUR . " MySQL table name to be created".PHP_EOL;
      echo self::CLI_GREEN . "  -u, -u=<arg>" . self::RESET_COLOUR . "                         MySQL username".PHP_EOL;
      echo self::CLI_GREEN . "  -p, -p=<arg>" . self::RESET_COLOUR . "                         MySQL password".PHP_EOL;
      echo self::CLI_GREEN . "  -h, -h=<arg>" . self::RESET_COLOUR . "                         MySQL hostname".PHP_EOL;
      echo PHP_EOL;
      echo self::CLI_YELLOW . "Options:" .self::RESET_COLOUR.PHP_EOL;
      echo self::CLI_GREEN . "  --dry-run " . self::RESET_COLOUR . "  Runs the whole script without changing the database.".PHP_EOL;
      // TODO: Implement this option to display more information about the script.
      echo self::CLI_GREEN . "  --about   " . self::RESET_COLOUR . "  More information about this script".PHP_EOL;
      echo PHP_EOL;
      echo self::CLI_YELLOW . "Example:" .self::RESET_COLOUR.PHP_EOL;
      echo self::CLI_CYAN . "php user_upload.php --file=users.csv --create_table=users -uuser -ppass -hmysql" . self::RESET_COLOUR.PHP_EOL;
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
      echo self::CLI_RED . "Are you sure you want to create a table and import " . $options['file'] . " into the database?" . self::RESET_COLOUR.PHP_EOL;
      echo PHP_EOL;
      echo self::CLI_YELLOW . "Actions:" . self::RESET_COLOUR.PHP_EOL;
      echo "A table name called " . $options['create_table'] . " will be created.".PHP_EOL;
      echo $options['file'] . " data will be imported to the newly created table.".PHP_EOL;
      echo PHP_EOL;
      echo "Append " . self::CLI_GREEN . "`--dry-run`" .self::RESET_COLOUR ." to the command to see the execution without altering the database." .PHP_EOL;
      echo PHP_EOL;
      echo "Type " . self::CLI_GREEN . "'yes'" . self::RESET_COLOUR . " to continue or" . self::CLI_RED . " 'no'" . self::RESET_COLOUR . " to abort: ";
      $handle = fopen ("php://stdin","r");
      $line = fgets($handle);
      if(trim($line) != 'yes'){
        echo self::CLI_YELLOW . "ABORTING!" . self::RESET_COLOUR.PHP_EOL;
        echo PHP_EOL;
        exit;
      }
      echo PHP_EOL;
      echo self::CLI_GREEN . "Running the database updates now.." . self::RESET_COLOUR.PHP_EOL;
      echo PHP_EOL;
    }
    
    /**
     * Displays info about the `--dry-run`.
     *
     * @return void
     */
    public static function displayDryRunMessage(): void
    {
      echo self::CLI_GREEN . "This action will not make any changes to the database." . self::RESET_COLOUR.PHP_EOL;
      echo self::CLI_GREEN . "Mocking the execution steps..." . self::RESET_COLOUR.PHP_EOL;
      echo PHP_EOL;
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
    
    /**
     * ANSI text art generated to display the brand name.
     * Generated by https://patorjk.com/
     *
     * @return string
     *  ANSI text art.
     */
    public static function displayANSIBrandTitle(): string
    {
      return "
       _   _                    _____                                 _
      | | | |                  |_   _|                               | |
      | | | | ___   ___  _ __    | |   _ __ ___   _ __    ___   _ __ | |_
      | | | |/ __| / _ \| '__|   | |  | '_ ` _ \ | '_ \  / _ \ | '__|| __|
      | |_| |\__ \|  __/| |     _| |_ | | | | | || |_) || (_) || |   | |_
       \___/ |___/ \___||_|     \___/ |_| |_| |_|| .__/  \___/ |_|    \__|
                                                 | |
                                                 |_|
      ";
    }
  }