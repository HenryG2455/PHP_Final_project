<?php

class User implements JsonSerializable
{
    private string $username;
    private string $password;
    private string $permissionLevel;

    public function __construct($username, $password, $permissionLevel)
    {
        $this->username = $username;
        $this->password = $password;
        $this->permissionLevel = $permissionLevel;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param $passwordToTest
     * @return bool
     */
    public function isPasswordCorrect($passwordToTest): bool
    {
        return password_verify($passwordToTest, $this->password);
    }

    /**
     * @return string
     */
    public function getPermissionLevel(): string
    {
        return $this->permissionLevel;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}