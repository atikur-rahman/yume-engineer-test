<script setup lang="ts">
import {ref} from 'vue'
import {useInfiniteScroll} from '@vueuse/core'
import type {Product} from '~/models'
import {useProductStore} from '@/stores';
import { fetchProducts } from '@/services/productService';

const productsStore = useProductStore();

const autoFetch = ref(true)

const el = ref<HTMLElement | null>(null)

const isReachedEnd = ref(false)

useInfiniteScroll(
    el,
    async () => {
        if (autoFetch.value && !isReachedEnd.value) {
            autoFetch.value = false

            await fetchProducts()
            autoFetch.value = true

            if (productsStore.products.length >= productsStore.pagination.total) {
                isReachedEnd.value = true
            }
        }

    },
    {distance: 250}
)
</script>

<template>
    <div class="bg-red text-white">
        <div class="container mx-auto text-white text-center">
            <div class="text-5xl md:text-7xl md:py-12 hero leading-loose tracking-wide">
                ALL PRODUCTS
            </div>
        </div>
    </div>
    <div class="flex flex-wrap mt-8" >
        <Product v-for="product in productsStore.products" :key="product.id" :product="product"/>
    </div>
    <div v-if="isReachedEnd"><p class="text-center mb-3" >You have reached the end. No more product :) </p></div>
    <div ref="el"></div>
</template>
