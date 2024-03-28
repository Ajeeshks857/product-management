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
    el: '#edit-product',
    data: {
        title: '',
        description: '',
        mainImage: null,
        mainImagePreview: null,
        showVariantSection: true,
        product: {
            variants: [],
        },
        fileInputRef: null
    },
    methods: {
        async fetchProduct() {
            try {
                const response = await axios.get(`/api/products/item/${window.product_id}`);
                this.product = response.data.product;
                this.title = this.product.title;
                this.description = this.product.description;
                this.mainImagePreview = this.getProductImageURL(this.product.main_image);
                this.variants = this.product.variants || [];
            } catch (error) {
                console.error('Error fetching product:', error);
            }
        },
        async submitForm() {
            try {
                let formData = new FormData();
                formData.append('title', this.title);
                formData.append('description', this.description);
                formData.append('mainImage', this.mainImage);
                this.variants.forEach((variant, index) => {
                    formData.append(`variants[${index}][id]`, variant.id !== undefined ? variant.id : '');
                    formData.append(`variants[${index}][size]`, variant.size);
                    formData.append(`variants[${index}][color]`, variant.color);
                });

                const response = await axios.post(`/api/products/update/${window.product_id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                showAlert('success', 'Success', response.data.message);
                 window.location.href = '/products';
            } catch (error) {
                showAlert('error', 'Error', 'An error occurred while updating the product.');
                console.error('Error updating product:', error);
            }
        },

        removeVariant(index) {
            this.variants.splice(index, 1);
        },

        addVariant() {
           this.variants.push({
               size: '',
               color: ''
           });

           if (this.fileInputRef) {
               this.fileInputRef.value = '';
           }
        },

        toggleVariantSection() {
            this.showVariantSection = !this.showVariantSection;
        },

        onFileChange(event) {
            this.mainImage = event.target.files[0];
            this.mainImagePreview = URL.createObjectURL(this.mainImage);
        },

        getProductImageURL(imagePath) {
            return imagePath ? `/storage/${imagePath}` : null;
        }
    },
    mounted() {
        this.fetchProduct();

    }
});
