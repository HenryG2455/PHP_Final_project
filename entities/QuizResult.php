<?php

class QuizResult implements JsonSerializable
{
    private string $resultID;
    private Quiz $quiz;
    private User $user; //    private string $username;
    private array $answers; //int[]
    private string $startTime;
    private string $endTime;
    private int $scoreNumerator;
    private int $scoreDenominator;

    public function __construct($resultID, $quiz, $user, $startTime, $endTime, $answers, $scoreNumerator, $scoreDenominator) {
        $this->resultID = $resultID;
        $this->quiz = $quiz;
        $this->user = $user;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->answers = $answers;
        $this->scoreNumerator = $scoreNumerator;
        $this->scoreDenominator = $scoreDenominator;
    }

    public function getResultID(): string
    {
        return $this->resultID;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuizStartTime(): string
    {
        return $this->startTime;
    }

    public function getQuizEndTime(): string
    {
        return $this->endTime;
    }

    public function getUserAnswers(): array
    {
        return $this->answers;
    }

    public function getScoreNumerator(): int
    {
        return $this->scoreNumerator;
    }

    public function getScoreDenominator(): int
    {
        return $this->scoreDenominator;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}