import {useProductStore} from "~/stores";
import type {Product} from "~/models";




export const fetchProducts = async () => {
    const {setProducts, setPagination, pagination} = useProductStore();

    const {data: {value: ProductResponse}} = await useApi<Product>(
        '/api/products',
        {query: {page: pagination.page + 1, per_page: pagination.pageSize}}
    );

    const meta = ProductResponse.data.meta;
    const productData = ProductResponse.data.data;

    setProducts(productData);
    setPagination({page: meta.current_page, pageSize: meta.per_page, total: meta.total});
}

export default {fetchProducts}
