<?php
  
  /**
   * The database operations handler.
   */
  class Database
  {
    /**
     * The database connection.
     *
     * @var PDO
     */
    public PDO $connection;
    
    /**
     * The list of arguments supplied at the initiation of the class.
     *
     * @var array
     */
    public array $args;
    
    /**
     * Constructor for the Database class.
     * Best practice is to initiate the DB connection inside the constructor.
     * So that the connection will be available to use anywhere.
     *
     * @param array $config
     *    Configs to set up the `dsn` string for PDO
     * @param ...$args
     *    Additional options to make the database connection.
     *
     */
    public function __construct(array $config, ...$args)
    {
      try {
        $this->args = $args;
        
        $dsn = 'mysql:' . http_build_query($config, '', ';');
        // Set the PDO error mode to exception
        $this->connection = new PDO($dsn, $args[0], $args[1], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        echo Helper::CLI_GREEN . "DB connection established" . Helper::RESET_COLOUR.PHP_EOL;
        
      } catch (PDOException $e) {
        throw new PDOException(Helper::CLI_RED . "Unable to connect to the Database: " . Helper::RESET_COLOUR.$e->getMessage());
      }
    }
    
    /**
     * Creates a table named `users`.
     *
     * @param string $query
     *  Table creation query
     *
     * @return void
     */
    public function createTable(string $query): void
    {
      try {
        // Don't create the table when doing `--dry-run`.
        if (is_null($this->args[2])) {
          $statement = $this->connection->prepare($query);
          $statement->execute();
        }
        echo Helper::CLI_GREEN . "Table users created successfully!" . Helper::RESET_COLOUR.PHP_EOL;
      } catch (PDOException $e) {
        throw new PDOException(Helper::CLI_RED . "Unable to create the table: " . Helper::RESET_COLOUR.$e->getMessage());
      }
    }
    
    /**
     * Inserts the users to the table with validations.
     * If duplicate emails found, entry will be ignored.
     * If an invalid email found, entry will be ignored.
     *
     * @param string $query
     *  Insert query to add the records to the DB.
     *
     * @param array $users
     *  Array of users derived by the CSV.
     *
     * @return void
     */
    public function insertUsers(string $query, array $users): void
    {
      try {
        $count = 0;
        foreach ($users as $user) {
          // Capitalise the first letter of the `name` and `surname`.
          // Check if the `email` is in valid format.
          $user = [
            'name' => ucfirst(strtolower($user[0])),
            'surname' => ucfirst(strtolower($user[1])),
            'email' => Helper::checkValidEmail($user[2]),
          ];
          
          if (!$user['email']) {
            echo Helper::CLI_YELLOW . "Invalid email provided: entry skipped" . Helper::RESET_COLOUR.PHP_EOL;
            continue;
          }
          
          // Don't insert records to the table when doing `--dry-run`.
          if (is_null($this->args[2])) {
            $statement = $this->connection->prepare($query);
            $statement->execute($user);
          }
          ++$count;
          echo Helper::CLI_GREEN . "New user record created" . Helper::RESET_COLOUR.PHP_EOL;
        }
        
        $count = (is_null($this->args[2])) ? $count : 0;
        echo Helper::CLI_GREEN . $count . Helper::RESET_COLOUR . " records were inserted or updated" . PHP_EOL;
        echo PHP_EOL;
        
      } catch (PDOException $e) {
        throw new PDOException(Helper::CLI_RED . "Cannot insert records to the database: " . Helper::RESET_COLOUR.$e->getMessage());
      }
    }
  }
  
