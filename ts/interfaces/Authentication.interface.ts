
export interface Authentication {
    fromToken(token: string): Promise<boolean>;
    login(email: string, password: string): Promise<boolean>;
    logout(): Promise<boolean>;
}