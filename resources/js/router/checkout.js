let Checkout = () => import("../pages/Checkout.vue");
let GuestCheckout = () => import("../pages/GuestCheckout.vue");
let OrderConfirmed = () => import("../pages/OrderConfirmed.vue");

export default [
    {
        path: "/checkout",
        component: Checkout,
        name: "Checkout",
        meta: { requiresAuth: true },
    },
    {
        path: "/guest-checkout",
        component: GuestCheckout,
        name: "GuestCheckout",
        meta: { requiresAuth: false },
    },
    {
        path: "/order-confirmed",
        component: OrderConfirmed,
        name: "OrderConfirmed",
        meta: { requiresAuth: false },
    },
];
