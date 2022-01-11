"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Query = void 0;
var Query = /** @class */ (function () {
    function Query() {
        this.clauses = [];
        this.cols = [];
        this.sorters = [];
        this.pageSize = 0;
        this.version = "1.0.0";
        this.operatorsMap = [
            "<",
            ">",
            "=",
            "LIKE",
            "!=",
            "SEARCH"
        ];
        this.tableMap = {
            "Libri": {
                "id": {
                    "type": "ID",
                    "compulsory": true
                },
                "genre": {
                    "type": "text",
                    "compulsory": false
                },
                "title": {
                    "type": "text",
                    "compulsory": true
                },
                "authors": {
                    "type": "text",
                    "compulsory": false
                },
                "editor": {
                    "type": "text",
                    "compulsory": false
                },
                "serie": {
                    "type": "text",
                    "compulsory": false
                },
                "language": {
                    "type": "text",
                    "compulsory": false
                },
                "topic": {
                    "type": "text",
                    "compulsory": false
                },
                "isbn": {
                    "type": "text",
                    "compulsory": false
                },
                "publicNotes": {
                    "type": "text",
                    "compulsory": false
                },
                "privateNotes": {
                    "type": "text",
                    "compulsory": false
                },
                "location": {
                    "type": "text",
                    "compulsory": false
                },
                "date": {
                    "type": "UNIX",
                    "compulsory": false
                },
                "inventory": {
                    "type": "text",
                    "compulsory": false
                },
                "noPages": {
                    "type": "text",
                    "compulsory": false
                },
                "bibliographicLevel": {
                    "type": "text",
                    "compulsory": false
                },
                "dewey": {
                    "type": "text",
                    "compulsory": false
                },
                "publishingCountry": {
                    "type": "text",
                    "compulsory": false
                },
                "editorPlace": {
                    "type": "text",
                    "compulsory": false
                },
                "curator": {
                    "type": "text",
                    "compulsory": false
                },
                "translation": {
                    "type": "text",
                    "compulsory": false
                },
                "description": {
                    "type": "text",
                    "compulsory": false
                },
                "identification": {
                    "type": "text",
                    "compulsory": false
                },
                "isPublic": {
                    "type": "boolean",
                    "compulsory": true
                },
                "lastEdit": {
                    "type": "UNIX"
                }
            },
            "Users": {
                "id": {
                    "type": "ID",
                    "compulsory": true
                },
                "name": {
                    "type": "text",
                    "compulsory": true
                },
                "surname": {
                    "type": "text",
                    "compulsory": true
                },
                "birthDate": {
                    "type": "UNIX"
                },
                "email": {
                    "type": "text",
                    "compulsory": true
                },
                "allowLogin": {
                    "type": "boolean"
                },
                "passwordHash": {
                    "type": "text"
                },
                "isStaff": {
                    "type": "boolean"
                },
                "enabled": {
                    "type": "boolean"
                },
                "token": {
                    "type": "text"
                },
                "emailToken": {
                    "type": "text"
                },
                "lastEdit": {
                    "type": "UNIX"
                },
                "created": {
                    "type": "UNIX"
                }
            },
            "Bookings": {
                "id": {
                    "type": "ID",
                    "compulsory": true
                },
                "start": {
                    "type": "UNIX",
                    "compulsory": true
                },
                "end": {
                    "type": "UNIX",
                    "compulsory": true
                },
                "librarianId": {
                    "type": "int"
                },
                "gaveBackDate": {
                    "type": "UNIX"
                },
                "privateComment": {
                    "type": "text"
                },
                "approved": {
                    "type": "boolean"
                },
                "lastEdit": {
                    "type": "UNIX"
                },
                "created": {
                    "type": "UNIX"
                }
            }
        };
    }
    Query.prototype.costructor = function (table) {
        this.tableName = table;
    };
    Query.prototype.hasColumn = function (column) {
        if (Object.keys(this.tableMap).indexOf(this.tableName) < 0)
            return false;
        if (Object.keys(this.tableMap[this.tableName]).indexOf(column) < 0)
            return false;
        return true;
    };
    Query.prototype.toObj = function () {
        return {
            "clauses": this.clauses,
            "cols": this.cols,
            "sorters": this.sorters,
            "pagination": {
                "size": this.pageSize,
                "start": this.pageStart,
                "noPages": this.noPages
            }
        };
    };
    Query.prototype.fromObj = function (o) {
        this.clauses = o["clauses"];
        this.cols = o["columns"];
        this.sorters = o["sorters"];
        this.pageSize = parseInt(o["pagination"]["size"] ? o["pagination"]["size"] : 0);
        this.pageStart = parseInt(o["pagination"]["start"] ? o["pagination"]["start"] : 0);
        this.noPages = parseInt(o["pagination"]["noPages"] ? o["pagination"]["noPages"] : 0);
    };
    Query.prototype.addClause = function (a, operation, b) {
        if (this.operatorsMap.indexOf(operation) == (-1))
            throw new ObjectMismatch();
        this.clauses.push({ "a": a, "operation": operation, "b": b });
    };
    return Query;
}());
exports.Query = Query;
//# sourceMappingURL=Query.class.js.map