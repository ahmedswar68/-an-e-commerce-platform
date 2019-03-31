<template>
  <div class="section">
    <div class="container is-fluid">
      <div class="columns is-fullwidth is-full">
        <div class="column is-three-quarters">
          <ShippingAddress :addresses="addresses"/>

          <article class="message">
            <div class="message-body">
              <h1 class="title is-5">Payment</h1>
            </div>
          </article>

          <article class="message">
            <div class="message-body">
              <h1 class="title is-5">
                Shipping
              </h1>
              <div class="select is-fullwidth">
                <select>
                  <option>
                    Royal Mail 1st Class
                  </option>
                </select>
              </div>
            </div>
          </article>

          <article class="message" v-if="products.length">
            <div class="message-body">
              <h1 class="title is-5">
                Cart summary
              </h1>
              <CartOverview>
                <template slot="rows">
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="has-text-weight-bold">Shipping</td>
                    <td>EGP 0.00</td>
                    <td></td>
                    <td></td>
                  </tr>
                </template>
              </CartOverview>
            </div>
          </article>

          <article class="message">
            <div class="message-body">
              <button :disabled="empty" class="button is-info is-fullwidth is-medium">
                Place order
              </button>
            </div>
          </article>
        </div>
        <div class="column is-one-quarter">
          <article class="message">
            <div class="message-body">
              <button :disabled="empty" class="button is-info is-fullwidth is-medium">
                Place order
              </button>
            </div>
          </article>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import {mapGetters} from 'vuex';
  import CartOverview from '@/components/cart/CartOverview';
  import ShippingAddress from '@/components/checkout/addresses/ShippingAddress';

  export default {
    data() {
      return {
        addresses: []
      }
    },
    components: {
      CartOverview, ShippingAddress
    },
    computed: {
      ...mapGetters({
        empty: 'cart/empty',
        total: 'cart/total',
        products: 'cart/products',
      })
    },
    async asyncData({app}) {
      let addresses = await app.$axios.$get('addresses');
      return {
        addresses: addresses.data
      }
    }
  }
</script>