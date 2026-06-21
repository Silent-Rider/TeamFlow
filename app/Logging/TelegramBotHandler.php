<?php

namespace App\Logging;

use Illuminate\Support\Facades\Log;
use Monolog\Handler\TelegramBotHandler as DefaultTelegramBotHandler;
use Monolog\LogRecord;
use Throwable;

class TelegramBotHandler extends DefaultTelegramBotHandler
{
    protected function write(LogRecord $record): void
    {
        try {
            parent::write($record);
        } catch (Throwable $e) {
            Log::channel('single')->warning(
                '[telegram-log-failed] Не удалось отправить уведомление в Telegram',
                ['telegram_error' => $e->getMessage()]
            );
        }
    }
}
