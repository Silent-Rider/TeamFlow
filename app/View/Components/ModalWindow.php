<?php

namespace App\View\Components;

use App\View\ModalWindowType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalWindow extends Component
{
    public array $labels;
    public function __construct(ModalWindowType $type)
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.modal-window');
    }

    private function getLabels(ModalWindowType $type): array
    {
        switch ($type) {
            case ModalWindowType::COMPANY:
                return [
                    'create_title' => __('admin.create_title'),
                    'edit_title' => __('admin.edit_title'),
                    'name_label' => __('admin.name_label'),
                    'cancel_button' => __('admin.cancel_button'),
                    'save_button' => __('admin.save_button'),
                    'delete_button' => __('admin.delete_button'),
                    'confirm_delete' => __('admin.confirm_delete'),
                    'description_label' => __('admin.description_label'),
                    'logo_placeholder' => __('admin.logo_placeholder')
                ];
            case ModalWindowType::PROJECT:
                return [
                    'create_title' => __('projects.create_title'),
                    'edit_title' => __('projects.edit_title'),
                    'name_label' => __('projects.name_label'),
                    'cancel_button' => __('projects.cancel_button'),
                    'save_button' => __('projects.save_button'),
                    'delete_button' => __('projects.delete_button'),
                    'confirm_delete' => __('projects.confirm_delete'),
                    'description_label' => __('projects.description_label'),
                    'members_label' => __('projects.members_label')
                ];
        }
        return [];
    }
}
