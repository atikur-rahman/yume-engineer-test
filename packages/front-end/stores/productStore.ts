import {defineStore} from 'pinia'
import type {Product} from '~/models/product'
import type {Pagination} from "~/models/pagination";

export const useProductStore = defineStore('product', () => {
    const ProductData = ref<Product[]>([])

    const paginationData = ref<Pagination>({
        page: 0,
        pageSize: 15,
        total: 0
    })
    const pagination = computed(() => paginationData.value)

    const products = computed(() => ProductData.value);

    function setProducts(u: Product[]) {
        ProductData.value = [...ProductData.value, ...u];
    }

    const setPagination = (meta: Pagination) => {
        paginationData.value = meta
    }
    return {products, setProducts, setPagination, pagination}
})
