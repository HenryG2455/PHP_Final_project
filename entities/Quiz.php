<?php

class Quiz implements JsonSerializable
{
    private string $quizID;
    private string $quizTitle;
    private array $questions; // array of Question objects
    private array $points; // array of integers

    public function __construct($quizID, $quizTitle, $questions, $points) {
        $this->quizID = $quizID;
        $this->quizTitle = $quizTitle;
        $this->questions = $questions;
        $this->points = $points;
    }

    public function getQuizID(): string
    {
        return $this->quizID;
    }

    public function getQuizTitle(): string
    {
        return $this->quizTitle;
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }

    public function getPoints(): array
    {
        return $this->points;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}