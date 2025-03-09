<?php

namespace App\Livewire;

use Ijpatricio\Mingle\Concerns\InteractsWithMingles;
use Ijpatricio\Mingle\Contracts\HasMingles;
use Livewire\Component;
use Illuminate\Support\Str;

class TodoApp extends Component implements HasMingles
{
    use InteractsWithMingles;

    public function component(): string
    {
        return 'resources/js/vue-todo/index.js';
    }

    public function mingleData(): array
    {
        return [
            'initialTodos' => [
                [
                    'id' => Str::uuid()->toString(),
                    'title' => 'Learn MingleJS',
                    'completed' => true,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'title' => 'Build a Todo app',
                    'completed' => false,
                ],
                [
                    'id' => Str::uuid()->toString(),
                    'title' => 'Deploy to production',
                    'completed' => false,
                ],
            ]
        ];
    }

    public function addTodo($title)
    {
        $newTodo = [
            'id' => Str::uuid()->toString(),
            'title' => $title,
            'completed' => false,
        ];

        // This event will notify the Vue component
        $this->dispatch('todoAdded', todo: $newTodo);
        
        return $newTodo;
    }

    public function updateTodo($id, $completed)
    {
        // In a real app, this would update the database
        $data = [
            'id' => $id,
            'completed' => $completed
        ];
        
        // Send a single data object to the client
        $this->dispatch('todoUpdated', $data);
        
        return $data;
    }

    public function deleteTodo($id)
    {
        // In a real app, this would delete from the database
        $data = ['id' => $id];
        
        // Send a single data object to the client
        $this->dispatch('todoDeleted', $data);
        
        return true;
    }

    public function clearCompleted()
    {
        // In a real app, this would perform a bulk delete
        $this->dispatch('completedCleared');
        
        return true;
    }
}
