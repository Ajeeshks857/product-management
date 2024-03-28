function showAlert(icon, title, message) {
    Swal.fire({
        icon: icon,
        title: title,
        text: message,
        showConfirmButton: false,
        timer: 1500
    });
}
new Vue({
    el: '#products',
    data: {
        products: []
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get('/api/products');
                this.products = response.data.data;

            } catch (error) {
                showAlert('error', 'Error!', error.response.data.message);
            }
        },
        getProductImageURL(imagePath) {
            return imagePath ? '/storage/' + imagePath : '';
        },

        async removeProduct(product) {
            try {
                const response = await axios.delete(`/api/products/${product.id}`);
                showAlert('success', 'Success!', response.data.message);
                this.fetchProducts();
            } catch (error) {
                showAlert('error', 'Error!', error.response.data.message);
            }
        },
    },
    mounted() {
        this.fetchProducts();
    }
});
