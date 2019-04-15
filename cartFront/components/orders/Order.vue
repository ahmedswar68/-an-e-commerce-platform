<template>
  <tr>
    <td># {{order.id}}</td>
    <td>{{order.created_at}}</td>
    <td>
      <div v-for="variation in products" :key="variation.id">
        <NuxtLink :to="{
        name:'products-slug',
          params:{
            slug:variation.product.slug
          }
        }">
          {{variation.product.name}} ({{variation.name}}) - {{variation.type}}
        </NuxtLink>
      </div>
      <template v-if="moreProducts>0"> and {{moreProducts}} more</template>
    </td>
    <td>{{order.subtotal}}</td>
    <td>
      <component :is="order.status"/>
    </td>
  </tr>
</template>
<script>
  import OrderStatusPending from '@/components/orders/statuses/OrderStatus-pending';
  import OrderStatusFailed from '@/components/orders/statuses/OrderStatus-failed';
  import OrderStatusProcessing from '@/components/orders/statuses/OrderStatus-processing';
  import OrderStatusCompleted from '@/components/orders/statuses/OrderStatus-completed';

  export default {
    components: {
      'pending': OrderStatusPending,
      'failed': OrderStatusFailed,
      'processing': OrderStatusProcessing,
      'completed': OrderStatusCompleted,
    },
    data() {
      return {
        maxProducts: 2
      }
    },
    props: {
      order: {
        required: true,
        type: Object,
      }
    },
    computed: {
      products() {
        return this.order.products.slice(0, this.maxProducts)
      },
      moreProducts() {
        return this.order.products.length - this.maxProducts;
      }
    }
  }
</script>