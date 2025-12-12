<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'upcoming' => 'success',
                        'ongoing' => 'warning',
                        'completed' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->location),

                TextColumn::make('participants_count')
                    ->label('Peserta')
                    ->counts('participants')
                    ->badge()
                    ->color('info')
                    ->suffix(fn($record) => ' / ' . $record->capacity)
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->placeholder('GRATIS')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->sortable(),

                TextColumn::make('registration_deadline')
                    ->label('Batas Registrasi')
                    ->date('d M Y')
                    ->sortable()
                    ->description(
                        fn($record) =>
                        $record->isRegistrationOpen()
                        ? '✓ Terbuka'
                        : '✗ Tutup'
                    ),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'upcoming' => 'success',
                        'ongoing' => 'warning',
                        'completed' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'upcoming' => 'Akan Datang',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        default => $state,
                    }),

                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Event')
                    ->options([
                        'upcoming' => 'Akan Datang',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                    ])
                    ->attribute('status'),

                TernaryFilter::make('is_free')
                    ->label('Event Gratis')
                    ->nullable()
                    ->trueLabel('Hanya Gratis')
                    ->falseLabel('Hanya Berbayar')
                    ->queries(
                        true: fn($query) => $query->whereNull('price')->orWhere('price', 0),
                        false: fn($query) => $query->whereNotNull('price')->where('price', '>', 0),
                    ),

                SelectFilter::make('month')
                    ->label('Bulan Event')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->query(function ($query, $state) {
                        if ($state['value']) {
                            return $query->whereMonth('event_date', $state['value']);
                        }
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
                EditAction::make()
                    ->label('Edit'),
                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada event')
            ->emptyStateDescription('Buat event pertama Anda untuk memulai.')
            ->emptyStateIcon('heroicon-o-calendar');
    }
}
