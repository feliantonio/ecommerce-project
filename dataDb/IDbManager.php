<?php

interface IDbManager
{
    public function OpenConnection(): ?PDO;
    public function CloseConnection();
    public function IsConnected(): bool;
    public function Delete(string $sql, ?array $ar = null): bool;
    public function Insert(string $sql, ?array $ar = null): bool;
    public function Update(string $sql, ?array $ar = null): bool;
    public function Select(string $sql, ?array $ar = null): ?array;
}

?>