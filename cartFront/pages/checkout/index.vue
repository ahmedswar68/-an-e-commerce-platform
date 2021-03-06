<template>
  <div class="section">
    <div class="container is-fluid">
      <div class="columns is-fullwidth is-full">
        <div class="column is-three-quarters">
          <ShippingAddress
            :addresses="addresses"
            v-model="form.address_id"
          />
          <PaymentMethod
            :payment-methods="paymentMethods"
            v-model="form.payment_method_id"
          />



          <article class="message" v-if="shippingMethodId">
            <div class="message-body">
              <h1 class="title is-5">
                Shipping
              </h1>
              <div class="select is-fullwidth">
                <select v-model="shippingMethodId">
                  <option v-for="shipping in shippingMethods" :key="shipping.id" :value="shipping.id">
                    {{shipping.name}} ({{ shipping.price }})
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
                <template slot="rows" v-if="shippingMethodId">
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="has-text-weight-bold">Shipping</td>
                    <td>{{shipping.price}}</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td class="has-text-weight-bold">Total</td>
                    <td>{{total}}</td>
                    <td></td>
                  </tr>
                </template>
              </CartOverview>
            </div>
          </article>

          <article class="message">
            <div class="message-body">
              <button :disabled="empty|| submitting"
                      @click.prevent="order"
                      class="button is-info is-fullwidth is-medium">
                Place order
              </button>
            </div>
          </article>
        </div>
        <div class="column is-one-quarter">
          <article class="message">
            <div class="message-body">
              <button :disabled="empty || submitting"
                      @click.prevent="order"
                      class="button is-info is-fullwidth is-medium">
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
  import {mapGetters, mapActions} from 'vuex';
  import CartOverview from '@/components/cart/CartOverview';
  import ShippingAddress from '@/components/checkout/addresses/ShippingAddress';
  import PaymentMethod from '@/components/checkout/paymentMethods/PaymentMethod';

  export default {
    data() {
      return {
        submitting: false,
        addresses: [],
        paymentMethods: [],
        shippingMethods: [],
        form: {
          address_id: null,
          payment_method_id: null,
        }
      }
    },
    watch: {
      'form.address_id'(addressId) {
        console.log(addressId);
        this.getShippingMethodsForAddress(addressId).then(() => {
          this.setShipping(this.shippingMethods[0])
        })
      },
      shippingMethodId() {
        this.getCart()
      }
    },
    middleware:[
      'redirectIfGuest'
    ],
    components: {
      CartOverview, ShippingAddress,PaymentMethod
    },
    computed: {
      ...mapGetters({
        empty: 'cart/empty',
        total: 'cart/total',
        products: 'cart/products',
        shipping: 'cart/shipping',
      }),
      shippingMethodId: {
        get() {
          return this.shipping ? this.shipping.id : ''
        },
        set(shippingMethodId) {
          this.setShipping(this.shippingMethods.find(s => s.id === shippingMethodId))
        }
      },
    },
    methods: {
      ...mapActions({
        setShipping: 'cart/setShipping',
        getCart: 'cart/getCart',
        flash: 'alert/flash',
      }),
      async order() {
        this.submitting = true;

        try {
          await this.$axios.$post('orders', {
            ...this.form,
            shipping_method_id: this.shippingMethodId
          });
          await this.getCart();

          this.$router.replace({
            name: 'orders'
          })
        } catch (e) {
          this.flash(e.response.data.message);
          this.getCart();

        }
      },
      async getShippingMethodsForAddress(addressId) {
        let response = await this.$axios.$get('addresses/' + addressId + '/shipping');
        console.log(response);

        this.shippingMethods = response.data;
        return response;
      }
    },
    async asyncData({app}) {
      let addresses = await app.$axios.$get('addresses');
      let paymentMethods = await app.$axios.$get('payment-methods');
      return {
        addresses: addresses.data,
        paymentMethods: paymentMethods.data
      }
    }
  }
</script>