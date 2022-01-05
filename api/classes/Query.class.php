<?php

/**
 *  Query class
 * 
 *  Handles db querying
 */
class Query implements ObjSerialize
{
    /**
     * List of clauses,
     * format is [`a`, `operator`, `b`]
     */
    private array $clauses;
    /**
     * Array of requested columns
     */
    private array $cols;
    /**
     * Table name
     */
    private string $table;
    /**
     * Sql string
     */
    private string $sql;
    /**
     * List of sorters
     * array keys are: `order`, `field`
     */
    private array $sorters;
    /**
     * Page size in pagination
     */
    private int $pageSize = 0;
    /**
     * Page start in pagination
     */
    private int $pageStart;
    /**
     * Number of pages
     */
    private int $noPages;
    /**
     * Class Version
     */
    public const version = "1.0.0";
    /**
     * List of PDO parameters
     */
    private array $pdoParams = [];
    /**
     * `PDO` database object
     */
    private ?PDO $pdo = null;
    /**
     * List of allowed operators
     */
    const operators = [
        "<",
        ">",
        "=",
        "LIKE",
        "!=",
        "SEARCH"
    ];
    public function  __construct(PDO $pdo)
    {
        $this->sql = "SELECT ";
        $this->pdo = $pdo;
    }
    /**
     * Get information about the pagination
     *
     *
     * @return array
     */
    public function getPaginationInfo(): array
    {
        return [
            "pageSize" => $this->pageSize,
            "pageStart" => $this->pageStart,
            "noPages" => $this->noPages
        ];
    }
    /**
     * Set DB Table name 
     *
     * @param string $table Table Name
     *
     * @return void
     */
    public function setTableName(string $table): void
    {
        $this->table = $table;
    }
    /**
     * Add clause to the query
     *
     * @param mixed $a `a`, can be table name or value
     * 
     * @param string $op `operator`, operator in the clause
     *
     * @param mixed $b `b`, can be table name or value
     *
     * @return void
     */
    public function clause($a, string $op, $b): void
    {
        if (gettype($a) === "boolean") $a = (int) $a;
        if (gettype($b) === "boolean") $b = (int) $b;
        $this->clauses[] = [$a, $op, $b];
    }
    /**
     * Set cols limit to the query
     *
     * @param array $cols set array of cols
     *
     *
     * @return void
     */
    public function limitCols(array $cols): void
    {
        $this->cols = $cols;
    }
    /**
     * Set pagination data
     *
     * @param int $pageSize page size
     *
     * @param int $pageStart starting page
     *
     * @param int $noPages number of pages to show
     * 
     * @return void
     */
    public function setPagination(int $pageSize, int $pageStart, int $noPages): void
    {
        $this->pageSize = $pageSize;
        $this->noPages = $noPages;
        $this->pageStart = $pageStart;
    }
    /**
     * Set field sorters
     * 
     * @param array $sorters
     * 
     * @return void
     */
    public function setSorters(array $sorters): void
    {
        $this->sorters = $sorters;
    }
    /**
     * Build `sql` query
     *
     * 
     * @return void
     */
    private function buildSQL(): void
    {
        if (!$this->table || !in_array($this->table, array_keys(Tables::$tables))) throw new OutOfDatabaseSpecification();
        $i = 0;
        foreach ($this->cols as $col) {
            if (!Tables::hasColumn($this->table, $col)) throw new OutOfDatabaseSpecification();
            $this->sql .= " " . $col . " ";
            if ($i < count($this->cols) - 1) $this->sql .= " , ";
            $i++;
        }
        if ($i == 0) $this->sql .= " * ";
        $this->sql .= " FROM " . $this->table . " WHERE ";
        $i = 0;
        if(count($this->clauses)==0) $this->sql.=" 1=1 ";
        foreach ($this->clauses as $c) {
            if ($c[1] == "SEARCH") $this->sql .= " trim(lower( ";
            if (Tables::hasColumn($this->table, $c[0])) {
                $this->sql .= " " . $c[0] . " ";
            } else {
                $holder = bin2hex(random_bytes(10));
                $this->sql .= " :$holder ";
                $this->pdoParams[":$holder"] = $c[0];
            }
            if ($c[1] == "SEARCH") $this->sql .= " )) ";
            if (!in_array($c[1], Query::operators)) {
                throw new OutOfDatabaseSpecification();
            } else {
                if ($c[1] == "SEARCH") {
                    $this->sql .= "LIKE ('%' || trim(lower(";
                } else {
                    $this->sql .= " " . $c[1] . " ";
                }
            }

            if (Tables::hasColumn($this->table, $c[2])) {
                $this->sql .= " " . $c[2] . " ";
            } else {
                $holder = bin2hex(random_bytes(10));
                $this->sql .= " :$holder ";
                $this->pdoParams[":$holder"] = $c[2];
            }
            if ($c[1] == "SEARCH") $this->sql .= " )) ";
            if ($i < count($this->clauses) - 1) $this->sql .= " AND ";
            $i++;
        }
        if (count($this->sorters)) $this->sql .= " ORDER BY ";
        $i = 0;
        foreach ($this->sorters as $sorter) {
            if (!Tables::hasColumn($this->table, $sorter["field"])) throw new OutOfDatabaseSpecification();
            if (!in_array($sorter["order"], ["ASC", "DESC"])) throw new OutOfDatabaseSpecification();
            $this->sql .= " " . $sorter["field"] . " " . $sorter["order"] . " ";
            if ($i < count($this->sorters) - 1) $this->sql .= " , ";
            $i++;
        }
        if ($this->pageSize > 0) {
            $this->sql .= "LIMIT " . $this->pageSize * $this->noPages . " OFFSET " . ($this->pageSize * $this->pageStart);
        }
    }
    /**
     * Convert array object to Query
     *
     * @param array $object The object
     * @return void
     */
    public function fromObj(array $object): void
    {
        if ($object["type"] != get_class($this) || $object["version"] != Query::version) throw new ObjectMismatch();
        $this->clauses = [];
        foreach ($object["clasuses"] as $clause) {
            $this->clause($clause["a"], $clause["operation"], $clause["b"]);
        }
        $this->limitCols($object["columns"] ?: []);
        $this->setSorters($object["sorters"] ?: []);
        if ($object["pagination"]) {
            $this->setPagination($object["pagination"]["size"], $object["pagination"]["start"] ?: 0, $object["pagination"]["noPages"] ?: 1);
        } else {
            $this->setPagination(0, 0, 0);
        }
    }
    /**
     * Convert Query to array object
     *
     * @return array The Object
     */
    public function  toObj(): array
    {
        $result = [];
        $result["type"] = get_class($this);
        $result["version"] = Query::version;
        foreach ($this->clauses as $clause) {
            $result["clauses"][] = ["a" => $clause[0], "operation" => $clause[1], "b" => $clause[2]];
        }
        $result["columns"] = $this->cols;
        $result["sorters"] = $this->sorters;
        if ($this->pageSize) {
            $result["pagination"]["size"] = $this->pageSize;
            $result["pagination"]["start"] = $this->pageStart ?: 0;
            $result["pagination"]["noPages"] = $this->noPages ?: 1;
        }
        return $result;
    }
    /**
     * Execute the current query
     * 
     * 
     * @return array results of the query
     */
    public function execute(): array
    {
        $this->buildSQL();
        $statement = $this->pdo->prepare($this->sql);
        if (count($this->pdoParams)) $statement->execute($this->pdoParams);
        else $statement->execute();
        return  $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
