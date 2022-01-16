"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Book = void 0;
var Book = /** @class */ (function () {
    function Book() {
        this.classN = "Book";
        this.version = "1.0.0";
    }
    Book.prototype.load = function (id) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "books.get",
                "sessionToken": this.u.token ? this.u.token : this.token,
                "id": id
            });
            req.then(function ok(data) {
                this.fromObj(data["data"]);
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    };
    Book.prototype.list = function (q) {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "users.list",
                "sessionToken": this.u.token,
                "query": q.toObj()
            });
            req.then(function ok(data) {
                resolve(data["data"]);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    };
    Book.prototype.create = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "books.create",
                "sessionToken": this.u.token,
                "object": this.toObj()
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    };
    Book.prototype.save = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "books.edit",
                "sessionToken": this.u.token,
                "object": this.toObj()
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    };
    Book.prototype.delete = function () {
        return new Promise(function (resolve, reject) {
            var req = this.api.send({
                "action": "users.remove",
                "sessionToken": this.u.token,
                "id": this.id
            });
            req.then(function ok(data) {
                this.fromObj(data["data"]);
                resolve(true);
            }.bind(this));
            req.catch(function no() {
                reject(false);
            });
        }.bind(this));
    };
    Book.prototype.validate = function () {
        if (!this.title)
            return false;
        if (this.lastEdit < this.created)
            return false;
        return true;
    };
    Book.prototype.toObj = function () {
        return {
            "type": this.classN,
            "version": this.version,
            "id": this.id,
            "data": {
                "genre": this.genre,
                "title": this.title,
                "authors": this.authors,
                "editor": this.editor,
                "serie": this.serie,
                "language": this.language,
                "topic": this.topic,
                "isbn": this.isbn,
                "publicNotes": this.publicNotes,
                "privateNotes": this.privateNotes,
                "location": this.location,
                "date": this.date,
                "inventory": this.inventory,
                "noPages": this.noPages,
                "bibliographicalLevel": this.bibliographicLevel,
                "dewey": this.dewey,
                "publishingCountry": this.publishingCountry,
                "editorPlace": this.editorPlace,
                "curator": this.curator,
                "type": this.type,
                "translation": this.translation,
                "description": this.description,
                "identification": this.identification
            },
            "isPublic": this.isPublic,
            "lastEdit": this.lastEdit,
            "created": this.created
        };
    };
    Book.prototype.fromObj = function (o) {
        if (o["type"] != this.classN || o["version"] != this.version)
            throw new ObjectMismatch();
        this.id = parseInt(o["id"]);
        this.genre = String(o["data"]["genre"]);
        this.title = String(o["data"]["title"]);
        this.authors = String(o["data"]["authors"]);
        this.editor = String(o["data"]["editor"]);
        this.serie = String(o["data"]["serie"]);
        this.language = String(o["data"]["language"]);
        this.topic = String(o["data"]["topic"]);
        this.isbn = String(o["data"]["isbn"]);
        this.publicNotes = String(o["data"]["publicNotes"]);
        this.privateNotes = String(o["data"]["privateNotes"]);
        this.location = String(o["data"]["location"]);
        this.date = String(o["data"]["date"]);
        this.inventory = String(o["data"]["inventory"]);
        this.noPages = String(o["data"]["noPages"]);
        this.bibliographicLevel = String(o["data"]["bibliographicLevel"]);
        this.dewey = String(o["data"]["dewey"]);
        this.publishingCountry = String(o["data"]["publishingCountry"]);
        this.editorPlace = String(o["data"]["editorPlace"]);
        this.curator = String(o["data"]["curator"]);
        this.type = String(o["data"]["type"]);
        this.translation = String(o["data"]["translation"]);
        this.description = String(o["data"]["description"]);
        this.identification = String(o["data"]["identification"]);
        this.isPublic = Boolean(o["data"]["identification"]);
        this.lastEdit = parseInt(o["data"]["identification"]);
        this.created = parseInt(o["data"]["identification"]);
    };
    return Book;
}());
exports.Book = Book;
//# sourceMappingURL=Book.class.js.map