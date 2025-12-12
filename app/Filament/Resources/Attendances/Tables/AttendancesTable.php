<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Attendance; // Ensure model import if likely needed later or for type hint correctness logic


class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant.user.username')
                    ->label('NIM / ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('participant.name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('checked_in_at')
                    ->label('Waktu Check-in')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('status_kehadiran')
                    ->label('Ketepatan Waktu')
                    ->badge()
                    ->state(function ($record) {
                        return $record->isLate() ? 'Terlambat' : 'Tepat Waktu';
                    })
                    ->color(fn($state) => $state === 'Terlambat' ? 'danger' : 'success'),

                TextColumn::make('checkedInBy.name')
                    ->label('Dicheck-in Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('checked_in_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->label('Event')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // Read only mostly, maybe delete if mistake
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data kehadiran')
            ->emptyStateDescription('Data akan muncul saat peserta melakukan scan QR.');
    }
}
