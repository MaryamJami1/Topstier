<template>
    <v-container class="py-0 px-0 px-md-4 mb-4">
        <swiper 
            v-if="banners.length > 1"
            :modules="modules" 
            :slides-per-view="carouselOption.slidesPerView"
            :space-between="carouselOption.spaceBetween"
            :breakpoints="carouselOption.breakpoints"
            :autoplay="carouselOption.autoplay"
            :loop="true"
            class="mySwiper">
            <swiper-slide v-for="(banner, i) in banners" :key="i" class="">
                <banner :loading="loading" :banner="banner"/>
            </swiper-slide>
        </swiper>
        <div v-else class="banner-container">
            <banner v-if="banners.length > 0" :loading="loading" :banner="banners[0]"/>
        </div>
    </v-container>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
import { FreeMode, Autoplay } from "swiper/modules";

// Import Swiper styles
import "swiper/css";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [FreeMode, Autoplay],
        };
    },
    data: () => ({
        loading: true,
        banners: [],
        carouselOption: {
            slidesPerView: 4,
            spaceBetween: 20,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                599: {
                    slidesPerView: 2,
                    spaceBetween: 0
                },
                960: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1264: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
                1904: {
                    slidesPerView: 4,
                    spaceBetween: 20
                },
            }
        },
    }),
    async created(){
        try {
            const res = await this.call_api("get", "setting/home/banner_section_four");
            if (res.data.success) {
                this.banners = res.data.data
            }
        } catch (error) {
            console.error("Failed to load banner section four:", error);
        } finally {
            this.loading = false
        }
    }
}
</script>

<style scoped>
.mySwiper {
    width: 100%;
    height: 100%;
}
.banner-container {
    width: 100%;
}
</style>
