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
        //echo "Unable to connect to the database: " . $e->getMessage();
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
        // Don't create th table when doing `--dry-run`.
        if (is_null($this->args[2])) {
          $statement = $this->connection->prepare($query);
          $statement->execute();
        }
        echo "Table users created successfully!" . PHP_EOL;
      } catch (PDOException $e) {
        throw new PDOException("Unable to create the table: " . $e->getMessage());
      }
    }
  }
