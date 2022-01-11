"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.Api = void 0;
var Api = /** @class */ (function () {
    function Api() {
        this.version = "1.0.0";
        this.path = "api";
    }
    Api.prototype.send = function (o) {
        return new Promise(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", this.path);
            xhr.onload = resolve;
            xhr.onerror = reject;
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(o));
        }.bind(this));
    };
    return Api;
}());
exports.Api = Api;
//# sourceMappingURL=Api.class.js.map