<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ParticipantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('event_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('type'),
                TextEntry::make('payment_amount')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('payment_proof')
                    ->placeholder('-'),
                TextEntry::make('payment_status'),
                TextEntry::make('registration_number'),
                TextEntry::make('qr_code')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('registered_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
