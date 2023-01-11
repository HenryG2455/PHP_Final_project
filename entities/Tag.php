<?php

class Tag implements JsonSerializable
{
    private int $tagID;
    private string $tagName;
    private string $tagCategory;

    public function __construct($tagID, $tagName, $tagCategory) {
        $this->tagID = $tagID;
        $this->tagName = $tagName;
        $this->tagCategory = $tagCategory;
    }
    public function getTagID(): int
    {
        return $this->tagID;
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getTagCategory(): string
    {
        return $this->tagCategory;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}