<template>
  <div class="min-h-screen bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden">
      <div class="p-8">
        <div class="flex items-center justify-between mb-8">
          <h1 class="text-3xl font-bold text-gray-900">MingleJS Todo App</h1>
          <div class="text-sm font-medium text-indigo-600">
            Vue + Livewire Integration
          </div>
        </div>

        <!-- Add Todo Form -->
        <form @submit.prevent="addTodo" class="mb-8">
          <div class="flex items-center">
            <input
              v-model="newTodo"
              type="text"
              placeholder="What needs to be done?"
              class="block w-full px-4 py-3 text-base text-gray-900 placeholder-gray-500 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
              required
            />
            <button
              type="submit"
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-r-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Add
            </button>
          </div>
        </form>

        <!-- Filters -->
        <div class="flex items-center justify-between mb-6">
          <div class="flex space-x-4">
            <button
              @click="filter = 'all'"
              :class="[
                'px-3 py-2 rounded-md text-sm font-medium',
                filter === 'all'
                  ? 'bg-indigo-100 text-indigo-700'
                  : 'text-gray-700 hover:bg-gray-100'
              ]"
            >
              All
            </button>
            <button
              @click="filter = 'active'"
              :class="[
                'px-3 py-2 rounded-md text-sm font-medium',
                filter === 'active'
                  ? 'bg-indigo-100 text-indigo-700'
                  : 'text-gray-700 hover:bg-gray-100'
              ]"
            >
              Active
            </button>
            <button
              @click="filter = 'completed'"
              :class="[
                'px-3 py-2 rounded-md text-sm font-medium',
                filter === 'completed'
                  ? 'bg-indigo-100 text-indigo-700'
                  : 'text-gray-700 hover:bg-gray-100'
              ]"
            >
              Completed
            </button>
          </div>
          <button
            @click="clearCompleted"
            class="text-sm text-gray-500 hover:text-gray-700"
            :disabled="!hasCompleted"
            :class="{ 'opacity-50 cursor-not-allowed': !hasCompleted }"
          >
            Clear completed
          </button>
        </div>

        <!-- Todo List -->
        <div v-if="filteredTodos.length > 0" class="space-y-2 mb-6">
          <transition-group name="todo-list" tag="div" class="space-y-2">
            <div
              v-for="todo in filteredTodos"
              :key="todo.id"
              class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200"
              :class="{ 'opacity-75': todo.completed }"
            >
              <input
                type="checkbox"
                :checked="todo.completed"
                @change="toggleTodo(todo)"
                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <span
                class="ml-3 text-gray-900 flex-grow"
                :class="{ 'line-through text-gray-500': todo.completed }"
              >
                {{ todo.title }}
              </span>
              <button
                @click="removeTodo(todo)"
                class="text-gray-400 hover:text-red-500"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>
          </transition-group>
        </div>

        <div v-else class="py-12 text-center text-gray-500">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-12 w-12 mx-auto text-gray-400 mb-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
            />
          </svg>
          <p>No todos to show</p>
        </div>

        <!-- Stats -->
        <div class="pt-6 border-t border-gray-200">
          <div class="flex justify-between text-sm text-gray-600">
            <span>{{ activeTodosCount }} items left</span>
            <div class="text-xs space-x-2">
              <span>🔄 Server sync enabled</span>
              <span>⚡ Vue + Livewire</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Event Log Panel -->
    <div class="max-w-3xl mx-auto mt-8 bg-white rounded-xl shadow-xl overflow-hidden">
      <div class="p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Event Log</h2>
        <div class="bg-gray-50 p-4 rounded-lg max-h-40 overflow-y-auto">
          <div v-if="events.length === 0" class="text-gray-500 text-center py-6">
            No events logged yet. Try interacting with the Todo list.
          </div>
          <div v-else class="space-y-2">
            <div 
              v-for="(event, index) in events" 
              :key="index"
              class="text-sm py-1 border-b border-gray-200 last:border-b-0"
            >
              <span class="text-green-600 font-mono">[{{ event.time }}]</span>
              <span class="ml-2 font-medium">{{ event.type }}:</span>
              <span class="ml-1 text-gray-700">{{ event.message }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
  wire: Object,
  mingleData: Object,
});

// Initialize todos from server data
const todos = ref([]);
const newTodo = ref('');
const filter = ref('all');
const events = ref([]);

// Get initial todos from Livewire
onMounted(() => {
  if (props.mingleData && props.mingleData.initialTodos) {
    todos.value = props.mingleData.initialTodos;
    logEvent('Init', 'Loaded initial todos from server');
  }

  // Set up event listeners
  props.wire.on('todoAdded', (todo) => {
    logEvent('Server Event', `Todo added: ${todo.title}`);
  });

  props.wire.on('todoUpdated', (id, completed) => {
    logEvent('Server Event', `Todo updated: ID ${id}, completed: ${completed}`);
  });

  props.wire.on('todoDeleted', (id) => {
    logEvent('Server Event', `Todo deleted: ID ${id}`);
  });

  props.wire.on('completedCleared', () => {
    logEvent('Server Event', 'Completed todos cleared');
  });
});

// Computed properties
const filteredTodos = computed(() => {
  if (filter.value === 'active') {
    return todos.value.filter(todo => !todo.completed);
  } else if (filter.value === 'completed') {
    return todos.value.filter(todo => todo.completed);
  }
  return todos.value;
});

const activeTodosCount = computed(() => {
  return todos.value.filter(todo => !todo.completed).length;
});

const hasCompleted = computed(() => {
  return todos.value.some(todo => todo.completed);
});

// Methods
function addTodo() {
  if (!newTodo.value.trim()) return;
  
  logEvent('Client Action', `Adding todo: ${newTodo.value}`);
  
  // Call the server method
  props.wire.addTodo(newTodo.value)
    .then(newTodoFromServer => {
      todos.value.push(newTodoFromServer);
      newTodo.value = '';
      logEvent('Client Event', `Added todo locally: ${newTodoFromServer.title}`);
    })
    .catch(error => {
      logEvent('Error', `Failed to add todo: ${error}`);
    });
}

function toggleTodo(todo) {
  const updatedStatus = !todo.completed;
  
  logEvent('Client Action', `Toggling todo: ${todo.title} to ${updatedStatus ? 'completed' : 'active'}`);
  
  // Call the server method
  props.wire.updateTodo(todo.id, updatedStatus)
    .then(response => {
      const todoToUpdate = todos.value.find(t => t.id === todo.id);
      if (todoToUpdate) {
        todoToUpdate.completed = updatedStatus;
      }
      logEvent('Client Event', `Updated todo status locally`);
    })
    .catch(error => {
      logEvent('Error', `Failed to update todo: ${error}`);
    });
}

function removeTodo(todo) {
  logEvent('Client Action', `Removing todo: ${todo.title}`);
  
  // Call the server method
  props.wire.deleteTodo(todo.id)
    .then(response => {
      todos.value = todos.value.filter(t => t.id !== todo.id);
      logEvent('Client Event', `Removed todo locally`);
    })
    .catch(error => {
      logEvent('Error', `Failed to remove todo: ${error}`);
    });
}

function clearCompleted() {
  logEvent('Client Action', 'Clearing all completed todos');
  
  // Call the server method
  props.wire.clearCompleted()
    .then(response => {
      todos.value = todos.value.filter(todo => !todo.completed);
      logEvent('Client Event', 'Removed completed todos locally');
    })
    .catch(error => {
      logEvent('Error', `Failed to clear completed todos: ${error}`);
    });
}

function logEvent(type, message) {
  const now = new Date();
  const time = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
  
  events.value.unshift({
    time,
    type,
    message
  });
  
  // Keep only the last 50 events
  if (events.value.length > 50) {
    events.value = events.value.slice(0, 50);
  }
}
</script>

<style>
.todo-list-enter-active,
.todo-list-leave-active {
  transition: all 0.3s ease;
}
.todo-list-enter-from,
.todo-list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
