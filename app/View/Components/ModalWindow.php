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
        $this->labels = $this->getLabels($type);
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
                    'cancel_button' => __('admin.cancel_button'),
                    'delete_button' => __('admin.delete_button'),
                    'save_button' => __('admin.save_button'),
                    'confirm_delete' => __('admin.confirm_delete'),

                    'name_label' => __('admin.name_label'),
                    'description_label' => __('admin.description_label'),
                    'logo_placeholder' => __('admin.logo_placeholder')
                ];
            case ModalWindowType::PROJECT:
                return [
                    'create_title' => __('projects.create_title'),
                    'edit_title' => __('projects.edit_title'),
                    'cancel_button' => __('projects.cancel_button'),
                    'delete_button' => __('projects.delete_button'),
                    'save_button' => __('projects.save_button'),
                    'confirm_delete' => __('projects.confirm_delete'),

                    'name_label' => __('projects.name_label'),
                    'description_label' => __('projects.description_label'),
                    'members_label' => __('projects.members_label')
                ];
            case ModalWindowType::TASK:
                return [
                    'create_title' => __('tasks.create_title'),
                    'edit_title' => __('tasks.edit_title'),
                    'cancel_button' => __('tasks.cancel_button'),
                    'delete_button' => __('tasks.delete_button'),
                    'save_button' => __('tasks.save_button'),
                    'confirm_delete' => __('tasks.confirm_delete'),

                    'name_label' => __('tasks.name_label'),
                    'description_label' => __('tasks.description_label'),
                    'assignee_label' => __('tasks.assignee_label'),
                    'priority_label' => __('tasks.priority_label'),
                    'due_date_label' => __('tasks.due_date_label')
                ];
        }
        return [];
    }
}
