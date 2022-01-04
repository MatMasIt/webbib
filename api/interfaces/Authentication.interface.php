<?php
/**
 * Interface Specifying authentication-related tasks
 */
interface Authentication
{
    public function fromToken(string $token): void;
    public function login(string $email, string $password): bool;
    public function logout(): void;
}
