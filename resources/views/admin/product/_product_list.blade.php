@extends('layouts.app')

@section('title')
    {{ config('app.name', 'Laravel') }} | Products
@endsection

@section('content')
    <section class="content" id="products">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <a href="/products/create" class="btn btn-app float-right">
                        <i class="fas fa-plus"></i> Create
                    </a>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Products</h3>
                        </div>
                        <div class="card-body">
                            <div v-if="products.length === 0">
                                <p>No products found.</p>
                            </div>
                            <div v-else>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Main Image</th>
                                            <th>Variants</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(product, index) in products" :key="product.id">
                                            <td>@{{ index + 1 }}</td>
                                            <td>@{{ product.title }}</td>
                                            <td>@{{ product.description }}</td>
                                            <td>
                                                <img :src="getProductImageURL(product.main_image)" alt="Main Image" style="max-width: 30px;">
                                            </td>
                                            <td>
                                                <ul>
                                                    <li v-for="(variant, index) in product.variants" :key="index">
                                                        Size: @{{ variant.size }}, Color: @{{ variant.color }}
                                                    </li>
                                                </ul>
                                            </td>
                                            <td>
                                                <a class="btn btn-app" :href="'/products/' + product.id + '/edit'">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button class="btn btn-app" @click="removeProduct(product)">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('controller/products.vue.js') }}?v={{ time() }}"></script>
@endsection
