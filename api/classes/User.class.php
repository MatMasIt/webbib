<?php

/**
 *  User class
 * 
 *  Handles manipulating User elements,
 *  authentication and identity
 */
class User implements CRUDL, ObjSerialize, Validation, Authentication

{
    /**
     * Class version
     */
    public const version = "1.0.0";
    /**
     * Table name associated with the current class
     */
    public const tableName = "Users";
    /**
     * `PDO` database object
     */
    private PDO $pdo;
    /**
     * User issuing the request
     *  nullable for the current user object
     */
    private ?User $u;
    /**
     * User id
     */
    public int $id;
    /**
     * Name of the user
     */
    public string $name;
    /**
     * Surname of the user
     */
    public string $surname;
    /**
     * Email of the user
     */
    public string $email;
    /**
     * Hash of the password
     */
    public string $passwordHash;
    /**
    * Session token
     */
    public string $token;
    /**
     * Email reset token
     */
    public string $emailToken;
    /**
     * Unix birthdate of the user
     */
    public int $birthDate;
    /**
     * Unix last edit date
     */
    public int $lastEdit;
    /**
     * Unix creation date
     */
    public int $created;
    /**
     * Allow login
     */
    public bool $allowLogin;
    /**
     * Is staff
     */
    public bool $isStaff;
    /**
     * Is enabled
     */
    public bool $enabled;
    public function __construct(PDO $pdo, ?User $u)
    {
        $this->pdo = $pdo;
        $this->u = $u;
    }

    /**
     * List Users with query
     *
     * Obtain user list by query
     *
     * @param Query $q Query data
     *
     * @return ApiResult
     */
    public function list(Query $q): ApiResult
    {
        if (!$this->u->isStaff) {
            $a = new ApiResult();
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $q->setTableName(User::tableName);
        $final = [];
        $paInfo = $q->getPaginationInfo();
        foreach ($q->execute() as $l) {
            $final[] = [
                "type" => (string) get_class($this),
                "version" => (string) Book::version,
                "data" => [
                    "id" => (int) $l["id"],
                    "name" => (string)  $l["name"],
                    "surname" => (string)  $l["surname"],
                    "birthDate" => (int)  $l["birthDate"],
                    "email" => (string)  $l["email"],
                    "allowLogin" => (bool)  $l["allowLogin"],
                    "isStaff" => (bool)  $l["isStaff"],
                    "enabled" => (bool)  $l["enabled"],
                ],
                "lastEdit" => (int) $l["lastEdit"],
                "created" => (int) $l["created"]
            ];
        }
        if ($paInfo["pageSize"] == 0) $final = Tables::paginateArray($final, $paInfo);
        $ar = new ApiResult();
        $ar->data($final);
        return $ar;
    }

    /**
     * Load User by id
     *
     * Obtain User by provided id
     *
     * @param int $id Book id
     *
     * @return ApiResult
     */
    public function load(int $id): void
    {
        $a = new ApiResult();
        if (!$this->u->isStaff) {
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $this->id = $id;
        $s = $this->pdo->prepare("SELECT * FROM " . User::tableName . " WHERE id=:id");
        $s->execute([":id" => $this->id]);
        $r = $s->fetch(PDO::FETCH_ASSOC);
        $a = new ApiResult();
        if (!$r || !$this->u->isStaff) {
            $a->error(QError::NOT_FOUND);
            $a->send();
        } else {
            $this->id = (int) $r["id"];
            $this->name = (string) $r["name"];
            $this->surname = (string) $r["surname"];
            $this->birthDate = (int) $r["birthDate"];
            $this->email = (string) $r["email"];
            $this->allowLogin = (string) $r["allowLogin"];
            $this->passwordHash = (string) $r["passwordHash"];
            $this->isStaff = (bool) $r["isStaff"];
            $this->enabled = (bool) $r["enabled"];
            $this->token = (string) $r["token"];
            $this->emailToken = (string) $r["emailToken"];
            $this->lastEdit = (string) $r["lastEdit"];
            $this->created = (string) $r["created"];
        }
    }
    /**
     * Create User
     *
     * Create User and return `ApiResult`
     *
     * @return ApiResult
     */
    public function create(): ApiResult
    {
        $a = new ApiResult();
        if (!$this->u->isStaff || !$this->validate()) {
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $this->lastEdit = time();
        $this->created = time();
        $this->emailToken = bin2hex(random_bytes(16));
        $s = $this->pdo->prepare("INSERT INTO " . User::tableName . "(name,surname,birthDate,email,allowLogin,passwordHash,isStaff,enabled,emailToken,lastEdit,created) VALUES(:name,:surname,:birthDate,:email,:allowLogin,:passwordHash,:isStaff,:enabled,:emailToken,:lastEdit,:created)");
        $s->execute([
            ":name" => $this->name,
            ":surname" => $this->surname,
            ":birthDate" => $this->birthDate,
            ":email" => $this->email,
            ":allowLogin" => (int) $this->allowLogin,
            ":passwordHash" => $this->passwordHash,
            ":enabled" => (int) $this->enabled,
            ":emailToken" => $this->emailToken,
            ":isStaff" => (int) $this->isStaff,
            ":lastEdit" => $this->lastEdit,
            ":created" => $this->created
        ]);
        $a->data(["id" => (int)$this->pdo->lastInsertId()]);
        return $a;
    }
    /**
     * Save current user data
     *
     *
     * @return ApiResult
     */
    public function save(): ApiResult
    {
        $a = new ApiResult();
        if (!$this->u->isStaff || !$this->validate()) {
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $this->lastEdit = time();
        $this->created = time();
        $this->emailToken = bin2hex(random_bytes(16));
        $s = $this->pdo->prepare("UPDATE " . User::tableName . "SET name=:name, surname=:surname, birthDate=:birthDate, email=:email, allowLogin=:allowLogin, passwordHash=:passwordHash, isStaff=:isStaff, enabled=:enabled, token=:token, emailToken=:emailToken, lastEdit=:lastEdit, created=:created WHERE id=:id");
        $s->execute([
            ":id" => $this->id,
            ":name" => $this->name,
            ":surname" => $this->surname,
            ":birthDate" => $this->birthDate,
            ":email" => $this->email,
            ":allowLogin" => (int) $this->allowLogin,
            ":passwordHash" => "lol",
            ":enabled" => (int) $this->enabled,
            ":token" => $this->token,
            ":emailToken" => $this->emailToken,
            ":isStaff" => (int) $this->isStaff,
            ":lastEdit" => $this->lastEdit,
            ":created" => $this->created
        ]);
        $a->data([]);
        return $a;
    }
    /**
     * Delete user
     *
     *
     * @return ApiResult
     */
    public function delete(): ApiResult
    {
        $a = new ApiResult();
        if (empty($this->id)) {
            $a->error(QError::NOT_FOUND);
            $a->send();
        }
        if (!$this->u->isStaff || !$this->validate()) {
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $s = $this->pdo->prepare("DELETE FROM " . User::tableName . " WHERE id=:id");
        $s->execute([":id" => $this->id]);
        $a->data([]);
        return $a;
    }
    /**
     * Convert User to array object
     *
     * @return array The Object
     */
    public function toObj(): array
    {
        return [
            "type" => get_class($this),
            "version" => User::version,
            "id" => $this->id,
            "data" => [
                "name" => $this->name,
                "surname" => $this->surname,
                "email" => $this->email,
                "isStaff" => $this->isStaff,
                "allowLogin" => $this->allowLogin,
                "birthDate" => $this->birthDate,
                "token" => $this->token,
            ],
            "lastEdit" => $this->lastEdit,
            "created" => $this->created
        ];
    }
    /**
     * Convert array object to User
     *
     * @param array $object The object
     * @return void
     */
    public function fromObj(array $object): void
    {
        if ($object["type"] != get_class($this) || $object["version"] != User::version) throw new ObjectMismatch();
        $this->id = (int) $object["id"];
        $this->name = (string) $object["data"]["name"];
        $this->surname = (string) $object["data"]["surname"];
        $this->email = (string) $object["data"]["email"];
        $this->isStaff = (bool) $object["data"]["isStaff"];
        $this->allowLogin = (bool) $object["data"]["allowLogin"];
        $this->birthDate = (int) $object["data"]["birthDate"];
        $this->token = (int) $object["data"]["token"];
        $this->lastEdit = (int) $object["lastEdit"];
        $this->created = (int) $object["created"];
    }

    /**
     * Validate User
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->email) || empty($this->name) || empty($this->surname) || !filter_var($this->email, FILTER_SANITIZE_EMAIL)) return false;
        return true;
    }
    /**
     * Authenticate with token
     *
     * @param string $token The session token
     * @return void
     */
    public function fromToken(string $token): void
    {
        $p = $this->pdo->prepare("SELECT id FROM " . User::tableName . " WHERE token=:token");
        $p->execute([":token" => $token]);
        $res = $p->fetchAll(PDO::FETCH_ASSOC);
        if (count($res) == 0) {
            $a = new ApiResult();
            $a->error(QError::NOT_FOUND);
            $a->send();
        }
        $this->load($res[0]["id"]);
    }
    /**
     * Set new session token for user
     *
     * @return string the session token
     */
    private function makeToken(): string
    {
        $this->token = UUID::v4();
        $this->save();
        return $this->token;
    }
    /**
     * Authenticate with username & password
     *
     * @param string $email The user email
     * @param password $email The user password
     * @return bool authenticated
     */
    public function login(string $email, string $password): bool
    {
        $p = $this->pdo->prepare("SELECT id,email,passwordHash FROM " . User::tableName . " WHERE email=:email");
        $p->execute([":email" > $email]);
        $r = $p->fetch(PDO::FETCH_ASSOC);
        if (!$r) {
            $a = new ApiResult();
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        if (!password_verify($password, $r["passwordHash"])) {
            $a = new ApiResult();
            $a->error(QError::UNAUTHORIZED);
            $a->send();
        }
        $this->load($r["id"]);
        $this->makeToken();
        return true;
    }
    /**
     * Logout the current user by invalidating the token
     *
     * @return void
     */
    public function logout(): void
    {
        $this->load($this->id);
        $this->makeToken();
    }
    /**
     * Check if User exists
     * static
     * @param PDO $pdo PDO php object
     * @return bool lookup result
     */
    public static function exists(PDO $pdo, int $id): bool
    {
        $p = $pdo->prepare("SELECT COUNT(*) as c FROM " . User::tableName . " WHERE id=:id");
        $p->execute([":id" => $id]);
        return $p->fetch(PDO::FETCH_ASSOC)["c"] > 0;
    }
}
