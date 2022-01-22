export interface TokenStorage {
    saveToken(): boolean;
    loadToken(): boolean;
    clearToken(): boolean;
}