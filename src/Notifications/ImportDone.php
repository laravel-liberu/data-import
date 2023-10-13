<?php

namespace LaravelLiberu\DataImport\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use LaravelLiberu\DataImport\Models\Import;

class ImportDone extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Import $import)
    {
    }

    public function via()
    {
        return ['mail', ...Config::get('enso.imports.notifications')];
    }

    public function toBroadcast()
    {
        return (new BroadcastMessage([
            'level' => 'success',
            'title' => $this->title(),
            'body' => $this->filename(),
            'icon' => 'file-excel',
        ]))->onQueue($this->queue);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject($this->subject())
            ->markdown('laravel-enso/data-import::emails.import', [
                'name' => $notifiable->person->appellative
                    ?? $notifiable->person->name,
                'import' => $this->import,
            ]);
    }

    public function toArray()
    {
        return [
            'body' => "{$this->title()}: {$this->filename()}",
            'icon' => 'file-excel',
            'path' => '/import',
        ];
    }

    private function title()
    {
        return __(':type import done', ['type' => $this->import->type()]);
    }

    private function filename()
    {
        return $this->import->file->original_name;
    }

    private function subject()
    {
        $name = Config::get('app.name');

        return "[ {$name} ] {$this->title()}";
    }
}
