<template v-if="galleryImgaes && galleryImgaes.length > 0">
    <div>
        <template v-if="isLoading">
            <v-skeleton-loader
                type="image"
                class="mb-4"
                height="500"
            ></v-skeleton-loader>
            <v-row class="gutters-10">
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="100"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="100"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="100"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="100"
                    ></v-skeleton-loader>
                </v-col>
            </v-row>
        </template>
        <div class="product-gallery-container" v-show="!isLoading">
            <template v-if="galleryImgaes && galleryImgaes.length > 0">
                <!-- Main Gallery Swiper -->
                <div class="main-gallery-square-wrap border-thin rounded overflow-hidden">
                    <swiper
                        :style="{
                            '--swiper-navigation-color': '#fff',
                            '--swiper-pagination-color': '#fff',
                        }"
                        :thumbs="{ swiper: thumbsSwiper }"
                        :spaceBetween="10"
                        :navigation="true"
                        :modules="modules"
                        class="main-gallery-swiper"
                    >
                        <swiper-slide v-if="hasVideo" :key="sanitizedVideo">
                            <div class="gallery-media-wrapper">
                                <video
                                    :poster="sanitizedPoster || (selectedVariation.image ? selectedVariation.image : galleryImgaes[0])"
                                    controls
                                    preload="metadata"
                                    class="gallery-media-object"
                                >
                                    <source :src="sanitizedVideo" :type="videoMimeType" />
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </swiper-slide>
                        <swiper-slide v-for="(photo, i) in galleryImgaes" :key="i">
                            <ProductImageZoom :imageSrc="selectedVariation.image ? selectedVariation.image : photo"/>
                        </swiper-slide>
                    </swiper>
                </div>

                <!-- Thumbnail Swiper -->
                <swiper
                    @swiper="setThumbsSwiper"
                    :spaceBetween="10"
                    :slidesPerView="4"
                    :freeMode="true"
                    :watchSlidesProgress="true"
                    :modules="modules"
                    class="thumbnail-swiper mt-3"
                >
                    <swiper-slide v-if="hasVideo" class="thumbnail-slide">
                        <div class="thumb-media-wrapper border-thin rounded position-relative overflow-hidden">
                            <img v-if="sanitizedPoster" :src="sanitizedPoster" class="thumb-media-img" />
                            <div class="thumb-play-overlay">
                                <i class="las la-play-circle"></i>
                            </div>
                        </div>
                    </swiper-slide>
                    <swiper-slide v-for="(photo, i) in galleryImgaes" :key="i" class="thumbnail-slide">
                        <div class="thumb-media-wrapper border-thin rounded overflow-hidden">
                            <img :src="photo" class="thumb-media-img" :alt="'Product image ' + (i + 1)" />
                        </div>
                    </swiper-slide>
                </swiper>
            </template>
        </div>
    </div>
</template>

<script>
import { ref, computed } from "vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// zoom
import ProductImageZoom from '../../components/ProductImageZoom.vue';

// import required modules
import { FreeMode, Navigation, Thumbs } from "swiper/modules";
export default {
    props: {
        isLoading: { type: Boolean, default: true },
        galleryImgaes: { type: Array, required: true, default: () => [] },
        selectedVariation: { type: Object, default: () => {} },
        video: { type: String, default: null },
        videoPoster: { type: String, default: null },
    },
    components: {
        Swiper,
        SwiperSlide,
        ProductImageZoom
    },
    setup(props) {
        const thumbsSwiper = ref(null);

        const setThumbsSwiper = (swiper) => {
            thumbsSwiper.value = swiper;
        };

        const sanitize = (val) => {
            if (!val) return null;
            try {
                return String(val).trim().replace(/^[`'"]+|[`'"]+$/g, "");
            } catch {
                return val;
            }
        };
        const sanitizedVideo = computed(() => sanitize(props.video));
        const sanitizedPoster = computed(() => sanitize(props.videoPoster));
        const hasVideo = computed(() => !!sanitizedVideo.value);
        const videoMimeType = computed(() => {
            const url = sanitizedVideo.value || '';
            if (url.toLowerCase().includes('.webm')) return 'video/webm';
            if (url.toLowerCase().includes('.ogg') || url.toLowerCase().includes('.ogv')) return 'video/ogg';
            if (url.toLowerCase().includes('.mov')) return 'video/quicktime';
            if (url.toLowerCase().includes('.avi')) return 'video/avi';
            if (url.toLowerCase().includes('.wmv')) return 'video/x-ms-wmv';
            if (url.toLowerCase().includes('.flv')) return 'video/x-flv';
            if (url.toLowerCase().includes('.mkv')) return 'video/x-matroska';
            return 'video/mp4'; // default fallback
        });

        return {
            thumbsSwiper,
            setThumbsSwiper,
            modules: [FreeMode, Navigation, Thumbs],
            sanitizedVideo,
            sanitizedPoster,
            hasVideo,
            videoMimeType,
        };
    },
};
</script>

<style scoped>
/* Gallery Container */
.product-gallery-container {
    width: 100%;
}

/* 1:1 square wrapper — forces true square regardless of Swiper JS height */
.main-gallery-square-wrap {
    position: relative;
    width: 100%;
    height: 0;
    padding-top: 100%;
    background: #fff;
    overflow: hidden;
}

/* Main Gallery Swiper fills the square wrapper absolutely */
.main-gallery-swiper {
    position: absolute !important;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
}

:deep(.main-gallery-swiper .swiper-slide) {
    height: 100% !important;
    width: 100% !important;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
}

/* Media Wrapper (Video & Images same size) */
.gallery-media-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}

.gallery-media-object {
    width: 100%;
    height: 100%;
    object-fit: cover;
    max-height: 100%;
}

.gallery-media-wrapper video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Thumbnail Swiper */
.thumbnail-swiper {
    width: 100%;
    box-sizing: border-box;
    padding: 0;
}

.thumbnail-swiper .swiper-slide {
    width: 25%;
    opacity: 0.5;
    cursor: pointer;
    transition: opacity 0.3s ease;
    /* Force 1:1 aspect ratio for each thumbnail slide */
    aspect-ratio: 1 / 1;
    height: auto !important;
}

.thumbnail-swiper .swiper-slide-thumb-active {
    opacity: 1;
}

.thumbnail-swiper .swiper-slide:hover {
    opacity: 0.8;
}

/* Thumbnail Media */
.thumbnail-slide {
    display: block;
    width: 100%;
    height: 100%;
}

.thumb-media-wrapper {
    width: 100%;
    /* Enforce 1:1 square box */
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    overflow: hidden;
}

.thumb-media-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

/* Video Thumbnail Overlay */
.thumb-media-wrapper {
    position: relative;
}

.thumb-play-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.thumb-play-overlay i {
    font-size: 20px;
    color: #3490f3;
}

/* Swiper Navigation */
.main-gallery-swiper .swiper-button-next,
.main-gallery-swiper .swiper-button-prev {
    color: #fff;
    background: rgba(0, 0, 0, 0.3);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: background 0.3s ease;
}

.main-gallery-swiper .swiper-button-next:hover,
.main-gallery-swiper .swiper-button-prev:hover {
    background: rgba(0, 0, 0, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
    .gallery-media-object {
        max-height: 100%;
    }
}

@media (max-width: 576px) {
    .gallery-media-object {
        max-height: 100%;
    }
    
    .thumb-play-overlay {
        width: 32px;
        height: 32px;
    }
    
    .thumb-play-overlay i {
        font-size: 16px;
    }
}
</style>
