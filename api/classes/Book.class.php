<?php

/**
 *  Book class
 * 
 *  Handles manipulating Book elements
 */
class Book implements CRUDL, ObjSerialize, Validation
{
    /**
     * Class version
     */
    public const version = "1.0.0";
    /**
     * Table name associated with the current class
     */
    public const tableName = "Libri";
    /**
     * `PDO` database object
     */
    private PDO $pdo;
    /**
     * User issuing the request
     */
    private User $u;
    /**
     * Book id
     */
    public int $id;
    /**
     * Book last edit date
     */
    public int $lastEdit;
    /**
     * Book creation date
     */
    public int $created;
    /**
     * Book genre
     */
    public string $genre;
    /**
     * Book title
     */
    public string $title;
    /**
     * Book author
     */
    public string $authors;
    /**
     * Book editor
     */
    public string $editor;
    /**
     * The serie to which the book belongs to
     */
    public string $serie;
    /**
     * The language the book is written in
     */
    public string $language;
    /**
     * The topic of the book
     */
    public string $topic;
    /**
     * The book isbn
     */
    public string $isbn;
    /**
     * Public notes displayed to everyone
     */
    public string $publicNotes;
    /**
     * Private notes visible to all librarians
     */
    public string $privateNotes;
    /**
     * Book location in the library
     */
    public string $location;
    /**
     * Book publication date
     */
    public string $date;
    /**
     * Inventory data
     */
    public string $inventory;
    /**
     * Number of book pages of the Book
     */
    public string $noPages;
    /**
     * Books bibliographic level
     */
    public string $bibliographicLevel;
    /**
     * Dewey classification of the book
     */
    public string $dewey;
    /**
     * Country of book publishing
     */
    public string $publishingCountry;
    /**
     * Where the book editor is situated
     */
    public string $editorPlace;
    /**
     * Book curator
     */
    public string $curator;
    /**
     * Book type
     * 
     * For example
     * 
     * * Essay
     * * Fiction
     * * Catalogue
     */
    public string $type;
    /**
     * Translator and data about the translation
     */
    public string $translation;
    /**
     * Description of the book
     */
    public string $description;
    /**
     * Book identification data
     */
    public string $identification;
    /**
     * Is book public
     */
    public bool $isPublic;
    public function __construct(PDO $pdo, User $u)
    {
        $this->pdo = $pdo;
        $this->u = $u;
    }
    /**
     * List Books with query
     *
     * Obtain book list by query
     *
     * @param Query $q Query data
     *
     * @return ApiResult
     */
    public function list(Query $q): ApiResult
    {
        $q->setTableName(Book::tableName);
        $final = [];
        $paInfo = $q->getPaginationInfo();
        foreach ($q->execute() as $l) {
            if (!$l["isPublic"] && !$this->u->isStaff) continue;
            $to = [
                "type" => (string) get_class($this),
                "version" => (string) Book::version,
                "data" => [],
                "isPublic" => (bool) $l["isPublic"],
                "lastEdit" => (int) $l["lastEdit"],
                "created" => (int) $l["created"]
            ];
            if (in_array("id", array_keys($l))) $to["data"]["id"] = (int) $l["id"];
            if (in_array("genre", array_keys($l))) $to["data"]["genre"] = (string) $l["genre"];
            if (in_array("title", array_keys($l))) $to["data"]["title"] = (string) $l["title"];
            if (in_array("authors", array_keys($l))) $to["data"]["authors"] = (int) $l["authors"];
            if (in_array("editor", array_keys($l))) $to["data"]["editor"] = (string) $l["editor"];
            if (in_array("serie", array_keys($l))) $to["data"]["serie"] = (string) $l["serie"];
            if (in_array("language", array_keys($l))) $to["data"]["language"] = (string) $l["language"];
            if (in_array("topic", array_keys($l))) $to["data"]["topic"] = (string) $l["topic"];
            if (in_array("isbn", array_keys($l))) $to["data"]["isbn"] = (string) $l["isbn"];
            if (in_array("publicNotes", array_keys($l))) $to["data"]["publicNotes"] = (string) $l["publicNotes"];
            if (in_array("privateNotes", array_keys($l))) $to["data"]["privateNotes"] = (string) $l["privateNotes"];
            if (in_array("location", array_keys($l))) $to["data"]["location"] = (string) $l["location"];
            if (in_array("date", array_keys($l))) $to["data"]["date"] = (string) $l["date"];
            if (in_array("inventory", array_keys($l))) $to["data"]["inventory"] = (string) $l["inventory"];
            if (in_array("noPages", array_keys($l))) $to["data"]["noPages"] = (string) $l["noPages"];
            if (in_array("bibliographicalLevel", array_keys($l))) $to["data"]["bibliographicalLevel"] = (string) $l["bibliographicalLevel"];
            if (in_array("dewey", array_keys($l))) $to["data"]["dewey"] = (string) $l["dewey"];
            if (in_array("curator", array_keys($l))) $to["data"]["curator"] = (string) $l["curator"];
            if (in_array("type", array_keys($l))) $to["data"]["type"] = (string) $l["type"];
            if (in_array("translation", array_keys($l))) $to["data"]["translation"] = (string) $l["translation"];
            if (in_array("description", array_keys($l))) $to["data"]["description"] = (string) $l["description"];
            if (in_array("identification", array_keys($l))) $to["data"]["identification"] = (string) $l["identification"];

            $final[] = $to;
        }
        if ($paInfo["pageSize"] !== 0) $final = Tables::paginateArray($final, $paInfo);
        $ar = new ApiResult();
        $ar->data($final);
        return $ar;
    }
    /**
     * Load Book by id
     *
     * Obtain book by provided id
     *
     * @param int $id Book id
     *
     * @return ApiResult
     */
    public function load(int $id): void
    {
        $this->id = $id;
        $s = $this->pdo->prepare("SELECT * FROM " . Book::tableName . " WHERE id=:id");
        $s->execute([":id" => $this->id]);
        $r = $s->fetch(PDO::FETCH_ASSOC);
        $a = new ApiResult();
        if (!$r || (!$this->u->isStaff && $r["isPublic"] == 0)) {
            $a->error(QError::NOT_FOUND);
            $a->send();
        } else {
            $this->id = (int) $r["id"];
            $this->genre = (string) $r["genre"];
            $this->title = (string) $r["title"];
            $this->authors = (string) $r["authors"];
            $this->editor = (string) $r["editor"];
            $this->serie = (string) $r["serie"];
            $this->language = (string) $r["language"];
            $this->topic = (string) $r["topic"];
            $this->isbn = (string) $r["isbn"];
            $this->publicNotes = (string) $r["publicNotes"];
            $this->privateNotes = (string) $r["privateNotes"];
            $this->location = (string) $r["location"];
            $this->date = (string) $r["date"];
            $this->inventory = (string) $r["inventory"];
            $this->noPages = (string) $r["noPages"];
            $this->bibliographicalLevel = (string) $r["bibliographicalLevel"];
            $this->dewey = (string) $r["dewey"];
            $this->publishingCountry = (string) $r["publishingCountry"];
            $this->editorPlace =  (string) $r["editorPlace"];
            $this->curator = (string) $r["curator"];
            $this->type = (string) $r["type"];
            $this->translation = (string) $r["translation"];
            $this->description =  (string) $r["description"];
            $this->identification = (string) $r["identification"];
            $this->isPublic = (bool) $r["isPublic"];
            $this->lastEdit = (int) $r["lastEdit"];
            $this->created = (int) $r["created"];
        }
    }
    /**
     * Create Book
     *
     * Create Book and return `ApiResult`
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
        $s = $this->pdo->prepare("INSERT INTO " . Book::tableName . "(genre,title,authors,editor,serie,language,topic,isbn,publicNotes,privateNotes,location,date,inventory,noPages,bigliographicLevel,dewey,publishingCountry,editorPlace,curator,type,translation,description,identification,isPublic,lastEdit,created) VALUES(:genre,:title,:authors,:editor,:serie,:language,:topic,:isbn,:publicNotes,:privateNotes,:location,:date,:inventory,:noPages,:bigliographicLevel,:dewey,:publishingCountry,:editorPlace,:curator,:type,:translation,:description,:identification,:isPublic,:lastEdit,:created)");
        $s->execute([
            ":genre" => $this->genre,
            ":title" => $this->title,
            ":authors" => $this->authors,
            ":editor" => $this->editor,
            ":serie" => $this->serie,
            ":language" => $this->language,
            ":topic" => $this->topic,
            ":isbn" => $this->isbn,
            ":publicNotes" => $this->publicNotes,
            ":privateNotes" => $this->privateNotes,
            ":location" => $this->location,
            ":date" => $this->date,
            ":inventory" => $this->inventory,
            ":noPages" => $this->noPages,
            ":bibliographicalLevel" => $this->bibliographicalLevel,
            ":dewey" => $this->dewey,
            ":publishingCountry" => $this->publishingCountry,
            ":editorPlace" => $this->editorPlace,
            ":curator" => $this->curator,
            ":type" => $this->type,
            ":translation" => $this->translation,
            ":description" => $this->description,
            ":identification" => $this->identification,
            ":isPublic" => (int) $this->isPublic,
            ":lastEdit" => $this->lastEdit,
            ":created" => $this->created
        ]);
        $a->data(["id" => (int)$this->pdo->lastInsertId()]);
        return $a;
    }
    /**
     * Save current book data
     *
     *
     * @return ApiResult
     */
    public function save(): ApiResult
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
        $this->lastEdit = time();
        $s = $this->pdo->prepare("UPDATE " . Book::tableName . " SET genre=:genre, title=:title, authors=:authors, editor=:editor, serie=:serie, language=:language, topic=:topic, isbn=:isbn, publicNotes=:publicNotes, privateNotes=:privateNotes, location=:location, date=:date, inventory=:inventory, noPages=:noPages, bigliographicLevel=:bibliographicLevel, dewey=:dewey, publishingCountry=:publishingCountry, editorPlace=:editorPlace, curator=:curator, type=:type, translation=:translation, description=:description, identification=:identification, isPublic=:isPublic, lastEdit=:lastEdit WHERE id=:id");
        $s->execute([
            ":id" => $this->id,
            ":genre" => $this->genre,
            ":title" => $this->title,
            ":authors" => $this->authors,
            ":editor" => $this->editor,
            ":serie" => $this->serie,
            ":language" => $this->language,
            ":topic" => $this->topic,
            ":isbn" => $this->isbn,
            ":publicNotes" => $this->publicNotes,
            ":privateNotes" => $this->privateNotes,
            ":location" => $this->location,
            ":date" => $this->date,
            ":inventory" => $this->inventory,
            ":noPages" => $this->noPages,
            ":bibliographicalLevel" => $this->bibliographicalLevel,
            ":dewey" => $this->dewey,
            ":publishingCountry" => $this->publishingCountry,
            ":editorPlace" => $this->editorPlace,
            ":curator" => $this->curator,
            ":type" => $this->type,
            ":translation" => $this->translation,
            ":description" => $this->description,
            ":identification" => $this->identification,
            ":isPublic" => (int) $this->isPublic,
            ":lastEdit" => $this->lastEdit
        ]);
        $a->data([]);
        return $a;
    }
    /**
     * Delete Book
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
        $s = $this->pdo->prepare("DELETE FROM " . Book::tableName . " WHERE id=:id");
        $s->execute([":id" => $this->id]);
        $a->data([]);
        return $a;
    }

    /**
     * Validate Book
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->titolo)) return false;
        if ($this->lastEdit < $this->created) return false;
    }
    /**
     * Convert book to array object
     *
     * @return array The Object
     */
    public function toObj(): array
    {
        return [
            "type" => get_class($this),
            "version" => Book::version,
            "id" => $this->id,
            "data" => [
                "genre" => $this->genre,
                "title" => $this->title,
                "authors" => $this->authors,
                "editor" => $this->editor,
                "serie" => $this->serie,
                "language" => $this->language,
                "topic" => $this->topic,
                "isbn" => $this->isbn,
                "publicNotes" => $this->publicNotes,
                "privateNotes" => $this->privateNotes,
                "location" => $this->location,
                "date" => $this->date,
                "inventory" => $this->inventory,
                "noPages" => $this->noPages,
                "bibliographicalLevel" => $this->bibliographicLevel,
                "dewey" => $this->dewey,
                "publishingCountry" => $this->publishingCountry,
                "editorPlace" => $this->editorPlace,
                "curator" => $this->curator,
                "type" => $this->type,
                "translation" => $this->translation,
                "description" => $this->description,
                "identification" => $this->identification
            ],
            "isPublic" => $this->isPublic,
            "lastEdit" => $this->lastEdit,
            "created" => $this->created
        ];
    }

    /**
     * Convert array object to Book
     *
     * @param array $object The object
     * @return void
     */
    public function fromObj(array $object): void
    {
        if ($object["type"] != get_class($this) || $object["version"] != Book::version) throw new ObjectMismatch();
        $this->id = (int) $object["id"];
        $this->genre = (string) $object["data"]["genre"];
        $this->title = (string) $object["data"]["title"];
        $this->authors = (string) $object["data"]["authors"];
        $this->editor = (string) $object["data"]["editor"];
        $this->serie = (string) $object["data"]["serie"];
        $this->language = (string) $object["data"]["language"];
        $this->topic = (string) $object["data"]["topic"];
        $this->isbn = (string) $object["data"]["isbn"];
        $this->publicNotes = (string) $object["data"]["publicNotes"];
        $this->privateNotes = (string) $object["data"]["privateNotes"];
        $this->location = (string) $object["data"]["location"];
        $this->date = (int) $object["data"]["date"];
        $this->inventory = (string) $object["data"]["inventory"];
        $this->noPages = (string) $object["data"]["noPages"];
        $this->bibliographicalLevel = (string) $object["data"]["bibliographicalLevel"];
        $this->dewey = (string) $object["data"]["dewey"];
        $this->publishingCountry = (string) $object["data"]["publishingCountry"];
        $this->editorPlace =  (string) $object["data"]["editorPlace"];
        $this->curator = (string) $object["data"]["curator"];
        $this->type = (string) $object["data"]["type"];
        $this->translation = (string) $object["data"]["translation"];
        $this->description =  (string) $object["data"]["description"];
        $this->identification = (string) $object["data"]["identification"];
        $this->isPublic = (string) $object["isPublic"];
        $this->lastEdit = (string) $object["lastEdit"];
        $this->created = (string) $object["created"];
    }
    /**
     * Check if Book exists
     * static
     * @param PDO $pdo PDO php object
     * @return bool lookup result
     */
    public static function exists(PDO $pdo, int $id): bool
    {
        $p = $pdo->prepare("SELECT COUNT(*) as c FROM " . Book::tableName . " WHERE id=:id");
        $p->execute([":id" => $id]);
        return $p->fetch(PDO::FETCH_ASSOC)["c"] > 0;
    }
}
