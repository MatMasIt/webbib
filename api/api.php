<?php
require("interfaces/ObjSerialize.interface.php");
require("interfaces/CRUDL.interface.php");
require("exceptions/ObjectMismatch.exception.class.php");
require("exceptions/OutOfDatabaseSpecification.exception.class.php");
require("exceptions/UUIDInvalid.exception.class.php");
require("classes/UUID.class.php");
require("classes/Query.class.php");
require("classes/Tables.class.php");
require("classes/PermisssionCensor.class.php");
require("classes/Utils.class.php");
require("classes/User.class.php");
require("classes/Book.class.php");
require("classes/Booking.class.php");
$data = json_decode(file_get_contents("php://input"), true);
$dbh = new PDO('sqlite:db.sqlite3');
$u = new User($dbh, null);
if ($data["action"] != "users.login") {
    $u->fromToken($data["sessionToken"]);
}
switch ($data["action"]) {
    case "books.list":
        $b = new Book($dbh, $u);
        $q = new Query();
        $q->fromObj($data["query"]);
        $a = $b->list($q);
        $a->send();
        break;
    case "books.get":
        $b = new Book($dbh, $u);
        $b->load($data["id"]);
        $o = $b->toObj();
        $a = new ApiResult();
        $a->data($o);
        $a->send();
        break;
    case "books.create":
        $b = new Book($dbh, $u);
        $b->fromObj($data["object"]);
        $a = $b->create();
        $a->send();
        break;
    case "books.edit";
        $b = new Book($dbh, $u);
        $b->load((int) $data["object"]["id"]);
        if ($b->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $b->fromObj($data["object"]);
        $a = $b->save();
        $a->send();
        break;
    case "books.remove":
        $b = new Book($dbh, $u);
        $b->load((int) $data["object"]["id"]);
        if ($b->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $b->fromObj($data["object"]);
        $a = $b->save();
        $a->send();
        break;
    case "bookings.list":
        $b = new Booking($dbh, $u);
        $q = new Query();
        $q->fromObj($data["query"]);
        $a = $b->list($q);
        $a->send();
        break;
    case "bookings.create":
        $b = new Booking($dbh, $u);
        $b->fromObj($data["object"]);
        $a = $b->create();
        $a->send();
        break;
    case "bookings.edit":
        $b = new Booking($dbh, $u);
        $b->load((int) $data["object"]["id"]);
        if ($b->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $b->fromObj($data["object"]);
        $a = $b->save();
        $a->send();
        break;
    case "bookings.remove":
        $b = new Booking($dbh, $u);
        $b->load((int) $data["object"]["id"]);
        if ($b->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $b->fromObj($data["object"]);
        $a = $b->save();
        $a->send();
        break;
    case "users.login":
        $uf = new User($dbh, $u);
        $uf->login($data["email"], $data["password"]);
        $o = $uf->toObj();
        $a = new ApiResult();
        $a->data($o);
        $a->send();
        break;
    case "users.logout":
        $u->logout();
        break;
    case "users.list":
        $uf = new User($dbh, $u);
        $q = new Query();
        $q->fromObj($data["query"]);
        $a = $uf->list($q);
        $a->send();
        break;
    case "users.get":
        $uf = new User($this->pdo, $u);
        $uf->load((int) $data["id"]);
        $o = $uf->toObj();
        $a = new ApiResult();
        $a->data($o);
        $a->send();
        break;
    case "users.create":
        $uf = new User($this->pdo, $u);
        $uf->fromObj($data["object"]);
        $a = $uf->create();
        $a->send();
        break;
    case "users.edit":
        $uf = new User($dbh, $u);
        $uf->load((int) $data["object"]["id"]);
        if ($uf->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $uf->fromObj($data["object"]);
        $a = $uf->save();
        $a->send();
        break;
    case "users.remove":
        $uf = new User($dbh, $u);
        $uf->load((int) $data["object"]["id"]);
        if ($uf->lastEdit > (int)$data["object"]["lastEdit"]) {
            $a = new ApiResult();
            $a->error(QError::OLDER_LOCAL);
            $a->send();
        }
        $uf->fromObj($data["object"]);
        $a = $uf->save();
        $a->send();
        break;
}
