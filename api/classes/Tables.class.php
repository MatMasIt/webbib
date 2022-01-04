<?php
//  lastEdit and created types should be autofilled

/**
 * Tables class, an abstract dictionary
 * 
 * Contains the definition of database tables and related operations
 */
abstract class Tables
{
    /**
     * Static list of tales and their fields, documented
     */
    public static array $tables = [
        "Libri" => [
            "id" => [
                "type" => "ID",
                "compulsory" => true
            ],
            "genre" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "title" =>  [
                "type" => "text",
                "compulsory" => true
            ],
            "authors" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "editor" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "serie" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "language" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "topic" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "isbn" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "publicNotes" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "privateNotes" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "location" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "date" =>  [
                "type" => "UNIX",
                "compulsory" => false
            ],
            "inventory" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "noPages" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "bibliographicLevel" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "dewey" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "publishingCountry" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "dewey" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "editorPlace" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "curator" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "curator" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "translation" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "description" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "identification" =>  [
                "type" => "text",
                "compulsory" => false
            ],
            "isPublic" =>  [
                "type" => "boolean",
                "compulsory" => true,
            ],
            "lastEdit" =>  [
                "type" => "UNIX",
            ]
        ],
        "Users" => [
            "id" => [
                "type" => "ID",
                "compulsory" => true
            ],
            "name" => [
                "type" => "text",
                "compulsory" => true
            ],
            "surname" => [
                "type" => "text",
                "compulsory" => true
            ],
            "birthDate" => [
                "type" => "UNIX",
            ],
            "email" => [
                "type" => "text",
                "compulsory" => true
            ],
            "allowLogin" => [
                "type" => "boolean"
            ],
            "passwordHash" => [
                "type" => "text"
            ],
            "isStaff" => [
                "type" => "boolean"
            ],
            "enabled" => [
                "type" => "boolean"
            ],
            "token" => [
                "type" => "text"
            ],
            "emailToken" => [
                "type" => "text"
            ],
            "lastEdit" => [
                "type" => "UNIX"
            ],
            "created" => [
                "type" => "UNIX"
            ]
        ],
        "Bookings" => [
            "id" => [
                "type" => "ID",
                "compulsory" => true
            ],
            "start" => [
                "type" => "UNIX",
                "compulsory" => true
            ],
            "end" => [
                "type" => "UNIX",
                "compulsory" => true
            ],
            "librarianId" => [
                "type" => "int",
            ],
            "gaveBackDate" => [
                "type" => "UNIX",
            ],
            "privateComment" => [
                "type" => "text",
            ],
            "approved" => [
                "type" => "boolean",
            ],
            "lastEdit" => [
                "type" => "UNIX"
            ],
            "created" => [
                "type" => "UNIX"
            ]
        ]
    ];
    /**
     * Check if Table has column
     * 
     * @param string $table table name
     * @param string $table table name
     * @return bool lookup result
     */
    public static function hasColumn(string $table, string $column): bool
    {
        return in_array($column, array_keys(Tables::$tables[$table]), true);
    }

    /**
     * Paginate an array
     * 
     * @param array $array array to paginate
     * @param array $paginationObject pagination object array
     * @see Query::getPaginationInfo
     * @return array array result
     */
    public static function paginateArray(array $array, array $paginationObject): array
    {
        $pageEnd = $paginationObject["pageStart"] + $paginationObject["noPages"];
        $final = [];
        for ($i = 0; $i < count($paginationObject["noPages"]); $i++) {
            $final["pages"][$i % $paginationObject["pageSize"] + $paginationObject["pageStart"]] = $array[$i];
        }
        $final["pagination"] = $paginationObject;
        $final["pagination"]["pageEnd"] = $pageEnd;
        return $final;
    }
}
