<template>
  <div class="section">
    <div class="container is-fluid">
      <div class="columns is-multiline">
        <div class="column is-3" v-for="product in products" :key="product.slug">
          <product :product="product"></product>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import Product from "@/components/products/Product";
  export default {
    components: {Product},
    data() {
      return {
        products: []
      }
    },
    async asyncData({params, app}) {
      var url = `products?category=${params.slug}`;
      console.log(url);
      let response = await app.$axios.$get(url);
      return {
        products: response.data
      }
    }
  }
</script>