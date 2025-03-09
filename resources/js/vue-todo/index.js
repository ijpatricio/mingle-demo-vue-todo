import { createMingle } from '@mingle/mingleVue';
import TodoApp from './TodoApp.vue';

createMingle('resources/js/vue-todo/index.js', ({ createApp, props, el, wire, mingleId, wireId, mingleData }) => {
    const app = createApp(TodoApp, props);
    app.mount(el);
    return true;
});
