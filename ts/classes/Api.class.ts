class Api {
    version = "1.0.0";
    path = "api";
    constructor() {

    }
    send(o: object): Promise<object> {
        return new Promise<object>(function (resolve, reject) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", this.path);
            xhr.onload = resolve;
            xhr.onerror = reject;
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.send(JSON.stringify(o));
        }.bind(this));
    }
}