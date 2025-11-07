<?php

namespace App\Filament\Resources\Settings;

use App\Filament\Resources\Settings\Pages\ManageSettings;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string | UnitEnum | null $navigationGroup = 'Management';

    protected static ?int $navigationSort = 3;
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('site_name')
                                    ->required()
                                    ->placeholder('My Website')
                                    ->helperText('The name of your website'),
                                Textarea::make('site_description')
                                    ->rows(3)
                                    ->placeholder('A brief description of your website')
                                    ->columnSpanFull(),
                                TextInput::make('site_email')
                                    ->email()
                                    ->placeholder('info@example.com')
                                    ->prefixIcon('heroicon-o-envelope'),
                                TextInput::make('site_phone')
                                    ->placeholder('+1 (555) 123-4567')
                                    ->prefixIcon('heroicon-o-phone'),
                                Textarea::make('site_address')
                                    ->rows(2)
                                    ->placeholder('123 Main St, City, State 12345')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Tabs\Tab::make('Branding')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('logo')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('branding')
                                    ->helperText('Main logo (recommended: 200x50px)'),
                                FileUpload::make('favicon')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('branding')
                                    ->helperText('Browser icon (recommended: 32x32px)'),
                                FileUpload::make('footer_logo')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('branding')
                                    ->helperText('Footer logo (optional)'),
                                Textarea::make('footer_text')
                                    ->rows(3)
                                    ->placeholder('© 2024 Company Name. All rights reserved.')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3),
                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('facebook')
                                    ->url()
                                    ->placeholder('https://facebook.com/yourpage')
                                    ->prefixIcon('heroicon-o-link'),
                                TextInput::make('twitter')
                                    ->url()
                                    ->placeholder('https://twitter.com/yourhandle')
                                    ->prefixIcon('heroicon-o-link'),
                                TextInput::make('instagram')
                                    ->url()
                                    ->placeholder('https://instagram.com/yourprofile')
                                    ->prefixIcon('heroicon-o-link'),
                                TextInput::make('linkedin')
                                    ->url()
                                    ->placeholder('https://linkedin.com/company/yourcompany')
                                    ->prefixIcon('heroicon-o-link'),
                                TextInput::make('youtube')
                                    ->url()
                                    ->placeholder('https://youtube.com/@yourchannel')
                                    ->prefixIcon('heroicon-o-link'),
                                TextInput::make('whatsapp')
                                    ->placeholder('+1234567890')
                                    ->prefixIcon('heroicon-o-phone'),
                                TextInput::make('telegram')
                                    ->placeholder('https://t.me/yourchannel')
                                    ->prefixIcon('heroicon-o-link'),
                            ])
                            ->columns(2),
                        Tabs\Tab::make('SEO & Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Textarea::make('meta_keywords')
                                    ->rows(2)
                                    ->placeholder('keyword1, keyword2, keyword3')
                                    ->helperText('Comma-separated keywords')
                                    ->columnSpanFull(),
                                Textarea::make('meta_description')
                                    ->rows(3)
                                    ->placeholder('A compelling description for search engines')
                                    ->helperText('Recommended: 150-160 characters')
                                    ->columnSpanFull(),
                                Textarea::make('google_analytics')
                                    ->rows(5)
                                    ->placeholder('<!-- Google Analytics Code -->')
                                    ->helperText('Paste your Google Analytics tracking code')
                                    ->columnSpanFull(),
                                Textarea::make('facebook_pixel')
                                    ->rows(5)
                                    ->placeholder('<!-- Facebook Pixel Code -->')
                                    ->helperText('Paste your Facebook Pixel code')
                                    ->columnSpanFull(),
                            ]),
                        Tabs\Tab::make('System')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Toggle::make('maintenance_mode')
                                    ->label('Maintenance Mode')
                                    ->helperText('Enable to show maintenance page to visitors')
                                    ->inline(false),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
