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
    el: '#create-product',
    data: {
        title: '',
        description: '',
        mainImage: null,
        mainImagePreview: null,
        variants: [],
        showVariantSection: false
    },
    methods: {
        submitForm() {
            let formData = new FormData();
            formData.append('title', this.title);
            formData.append('description', this.description);
            formData.append('mainImage', this.mainImage);

            this.variants.forEach((variant, index) => {
                formData.append(`variants[${index}][size]`, variant.size);
                formData.append(`variants[${index}][color]`, variant.color);
            });

            axios.post('/api/products/create', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    showAlert('success', 'Success!', response.data.message);
                    window.location.href = '/products';
                })
                .catch(error => {
                    showAlert('error', 'Error!', error.response.data.message);
                });
        },
        toggleVariantSection() {
            this.showVariantSection = !this.showVariantSection;
        },
        addVariant() {
            this.variants.push({
                size: '',
                color: ''
            });
        },
        removeVariant(index) {
            this.variants.splice(index, 1);
        },
        onFileChange(event) {
            this.mainImage = event.target.files[0];
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.mainImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.mainImagePreview = null;
            }
        },

    }
});
