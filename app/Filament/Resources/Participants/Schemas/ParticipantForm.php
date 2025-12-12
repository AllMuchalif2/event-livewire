<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('event_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('umum'),
                TextInput::make('payment_amount')
                    ->numeric(),
                TextInput::make('payment_proof'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('registration_number')
                    ->required(),
                Textarea::make('qr_code')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('confirmed'),
                DateTimePicker::make('registered_at')
                    ->required(),
            ]);
    }
}
