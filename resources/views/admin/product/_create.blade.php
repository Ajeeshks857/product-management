@extends('layouts.app')
@section('title')
    {{ config('app.name', 'Laravel') }} | Create
@endsection
@section('content')
    <section class="content" id="create-product">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Create New Product</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" v-model="title" placeholder="Enter title">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea class="form-control" rows="3" v-model="description" placeholder="Enter description"></textarea>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                        <div class="form-group">
                            <label>Main Image</label>
                            <input type="file" class="form-control-file" accept="image/*" v-on:change="onFileChange">
                        </div>
                    </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Main Image</label>
                                <input type="file" class="form-control-file" accept="image/*" v-on:change="onFileChange">
                                <img :src="mainImagePreview" v-if="mainImagePreview" alt="Main Image Preview"
                                    style="max-width: 100px;">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <button class="btn btn-link" @click="toggleVariantSection">Manage Variants</button>
                        </div>
                    </div>

                    <div v-if="showVariantSection">
                        <div class="row mb-3" v-for="(variant, index) in variants" :key="index">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Variant Size</label>
                                    <input type="number" class="form-control" v-model="variant.size">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Variant Color</label>
                                    <input type="text" class="form-control" v-model="variant.color">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button class="btn btn-danger mt-4" @click="removeVariant(index)">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" @click="addVariant">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" @click="submitForm">Submit</button>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('controller/create-product.vue.js') }}?v={{ time() }}"></script>
@endsection
