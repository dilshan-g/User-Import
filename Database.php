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
    protected PDO $connection;
    
    /**
     * The list of arguments supplied at the initiation of the class.
     *
     * @var array
     */
    public array $args;
    
    /**
     * Check if the table exists.
     *
     * @var bool
     */
    private bool $is_table_exists;
    
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
        $this->is_table_exists = $this->isTableAlreadyExists($args[3]);
        
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
        
        if ($this->is_table_exists) {
          echo Helper::CLI_YELLOW . "Table users already exists, no new table will be created." . Helper::RESET_COLOUR.PHP_EOL;
        }
        
        // Don't create the table when doing `--dry-run` and table already exists.
        if (is_null($this->args[2]) && !$this->is_table_exists) {
          // Use prepare statements for better protection against SQL Injections.
          $statement = $this->connection->prepare($query);
          $statement->execute();
          echo Helper::CLI_GREEN . "Table users created successfully!" . Helper::RESET_COLOUR.PHP_EOL;
        }
        
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

          // Pass the arguments email and the table name.
          if ($this->is_table_exists && $this->isUserAlreadyExists($user['email'])){
            echo "User already exists, skipping to the next row.".PHP_EOL;
            continue;
          }
          
          // Don't insert records to the table when doing `--dry-run`.
          if (is_null($this->args[2])) {
            // Use prepare statements for better protection against SQL Injections.
            $statement = $this->connection->prepare($query);
            $statement->execute($user);
          }
          ++$count;
          echo Helper::CLI_GREEN . "New user record created" . Helper::RESET_COLOUR.PHP_EOL;
        }
        
        // We don't update any records during `--dry-run` so display the count as 0.
        $count = (is_null($this->args[2])) ? $count : 0;
        echo Helper::CLI_GREEN . $count . Helper::RESET_COLOUR . " records were inserted or updated" . PHP_EOL;
        echo PHP_EOL;
        
      } catch (PDOException $e) {
        throw new PDOException(Helper::CLI_RED . "Cannot insert records to the database: " . Helper::RESET_COLOUR.$e->getMessage());
      }
    }
    
    /**
     * Check if the user already exists in the table.
     *
     * @param string $email
     * @return bool
     */
    public function isUserAlreadyExists(string $email): bool
    {
      $table = $this->args[3];
      // Use prepare statements for better protection against SQL Injections.
      $statement = $this->connection->prepare("SELECT * FROM $table WHERE email = :email");
      $statement->execute(['email' => $email]);
      // Check if a row is returned
      return ($statement->rowCount() > 0) ? TRUE : FALSE;
    }
    
    /**
     * Check if the table already exists.
     *
     * @param string $table
     * @return bool
     */
    public function isTableAlreadyExists(string $table): bool
    {
      // Use prepare statements for better protection against SQL Injections.
      $statement =  $this->connection->prepare("SELECT 1 FROM information_schema.tables
        WHERE table_schema = database() AND table_name = ?");
      $statement->execute([$table]);
      return (bool)$statement->fetchColumn();
    }
    
  }
  
