<?php

namespace WC_BE\Http\Utility\Helpers;

class MessageHandler
{
    private static array $message = [];

    /**
     * Set a message with a type and optional additional options.
     *
     * @param string $type The type of the message (e.g., 'success', 'error').
     * @param string $message The message content.
     * @param array $options Optional additional data to include with the message.
     * @return void
     */
    public static function setMessage(string $type, string $message, array $options = []): void
    {
        self::$message = [
            'type' => $type,
            'message' => $message,
            'options' => $options,
        ];
    }

    /**
     * Retrieve the current message and clear it afterwards.
     *
     * @return array An array containing the message details: type, message, and options.
     */
    public static function getMessage(): array
    {
        $message = self::$message;
        self::clearMessage();
        return $message;
    }

    /**
     * Clear the current message.
     *
     * @return void
     */
    public static function clearMessage(): void
    {
        self::$message = [];
    }
}