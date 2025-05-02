<?php

namespace App\Logging;

use Monolog\Processor\ProcessorInterface;
use Monolog\LogRecord;

class TrimLogProcessor
{
    /**
     * The maximum length of the log message.
     */
    protected int $maxLength;

    /**
     * Constructor.
     *
     * @param int $maxLength
     */
    public function __construct(int $maxLength = 1000)
    {
        $this->maxLength = $maxLength;
    }

    /**
     * Trims the log message, context, and extra fields if they exceed the maximum length.
     *
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $trimmedMessage = $this->trimString((string) $record->message);
        $trimmedContext = $this->trimData($record->context);
        $trimmedExtra = $this->trimData($record->extra);

        return $record
            ->with(message: $trimmedMessage)
            ->with(context: $trimmedContext)
            ->with(extra: $trimmedExtra);
    }

    /**
     * Recursively trims data in arrays, objects, and strings.
     *
     * @param mixed $data
     * @return mixed
     */
    protected function trimData($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'trimData'], $data);
        } elseif (is_string($data)) {
            return $this->trimString($data);
        } elseif (is_object($data)) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
            return $this->trimString($json ?: 'JSON_ENCODE_ERROR');
        }

        return $data;
    }

    /**
     * Trims a string if it exceeds the maximum length.
     *
     * @param string $string
     * @return string
     */
    protected function trimString(string $string): string
    {
        return strlen($string) > $this->maxLength
            ? substr($string, 0, $this->maxLength) . '... [truncated]'
            : $string;
    }
} 