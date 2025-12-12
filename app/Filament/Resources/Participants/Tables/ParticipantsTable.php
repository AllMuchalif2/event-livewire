<?php

namespace App\Filament\Resources\Participants\Tables;

use App\Models\Participant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.username')
                    ->label('NIM / ID Peserta')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->description(fn($record) => $record->type === 'mahasiswa' ? 'Mahasiswa' : 'Umum'),

                TextColumn::make('name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->event->title),

                TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'verified' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'verified' => 'Lunas',
                        'pending' => 'Verifikasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->icon(fn($state) => match ($state) {
                        'verified' => 'heroicon-m-check-circle',
                        'pending' => 'heroicon-m-clock',
                        'rejected' => 'heroicon-m-x-circle',
                        default => null,
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('attendance.checked_in_at')
                    ->label('Kehadiran')
                    ->placeholder('Belum Hadir')
                    ->dateTime('H:i')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->label('Filter Event')
                    ->searchable()
                    ->preload(),

                \Filament\Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'mahasiswa' => 'Mahasiswa',
                        'umum' => 'Umum',
                    ])
                    ->label('Tipe Peserta'),

                \Filament\Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Lunas',
                        'rejected' => 'Ditolak',
                    ])
                    ->label('Status Pembayaran'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                \Filament\Actions\Action::make('verifyPayment')
                    ->label('Verifikasi')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(Participant $record) => $record->payment_status === 'pending')
                    ->action(function (Participant $record) {
                        $record->update([
                            'payment_status' => 'verified',
                            'status' => 'confirmed'
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Diverifikasi')
                            ->success()
                            ->send();
                    }),
                \Filament\Actions\Action::make('rejectPayment')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn(Participant $record) => $record->payment_status === 'pending')
                    ->action(function (Participant $record) {
                        $record->update([
                            'payment_status' => 'rejected',
                            'status' => 'cancelled'
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Ditolak')
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('verifySelected')
                        ->label('Verifikasi Terpilih')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each->update([
                                'payment_status' => 'verified',
                                'status' => 'confirmed'
                            ]);
                        }),
                ]),
            ])
            ->emptyStateHeading('Belum ada peserta')
            ->emptyStateDescription('Peserta yang mendaftar akan muncul di sini.');
    }
}
