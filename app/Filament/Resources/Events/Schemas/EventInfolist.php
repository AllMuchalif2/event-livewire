<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Poster')
                            ->checkFileExistence(false)
                            ->disk('public')
                            ->visibility('public'),

                        TextEntry::make('title')
                            ->label('Judul Event')
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->html()
                            ->columnSpanFull()
                            ->prose(),
                    ])->columns(2),

                Section::make('Detail Jadwal & Lokasi')
                    ->schema([
                        TextEntry::make('event_date')
                            ->label('Tanggal')
                            ->date('d F Y', 'Asia/Jakarta'),

                        TextEntry::make('location')
                            ->label('Lokasi'),

                        TextEntry::make('start_time')
                            ->label('Mulai')
                            ->time('H:i'),

                        TextEntry::make('end_time')
                            ->label('Selesai')
                            ->time('H:i'),
                    ])->columns(4),

                Section::make('Kapasitas & Biaya')
                    ->schema([
                        TextEntry::make('capacity')
                            ->label('Kapasitas')
                            ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.') . ' Peserta'),

                        TextEntry::make('price')
                            ->label('Harga Tiket')
                            ->money('IDR')
                            ->placeholder('Gratis'),

                        TextEntry::make('registration_deadline')
                            ->label('Batas Registrasi')
                            ->date('d F Y', 'Asia/Jakarta'),
                    ])->columns(3),

                Section::make('Meta Data')
                    ->schema([
                        TextEntry::make('creator.name')
                            ->label('Dibuat Oleh'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d F Y H:i', 'Asia/Jakarta'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d F Y H:i', 'Asia/Jakarta'),
                    ])->columns(3)
                    ->collapsed(),
            ]);
    }
}
