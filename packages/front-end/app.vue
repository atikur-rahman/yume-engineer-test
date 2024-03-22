<script setup lang="ts">
import type {User, Product} from '~/models'
import {useUserStore, useProductStore} from '@/stores';
import {fetchProducts} from "~/services/productService";

const {setUser} = useUserStore();
// const {setProducts, setPagination, pagination} = useProductStore();


const [{data: userData}] = await Promise.all([
    useApi<User>('/api/user'),
    fetchProducts()
]);



setUser(userData);


</script>
<template>
    <Header/>
    <ProductList/>
</template>
