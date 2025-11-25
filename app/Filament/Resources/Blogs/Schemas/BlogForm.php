<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Blog Content')
                    ->description('Main blog post content and featured image')
                    ->icon('heroicon-o-document-text')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Post Title')
                            ->placeholder('Enter blog post title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from title')
                            ->prefixIcon('heroicon-o-link')
                            ->columnSpanFull(),
                        MarkdownEditor::make('content')
                            ->label('Content')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                            ])
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->collection('featured_image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ])
                            ->maxSize(2048)
                            ->helperText('Recommended: 1200x675px')
                            ->columnSpanFull(),
                    ]),
                Grid::make(1)
                    ->columnSpan(1)
                    ->schema([
                        Section::make('Author')
                            ->description('Author information')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                TextInput::make('author_name')
                                    ->label('Author Name')
                                    ->placeholder('John Doe')
                                    ->required()
                                    ->maxLength(100),
                                SpatieMediaLibraryFileUpload::make('author_image')
                                    ->label('Author Photo')
                                    ->collection('author_image')
                                    ->image()
                                    ->avatar()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->maxSize(1024)
                                    ->helperText('Square image recommended'),
                            ]),
                        Section::make('Publishing')
                            ->description('Post status and visibility')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                    ])
                                    ->required()
                                    ->default('draft')
                                    ->native(false),
                                Toggle::make('is_featured')
                                    ->label('Featured Post')
                                    ->helperText('Show featured badge')
                                    ->inline(false),
                                DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->native(false)
                                    ->seconds(false),
                            ]),
                    ]),
                Section::make('SEO Settings')
                    ->description('Search engine optimization')
                    ->icon('heroicon-o-magnifying-glass')
                    ->columnSpan(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->placeholder('SEO optimized title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters')
                            ->columnSpanFull(),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->placeholder('Brief description for search engines')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Recommended: 150-160 characters')
                            ->columnSpanFull(),
                        TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->placeholder('keyword1, keyword2, keyword3')
                            ->helperText('Comma-separated keywords')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}
