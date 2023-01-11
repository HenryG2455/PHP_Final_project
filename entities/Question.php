<?php

class Question implements JsonSerializable
{
    private string $questionID;
    private string $questionText;
    private array $choices; // array of strings
    private int $answer;
    private array $tags; // array of Tag objects.

    public function __construct($questionID, $questionText, $choices, $answer, $tags) {
        $this->questionID = $questionID;
        $this->questionText = $questionText;
        $this->choices = $choices;
        $this->answer = $answer;
        $this->tags = $tags;
    }

    public function getQuestionID(): string
    {
        return $this->questionID;
    }

    public function getQuestionText(): string
    {
        return $this->questionText;
    }

    public function getChoices(): array
    {
        return $this->choices;
    }

    public function getAnswer(): int
    {
        return $this->answer;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}