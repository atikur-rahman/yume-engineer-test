<script setup lang="ts">
import type { Product } from '~/models'
const props = defineProps({
    product: {
        type: Object as PropType<Product>,
        required: true
    }
})
</script>

<template>
    <div class="mb-4 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 p-2">
        <div class="cursor-pointer bg-white rounded-lg shadow-lg hover:shadow w-full">
            <div class="p-6">
                <div class="flex justify-end mb-4">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-[#FF3334] mr-2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10-5.522 0-10-4.477-10-10 0-5.522 4.478-10 10-10zm0 2c4.418 0 8 3.582 8 8s-3.582 8-8 8-8-3.582-8-8 3.582-8 8-8zM12 6v4l3 1"></path>
                        </svg>
                        <span class="text-[#FF3334] font-semibold">10hrs</span>
                    </div>
                </div>

                <div class="py-2">

                    <div class="flex items-center">
                        <img class="w-20 h-20 mr-4" :src="`https://cataas.com/cat?c=${product.id}`" alt="Product Image">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">{{ product.name }}</h3>
                            <p class="text-gray-600">Product Weight</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-gray-700">from <span class="text-[40px] text-[#FF3334] font-bold">${{ product.price }}</span>&nbsp;<sub class="text-red-500">/carton</sub></p>
                    </div>

                    <div class="mt-4">
                        <div class="flex justify-between">
                            <div class="text-left">
                                <p class="text-gray-600 mt-2">BBD</p>
                                <p class="text-gray-600 mt-2">Qty Available</p>
                                <p class="text-gray-600 mt-2">Min Order</p>
                                <p class="text-gray-600 mt-2">State</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 font-semibold mt-2">[DD/MM/YYYY]</p>
                                <p class="text-gray-600 mt-2">[xx] cartons</p>
                                <p class="text-gray-600 mt-2">[xx] cartons</p>
                                <p class="text-gray-600 mt-2">[State]</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-4">
                    <button class="w-full bg-[#FF3334] hover:bg-red-500 text-white font-bold py-3 px-4 rounded">Place an Offer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
