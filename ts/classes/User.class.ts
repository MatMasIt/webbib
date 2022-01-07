class User implements Authentication, CRUDL, Validation, ObjSerialize {
    version = "1.0.0";
    id: bigint;
    name: string;
    surname: string;
    email: string;
    birthDate: string;
    created: bigint;
    lastEdit: bigint;
    allowLogin: boolean;
    isStaff: boolean;
    sessionToken: string | null;
    api: Api;
    u: User | null;
    constructor(api: Api) {
        this.api = api;
    }
    toObj(): object {
        throw new Error("Method not implemented.");
    }
    fromObj(o: object): void {
        throw new Error("Method not implemented.");
    }
    validate(): boolean {
        throw new Error("Method not implemented.");
    }
    load(id: bigint): void {
        throw new Error("Method not implemented.");
    }
    list(q: Query): ApiResult<object> {
        throw new Error("Method not implemented.");
    }
    create(): ApiResult<object> {
        throw new Error("Method not implemented.");
    }
    save(): ApiResult<object> {
        throw new Error("Method not implemented.");
    }
    delete(): ApiResult<object> {
        throw new Error("Method not implemented.");
    }
    fromToken(token: string): ApiResult<boolean> {
        return new ApiResult<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.me",
                "sessionToken": this.sessionToken
            });
            req.then(function ok(data) {
                this.fromObj(data);
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
    login(email: string, password: string): ApiResult<boolean> {
        return new ApiResult<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.login",
                "email": email,
                "password": password
            });
            req.then(function ok(data) {
                this.fromObj(data);
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
    logout(): ApiResult<boolean> {
        return new ApiResult<boolean>(function (resolve, reject) {
            let req = this.api.send({
                "action": "users.logout",
                "sessionToken": this.sessionToken
            });
            req.then(function ok(data) {
                resolve(true);
            }.bind(this));
            req.then, function no() {
                reject(false);
            }
        }.bind(this));
    }
}