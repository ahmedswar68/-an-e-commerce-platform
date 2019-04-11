export const state = () => ({
  categories: [{name: 'Iphone',slug:'iphone', children: []}]
  // categories: []
});
export const getters = {
  categories(state) {
    return state.categories
  }
};
export const mutations = {
  SET_CATEGORIES(state, categories) {
    state.categories = categories
  }
};
export const actions = {
  async nuxtServerInit({commit, dispatch}) {
    let response = await this.$axios.$get('categories');
    commit('SET_CATEGORIES', response.data);
    await dispatch('cart/getCart');
    if (this.$auth.loggedIn) {
      await dispatch('cart/getCart');
    }
  }
};