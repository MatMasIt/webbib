import { ObjSerialize } from "../interfaces/ObjSerialize.interface";
import { Validation } from "../interfaces/Validation.interface";

export class Query implements ObjSerialize {
    clauses = [];
    cols = [];
    sorters = [];
    pageStart: number;
    noPages: number;
    pageSize = 0;
    version = "1.0.0";
    operatorsMap = [
        "<",
        ">",
        "=",
        "LIKE",
        "!=",
        "SEARCH"
    ];
    tableName: string;
    tableMap = {
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
    costructor(table: string) {
        this.tableName = table;
    }
    hasColumn(column: string): boolean {
        if (Object.keys(this.tableMap).indexOf(this.tableName) < 0) return false;
        if (Object.keys(this.tableMap[this.tableName]).indexOf(column) < 0) return false;
        return true;
    }
    toObj(): object {
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
    }
    fromObj(o: object): void {
        this.clauses = o["clauses"];
        this.cols = o["columns"];
        this.sorters = o["sorters"];
        this.pageSize = parseInt(o["pagination"]["size"] ? o["pagination"]["size"] : 0);
        this.pageStart = parseInt(o["pagination"]["start"] ? o["pagination"]["start"] : 0);
        this.noPages = parseInt(o["pagination"]["noPages"] ? o["pagination"]["noPages"] : 0);
    }
    addClause(a, operation: string, b): void {
        if (this.operatorsMap.indexOf(operation) == (-1)) throw new ObjectMismatch();
        this.clauses.push({ "a": a, "operation": operation, "b": b });
    }

}