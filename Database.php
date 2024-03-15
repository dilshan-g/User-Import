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
        
        echo "DB connection established" . PHP_EOL;
        
      } catch (PDOException $e) {
        throw new PDOException("Unable to connect to the Database: " . $e->getMessage());
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
        echo "Table users created successfully!" . PHP_EOL;
      } catch (PDOException $e) {
        throw new PDOException("Unable to create the table: " . $e->getMessage());
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
            'email' => $this->checkValidEmail($user[2]),
          ];
          
          if (!$user['email']) {
            echo "Invalid email provided: entry skipped" . PHP_EOL;
            continue;
          }
          
          // Don't insert records to the table when doing `--dry-run`.
          if (is_null($this->args[2])) {
            $statement = $this->connection->prepare($query);
            $statement->execute($user);
          }
          ++$count;
          echo "New user record created" . PHP_EOL;
        }
        
        echo $count . " records were inserted or updated" . PHP_EOL;
        
      } catch (PDOException $e) {
        throw new PDOException("Cannot insert records to the database: " . $e->getMessage());
      }
    }
    
    /**
     * Validates the email address for each user.
     *
     * @param string $email
     *  Email to be validated.
     * @return mixed
     *  Return false if not valid, returns the email if valid.
     */
    public function checkValidEmail(string $email): mixed
    {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
  }
  
