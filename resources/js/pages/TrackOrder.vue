<template>
    <div class="pb-6">
        <v-container>
            <v-row>
                <v-col cols="12" lg="6" class="mx-auto">
                    <h1 class="mb-7 mt-4">{{ $t("track_your_order") }}</h1>
                    <v-form lazy-validation v-on:submit.prevent="trackOrder()">
                        <div class="mb-1 fs-13 fw-500">
                            {{ $t("order_code") }}
                        </div>
                        <v-text-field
                            :placeholder="$t('order_code')"
                            type="text"
                            class="mb-3 text-field"
                            v-model="form.orderCode"
                            :error-messages="orderCodeErrors"
                            hide-details="auto"
                            required
                            variant="plain"
                        ></v-text-field>

                        <!-- <p v-for="error of v$.form.orderCode.$errors" :key="error.$uid" class="text-red" >
              {{error.$message }}
            </p> -->
                        <p v-if="error" class="text-red">
                            {{ error }}
                        </p>

                        <v-btn
                            class="px-16 mb-4"
                            elevation="0"
                            color="primary"
                            @click="trackOrder"
                            :loading="loading"
                            :disabled="loading"
                            >{{ $t("track") }}</v-btn
                        >
                    </v-form>
                </v-col>
                <v-col
                    cols="12"
                    xl="8"
                    lg="10"
                    class="mx-auto"
                    v-if="!is_empty_obj(order)"
                >
                    <Summary :order-details="order" />
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import { useVuelidate } from "@vuelidate/core";
import { email, required } from "@vuelidate/validators";
import Summary from "../components/order/Summary.vue";
import { loadRecaptcha } from "@/utils/loadRecaptcha";
import { useRecaptcha } from "@/utils/useRecaptcha";
import { removeRecaptcha } from "@/utils/removeRecaptcha";

const { verifyRecaptcha } = useRecaptcha();

export default {
    head: {
        title: "Order Tracking Page",
    },
    data: () => ({
        loading: false,
        error: "",
        v$: useVuelidate(),
        form: {
            orderCode: "",
        },
        order: {},
        recaptchaReady: false,
    }),
    components: {
        Summary,
    },
    validations: {
        form: {
            orderCode: {
                required,
            },
        },
    },
    computed: {
       ...mapGetters("app", [
            "generalSettings",
            "appUrl",
            "paymentMethods",
            "offlinePaymentMethods",
        ]),
        ...mapGetters("auth", ["isAuthenticated"]),
        orderCodeErrors() {
            const errors = [];
            if (!this.v$.form.orderCode.$dirty) return errors;
            !this.v$.form.orderCode.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
    },

    async mounted() {
        if(this.generalSettings.google_recaptcha && this.generalSettings.recaptcha_track_order){
            try {
                await loadRecaptcha(import.meta.env.VITE_RECAPTCHA_KEY);
                this.recaptchaReady = true;
                console.log("reCAPTCHA ready!");
            } catch (err) {
                console.error(err);
            }
        }
    },

     beforeUnmount() {
        removeRecaptcha();
    },

    methods: {
        async trackOrder() {
            this.error = null;

            if (this.form.orderCode.trim().length <= 0) {
                this.error = "Value is required";
                return;
            }

            this.loading = true;

            try {
                if(this.generalSettings.google_recaptcha && this.generalSettings.recaptcha_track_order){
                    const response_recapcha = await verifyRecaptcha("track_order");
                    if(!response_recapcha.success){
                        this.error = response_recapcha.message;
                        return;
                    }
                }
              
                const orderRes = await this.call_api(
                    "get",
                    `user/order/${this.form.orderCode}`
                );

                console.log("Order response:", orderRes);

                if (orderRes.data.success) {
                    this.order = orderRes.data.data;
                } else {
                    this.error = orderRes.data.message;
                    this.order = {};
                }
            } catch (e) {
                console.error(e);
                this.error = "Something went wrong!";
            }

            this.loading = false;
        },
    },
};
</script>
