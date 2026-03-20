<?php

namespace App\Services;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class FilamentCrudNotificationService
{
    public function notifyCreated(Model $record): void
    {
        $this->send('success', $this->getModelLabel($record) . ' created successfully.', $this->getRecordIdentity($record));
    }

    public function notifyUpdated(Model $record): void
    {
        $this->send('success', $this->getModelLabel($record) . ' updated successfully.', $this->getRecordIdentity($record));
    }

    public function notifyDeleted(Model $record): void
    {
        $this->send('danger', $this->getModelLabel($record) . ' deleted successfully.', $this->getRecordIdentity($record));
    }

    private function send(string $color, string $title, ?string $body = null): void
    {
        $notification = Notification::make()->title($title);

        if ($color === 'danger') {
            $notification->danger();
        } else {
            $notification->success();
        }

        if (! blank($body)) {
            $notification->body($body);
        }

        $notification->send();
    }

    private function getModelLabel(Model $record): string
    {
        return class_basename($record);
    }

    private function getRecordIdentity(Model $record): ?string
    {
        $identityFields = ['name', 'email', 'plate_number', 'registration_number', 'agreement_id', 'make_model'];

        foreach ($identityFields as $field) {
            $value = $record->getAttribute($field);

            if (filled($value)) {
                return (string) $value;
            }
        }

        if (filled($record->getKey())) {
            return 'ID: ' . (string) $record->getKey();
        }

        return null;
    }
}

