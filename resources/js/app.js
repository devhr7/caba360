import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

import Ripple from "primevue/ripple";
import FocusTrap from "primevue/focustrap";
import StyleClass from "primevue/styleclass";
import AnimateOnScroll from "primevue/animateonscroll";

import ConfirmationService from "primevue/confirmationservice";
import ToastService from "primevue/toastservice";

import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import "primeicons/primeicons.css";

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import Swal from "sweetalert2";

import "primeicons/primeicons.css";


const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .mixin({ methods: { route, Swal } }) // Añade Swal a los métodos globales
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                },
            })
            .directive("ripple", Ripple)
            .directive("focustrap", FocusTrap)
            .directive("styleclass", StyleClass)
            .directive("animateonscroll", AnimateOnScroll)
            .use(ConfirmationService)
            .use(ToastService)
            .use(VueSweetalert2)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
