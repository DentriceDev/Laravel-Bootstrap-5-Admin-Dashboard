<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UsersTable extends PowerGridComponent
{
    use ActionButton;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('users-data')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
    * PowerGrid datasource.
    *
    * @return Builder<\App\Models\User>
    */
    public function datasource(): Builder
    {
        return User::query()
                ->with('roles:title');
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [
            'roles' => [
                'title',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn('roles', fn(User $model) => '<span class="badge bg-info">'.$model->roles->pluck('title')->implode(', ').'</span>')
            ->addColumn('email_verified_at_formatted', fn (User $model) => $model->email_verified_at? Carbon::parse($model->email_verified_at)->format('d/m/Y H:i:s') : '<span class="badge bg-warning">Unverified</span>')
            ->addColumn('created_at_formatted', fn (User $model) => $model->created_at? Carbon::parse($model->created_at)->format('d/m/Y H:i:s') : 'Null')
            ->addColumn('updated_at_formatted', fn (User $model) => $model->updated_at? Carbon::parse($model->updated_at)->format('d/m/Y H:i:s') : 'Null')
            ->addColumn('deleted_at_formatted', fn (User $model) => $model->deleted_at? Carbon::parse($model->deleted_at)->format('d/m/Y H:i:s') : 'Null');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

     /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->makeInputRange(),

            Column::make('NAME', 'name')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('EMAIL', 'email')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('ROLE', 'roles')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::make('EMAIL VERIFIED AT', 'email_verified_at_formatted', 'email_verified_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

            Column::make('CREATED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

            Column::make('UPDATED AT', 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

            Column::make('DELETED AT', 'deleted_at_formatted', 'deleted_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

        ]
;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

     /**
     * PowerGrid User Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            Button::make('view', 'View')
               ->class('btn btn-sm bg-primary cursor-pointer text-white')
               ->target('_self')
               ->route('users.show', ['user' => 'id']),

            Button::make('edit', 'Edit')
                ->class('btn btn-sm bg-warning cursor-pointer text-white')
                ->target('_self')
                ->route('users.edit', ['user' => 'id']),

            Button::make('destroy', 'Delete')
                ->class('btn btn-sm bg-danger cursor-pointer text-white')
                ->route('users.destroy', ['user' => 'id'])
                ->target('_self')
                ->method('delete')
         ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

     /**
     * PowerGrid User Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($user) => $user->id === 1)
                ->hide(),
        ];
    }
    */
}
