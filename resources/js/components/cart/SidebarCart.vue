<template>
  <div>
    <button
      class="side-cart-btn text-center fw-500 fs-12 d-none d-lg-block"
      @click.stop="updateCartDrawer(true)"
    >
      <span class="d-block side-cart-price-box rounded mt-1 px-1 py-2 lh-1">
        <i class="las la-shopping-cart mr-1" />
        <span>{{ getCartCount }} {{ $t("items") }}</span>
      </span>
      <span class="d-block side-cart-price-box rounded mt-1 px-1 py-2 lh-1">{{ format_price(getCartPrice - getTotalCouponDiscount) }}</span>
    </button>
    <v-navigation-drawer
      class="cart-drawer"
      location="right"
      width="400"
      height="100vh"
      v-model="cartDrawerOpen"
      fixed
      temporary
      hide-overlay
      right
      clipped
      @update:modelValue="updateCartDrawer"
    >

      <cart-for-multi v-if="is_addon_activated('multi_vendor')" />
      <cart-for-single v-else />

    </v-navigation-drawer>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import CartForMulti from "./CartForMulti.vue";
import CartForSingle from "./CartForSingle.vue";
export default {
  components: { CartForMulti, CartForSingle },
  computed: {
    ...mapGetters("cart", [
      "getCartCount",
      "getCartPrice",
      "getTotalCouponDiscount",
    ]),
    ...mapGetters("auth", ["cartDrawerOpen", "currentUser"]),
  },
  methods: {
    ...mapActions("cart", ["fetchCartProducts"]),
    ...mapMutations("auth", ["updateCartDrawer"]),
  },
  created() {
    this.fetchCartProducts();
  },
};
</script>

<style scoped>
.cart-drawer {
  z-index: 610;
}

.side-cart-price-box {
  background-color: #f8f9fa !important;
  color: #000 !important;
}
</style>
