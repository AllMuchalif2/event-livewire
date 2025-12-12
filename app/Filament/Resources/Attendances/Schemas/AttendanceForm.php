<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('participant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('event_id')
                    ->required()
                    ->numeric(),
                TextInput::make('checked_in_by')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('checked_in_at')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
