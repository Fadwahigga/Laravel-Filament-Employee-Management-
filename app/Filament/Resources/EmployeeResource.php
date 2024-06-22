<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use App\Models\Employee;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('first_name')->required(),
                    TextInput::make('last_name')->required(),
                    TextInput::make('address')->required(),
                    TextInput::make('zip_code')->required(),
                    Select::make('country_id')
                        ->label('Country')
                        ->options(Country::all()->pluck('name', 'id'))
                        ->searchable()->required(),
                    Select::make('state_id')
                        ->label('State')
                        ->options(State::all()->pluck('name', 'id'))
                        ->searchable()->required(),
                    Select::make('city_id')
                        ->label('City')
                        ->options(City::all()->pluck('name', 'id'))
                        ->searchable()->required(),
                    Select::make('department_id')
                        ->label('Department')
                        ->options(Department::all()->pluck('name', 'id'))
                        ->searchable()->required(),
                    DatePicker::make('birth_date')->required(),
                    DatePicker::make('date_hired')->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('First Name')->sortable()->searchable(),
                TextColumn::make('last_name')->label('Last Name')->sortable()->searchable(),
                TextColumn::make('address')->label('Address')->sortable()->searchable(),
                TextColumn::make('zip_code')->label('Zip Code')->sortable()->searchable(),
                TextColumn::make('country.name')->label('Country')->sortable()->searchable(),
                TextColumn::make('state.name')->label('State')->sortable()->searchable(),
                TextColumn::make('city.name')->label('City')->sortable()->searchable(),
                TextColumn::make('department.name')->label('Department')->sortable()->searchable(),
                TextColumn::make('birth_date')->label('Birth Date')->sortable()->searchable(),
                TextColumn::make('date_hired')->label('Date Hired')->sortable()->searchable(),
            ])
            ->filters([
                SelectFilter::make('Department')
                    ->relationship('department', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
