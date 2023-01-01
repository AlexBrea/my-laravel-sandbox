<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use App\Models\Project;
use App\Models\TaskTimer;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaskResource\RelationManagers;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Task manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('project_id')
                    ->label('Project')
                    ->options( Project::all()->pluck( 'name', 'id' ) )
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description'),
                DatePicker::make('limit_date')
                    ->displayFormat('d/m/Y'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label( 'Task ID' ),
                Tables\Columns\TextColumn::make('name')
                    ->label( 'Task name' ),
                Tables\Columns\TextColumn::make('description')
                    ->label( 'Task description' ),
                Tables\Columns\TextColumn::make( 'project.name' )
                    ->label( 'Project' ),
                Tables\Columns\TextColumn::make( 'timers.total_time' )
                    ->label( 'Total time' ),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('Start Time')
                    ->color('success')
                    ->icon( 'heroicon-o-play')
                    ->action(function ( Task $record, array $data ): void {
                        // TODO: Fix bug when delete record.
                        $task_id    = $record->getIdFromAction();
                        $task_timer = TaskTimer::find( $task_id );
                        $now        = Carbon::now();

                        // Check if timer exist.
                        if ( ! $task_timer ) {
                            // Create timer.
                            TaskTimer::create([
                                'is_active'  => 1,
                                'task_id'    => $task_id,
                                'total_time' => 0,
                                'task_start' => $now,
                            ]);

                            Notification::make() 
                                ->title( 'Task created and started!' )
                                ->success()
                                ->send(); 

                            return;
                        }

                        if ( $task_timer->is_active ) {
                            $task_timer->is_active = 0;
                            $task_timer->task_end  = $now;

                            // Calculate time.
                            $total_duration = $now->diffInSeconds( $task_timer->task_start );
                            // Save new total time.
                            $task_timer->total_time = $task_timer->total_time + $total_duration;
                            $task_timer->save();

                            Notification::make() 
                                ->title( 'Task stopped!' )
                                ->success()
                                ->send(); 

                            return;
                        }

                        // Update dates.
                        $task_timer->is_active   = 1;
                        $task_timer->task_start  = $now;

                        $task_timer->save();

                        Notification::make() 
                            ->title( 'Task started!' )
                            ->success()
                            ->send(); 
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTasks::route('/'),
        ];
    }    
}
