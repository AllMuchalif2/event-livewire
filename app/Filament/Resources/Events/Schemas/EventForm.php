<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Form; // Changed from Filament\Schemas\Schema
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TimePicker;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Event')
                    ->description('Detail informasi event/seminar')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Event')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                            ]),

                        FileUpload::make('image')
                            ->label('Poster Event')
                            ->image()
                            ->directory('events')
                            ->visibility('public')
                            ->disk('public')
                            ->getUploadedFileNameForStorageUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file, $get) {
                                $title = $get('title') ?: 'event';
                                $filename = \Illuminate\Support\Str::slug($title) . '-' . time();
                                return $filename . '.' . $file->getClientOriginalExtension();
                            })
                            ->imageEditor()
                            ->columnSpanFull()
                            ->helperText('Upload gambar poster event (maksimal 2MB)'),
                    ])->columns(2),

                Section::make('Jadwal & Lokasi')
                    ->schema([
                        DatePicker::make('event_date')
                            ->label('Tanggal Event')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now())
                            ->helperText('Tanggal pelaksanaan event'),

                        TimePicker::make('start_time')
                            ->label('Jam Mulai')
                            ->required()
                            ->native(false)
                            ->seconds(false),

                        TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->required()
                            ->native(false)
                            ->seconds(false)
                            ->after('start_time'),

                        TextInput::make('location')
                            ->label('Lokasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Aula Utama Gedung A'),
                    ])->columns(2),

                Section::make('Kapasitas & Pembayaran')
                    ->schema([
                        TextInput::make('capacity')
                            ->label('Kapasitas Peserta')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(100)
                            ->suffix('orang')
                            ->helperText('Maksimal jumlah peserta yang bisa mengikuti'),

                        TextInput::make('price')
                            ->label('Harga Tiket')
                            ->numeric()
                            ->prefix('Rp')
                            ->nullable()
                            ->placeholder('Kosongkan jika gratis')
                            ->helperText('Masukkan harga tiket. Kosongkan jika event gratis'),
                    ])->columns(2),

                Section::make('Registrasi')
                    ->schema([
                        DatePicker::make('registration_deadline')
                            ->label('Batas Waktu Registrasi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->addDay())
                            ->before('event_date')
                            ->helperText('Batas akhir peserta dapat mendaftar'),
                    ])->columns(1),

                // Hidden field - auto filled dengan user yang sedang login
                \Filament\Forms\Components\Hidden::make('created_by')
                    ->default(fn() => auth()->id()),
            ]);
    }
}
