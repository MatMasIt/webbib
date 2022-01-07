interface Authentication {
    fromToken(token: string): ApiResult<boolean>;
    login(email: string, password: string): ApiResult<boolean>;
    logout(): ApiResult<boolean>;
}