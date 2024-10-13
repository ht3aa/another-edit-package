<?php

namespace App\Traits;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\IconPosition;

trait HasAnotherEdit
{
    public function editAnotherAction()
    {

        if (! $this->displayField) {
            throw new \Exception('You must define a displayField property in your edit page of your resource in order to another edit package to work');
        }

        $modelInstance = new ($this->getModel());

        return Action::make('createAnother')
            ->label(__('system.edit_another'))
            ->icon('heroicon-m-pencil-square')
            ->form([
                Select::make('editAnother')
                    ->label(__('system.choose_record_to_edit'))
                    ->live()
                    ->searchable()
                    ->suffixIcon('heroicon-m-pencil-square')
                    ->options($modelInstance::all()->pluck($this->displayField, $modelInstance->getKeyName()))
                    ->afterStateUpdated(function ($state) {
                        return redirect()->to($this->getResource()::getUrl('edit', ['record' => $state]));
                    }),
            ])

            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalWidth('sm')
            ->keyBindings(['mod+s'])
            ->iconPosition(IconPosition::After)
            ->color('gray');

    }

    protected function getFormActions(): array
    {

        return [
            $this->getSaveFormAction(),
            $this->editAnotherAction(),
            $this->getCancelFormAction(),
        ];
    }
}
