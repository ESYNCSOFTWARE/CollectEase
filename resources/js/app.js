import '../css/app.css';
import './bootstrap';

import Tippy from '@commonComponents/Tippy.vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@layouts/GuestLayout.vue';
import MasterLayout from '@layouts/MasterLayout.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Company';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob('./pages/**/*.vue'),
        );
        page.then((module) => {
            if (name.startsWith('Guest')) {
                module.default.layout = GuestLayout;

                return;
            }

            module.default.layout = MasterLayout;
        });
        return page;
    },

    setup({ el, App, props, plugin }) {
        const permissions = props.auth?.permissions || [];

        return createApp({
            render: () => h(App, { ...props, permissions }),
        })
            .component('Head', Head)
            .component('Link', Link)
            .component('Tippy', Tippy)
            .component('VueDatePicker', VueDatePicker)
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
