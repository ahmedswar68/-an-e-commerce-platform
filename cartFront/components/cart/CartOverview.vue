<template>
  <table class="is-hoverable is-fullwidth">
    <tbody>
    <CartOverviewProduct
      v-for="product in products"
      :key="product.id"
      :product="product"
    ></CartOverviewProduct>
    <tr>
      <td></td>
      <td></td>
      <td>
        <p class="has-text-weight-bold">Subtotal</p>
      </td>
      <td>{{ subtotal }}</td>
      <td></td>
    </tr>
    <slot name="rows"></slot>
    </tbody>
  </table>
</template>
<script>
  import {mapGetters} from 'vuex';
  import CartOverviewProduct from '@/components/cart/CartOverviewProduct';

  export default {
    computed: {
      ...mapGetters({
        products: 'cart/products',
        total: 'cart/total',
        subtotal: 'cart/subtotal',
      })
    },
    components: {
      CartOverviewProduct
    }
  }
</script>