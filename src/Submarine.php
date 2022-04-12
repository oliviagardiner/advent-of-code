<?php

namespace App;

use App\InputReader\InputReaderInterface;
use App\Exceptions\NotFileException;
use App\Exceptions\IncorrectExtensionException;
use App\Exceptions\InvalidCommandException;
use App\Exceptions\InvalidNavigationValueException;
use App\Exceptions\FailedNavigationException;

class Submarine
{
    const COMMAND_SEPARATOR = ' ';
    const VALID_COMMANDS = ['forward', 'down', 'up'];

    private int $depthPosition = 0;
    private int $horizontalPosition = 0;
    private array $course;

    /**
     * @throws NotFileException
     * @throws IncorrectExtensionException
     */
    public function __construct(
        private InputReaderInterface $reader
    )
    {
        $this->course = $this->reader->readLines();
    }

    /**
     * @throws FailedNavigationException
     */
    public function navigate()
    {
        foreach ($this->course as $key => $line) {
            try {
                $command = $this->extractCommand($line);
                $command = $this->validateCommand($command);
                $this->changePosition($command[0], $command[1]);
            } catch (InvalidCommandException|InvalidNavigationValueException $e) {
                throw new FailedNavigationException($e->getMessage());
            }
        }
    }

    /**
     * @throws InvalidCommandException
     * @throws InvalidNavigationValueException
     */
    public function extractCommand(string $line): array
    {
        $line = explode(self::COMMAND_SEPARATOR, $line);
        return [trim($line[0]), (int)trim($line[1])];
    }

    public function notValidCommand(string $command): bool
    {
        return !in_array($command, self::VALID_COMMANDS);
    }

    public function notValidNavigationValue(int $value): bool
    {
        return $value < 0;
    }

    /**
     * @throws InvalidCommandException
     * @throws InvalidNavigationValueException
     */
    public function validateCommand(array $line): array
    {
        list($command, $value) = $line;

        if ($this->notValidCommand($command)) {
            throw new InvalidCommandException('Command not recognized: '.$command);
        }
        if ($this->notValidNavigationValue($value)) {
            throw new InvalidNavigationValueException('Navigation value can only be a positive whole number, received: '.$value);
        }

        return $line;
    }

    public function changePosition(string $command, int $value): void
    {
        match($command) {
            'forward' => $this->moveHorizontally($value),
            'down' => $this->increaseDepth($value),
            'up' => $this->decreaseDepth($value),
            default => throw new InvalidCommandException('Command not recognized: '.$command)
        };
    }

    public function increaseDepth(int $value): void
    {
        $this->depthPosition += abs($value);
    }

    public function decreaseDepth(int $value): void
    {
        $this->depthPosition -= abs($value);
    }

    public function moveHorizontally(int $value): void
    {
        $this->horizontalPosition += abs($value);
    }

    public function calculatePosition(): int
    {
        return $this->depthPosition * $this->horizontalPosition;
    }
}