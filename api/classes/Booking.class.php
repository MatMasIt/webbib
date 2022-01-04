<?php

/**
 *  Booking class
 * 
 *  Handles manipulating Booking elements
 */
class Booking implements CRUDL, ObjSerialize, Validation
{
    /**
     * Class version
     */
    public const version = "1.0.0";
    /**
     * Table name associated with the current class
     */
    public const tableName = "Booking";
    /**
     * `PDO` database object
     */
    private PDO $pdo;
    /**
     * User issuing the request, can be null if this is the object in question
     */
    private ?User $u;
    /**
     * Booking id
     */
    public int $id;
    /**
     * Booking start date
     */
    public int $start;
    /**
     * Booking end date
     */
    public int  $end;
    /**
     * Booking date given back
     */
    public int  $gaveBackDate;
    /**
     * Booking last edit date
     */
    public int  $lastEdit;
    /**
     * Booking created date
     */
    public int  $created;
    /**
     * Librarian Id
     */
    public int $librarianId;
    /**
     * Booker Id
     */
    public int $bookerId;
    /**
     * Private comment only visible to a librarian
     */
    public string $privateComment;
    /**
     * Has been approved
     */
    public bool $approved;
    public function __construct(PDO $pdo, ?User $u)
    {
        $this->pdo = $pdo;
        $this->u = $u;
    }
    /**
     * List Bookings with query
     *
     * Obtain bookings list by query
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
        $q->setTableName(Booking::tableName);
        $final = [];
        $paInfo = $q->getPaginationInfo();
        foreach ($q->execute() as $l) {
            $final[] = [
                "type" => (string) get_class($this),
                "version" => (string) Booking::version,
                "data" => [
                    "id" => (int) $l["id"],
                    "start" => (int)  $l["start"],
                    "end" => (int)  $l["end"],
                    "librarianId" => (int)  $l["librarianId"],
                    "bookerId" => (int)  $l["bookerId"],
                    "gaveBackDate" => (int)  $l["gaveBackDate"],
                    "privateComment" => (string)  $l["privateComment"],
                    "approved" => (bool)  $l["approved"]
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
     * Load Booking by id
     *
     * Obtain booking by prvovided id
     *
     * @param int $id Booking id
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
            $this->start = (int) $r["name"];
            $this->end = (int) $r["end"];
            $this->librarianId = (int) $r["librarianId"];
            $this->bookerId = (int) $r["bookerId"];
            $this->gaveBackDate = (int) $r["gaveBackDate"];
            $this->privateComment = (string) $r["privateComment"];
            $this->approved = (bool) $r["approved"];
            $this->lastEdit = (string) $r["lastEdit"];
            $this->created = (string) $r["created"];
        }
    }
    /**
     * Create Booking
     *
     * Create Booking and return `ApiResult`
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
        $s = $this->pdo->prepare("INSERT INTO " . Booking::tableName . "(start,end,librarianId,bookerId,gaveBackDate,privateComment,approved,lastEdit,created) VALUES(:start,:end,:librarianId,:bookerId,:gaveBackDate,:privateComment,:approved,:lastEdit,:created)");
        $s->execute([
            ":start" => $this->start,
            ":surname" => $this->surname,
            ":librarianId" => $this->librarianId,
            ":bookerId" => $this->bookerId,
            ":gaveBackDate" => (int) $this->gaveBackDate,
            ":privateComment" => $this->privateComment,
            ":approved" => (int) $this->approved,
            ":lastEdit" => $this->lastEdit,
            ":created" => $this->created
        ]);
        $a->data(["id" => (int)$this->pdo->lastInsertId()]);
        return $a;
    }
    /**
     * Save current booking data
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
        $s = $this->pdo->prepare("UPDATE " . Booking::tableName . " SET start=:start, surname=:surname, librarianId=:librarianId, bookerId=:bookerId, gaveBackDate=:gaveBackDate, privateComment=:privateComment, approved=:approved, lastEdit=:lastEdit, created=:created WHERE id=:id");
        $s->execute([
            ":id" => $this->id,
            ":start" => $this->start,
            ":surname" => $this->surname,
            ":librarianId" => $this->librarianId,
            ":bookerId" => $this->bookerId,
            ":gaveBackDate" => (int) $this->gaveBackDate,
            ":privateComment" => $this->privateComment,
            ":approved" => (int) $this->approved,
            ":lastEdit" => $this->lastEdit,
            ":created" => $this->created
        ]);
        $a->data([]);
        return $a;
    }

    /**
     * Delete Booking
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
        $s = $this->pdo->prepare("DELETE FROM " . Booking::tableName . " WHERE id=:id");
        $s->execute([":id" => $this->id]);
        $a->data([]);
        return $a;
    }
    /**
     * Convert booking to array object
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
                "start" => $this->start,
                "end" => $this->end,
                "librarianId" => $this->librarianId,
                "bookerId" => $this->bookerId,
                "gaveBackDate" => $this->gaveBackDate,
                "privateComment" => $this->privateComment,
                "approved" => $this->approved
            ],
            "lastEdit" => $this->lastEdit,
            "created" => $this->created
        ];
    }
    /**
     * Convert array object to Booking
     *
     * @param array $object The object
     * @return void
     */
    public function fromObj(array $object): void
    {
        if ($object["type"] != get_class($this) || $object["version"] != User::version) throw new ObjectMismatch();
        $this->id = (int) $object["id"];
        $this->start = (int) $object["data"]["start"];
        $this->end = (int) $object["data"]["end"];
        $this->librarianId = (int) $object["data"]["librarianId"];
        $this->bookerId = (int) $object["data"]["bookerId"];
        $this->gaveBackDate = (int) $object["data"]["gaveBackDate"];
        $this->privateComment = (string) $object["data"]["privateComment"];
        $this->approved = (bool) $object["data"]["approved"];
        $this->lastEdit = (int) $object["lastEdit"];
        $this->created = (int) $object["created"];
    }
    /**
     * Validate Booking
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->librarianId) || empty($this->bookerId)) return false;
        return true;
    }

    /**
     * Check if Booking exists
     * static
     * @param PDO $pdo PDO php object
     * @return bool lookup result
     */
    public static function exists(PDO $pdo, int $id): bool
    {
        $p = $pdo->prepare("SELECT COUNT(*) as c FROM " . Booking::tableName . " WHERE id=:id");
        $p->execute([":id" => $id]);
        return $p->fetch(PDO::FETCH_ASSOC)["c"] > 0;
    }
}
