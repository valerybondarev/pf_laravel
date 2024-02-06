<div class="container">
    <div class="row">
        <div class="col-md-10">
            <h3>Product List</h3>
            <hr>
            <table id="productList" class="table table-bordered table-condensed table-striped" >
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Created_at</th>
                    <th></th>
                </tr>
                </thead>

            </table>

            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

            <!-- Bootstrap CSS (jsDelivr CDN) -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!-- Bootstrap Bundle JS (jsDelivr CDN) -->
            <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $.noConflict();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        var ProductList = $('#productList').DataTable({
            ajax: '/ProductList' + (typeof window.location.href.split('/product')[1] == 'undefined' ? '' : window.location.href.split('/product')[1]),
            serverSide: true,
            processing: true,
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action'},
            ]
        });
    });

    function deleteProduct(product_id) {

        let dialog = confirm('Удалить?');
        if(dialog) {
            $.ajax({
                url: 'product/' + product_id,
                method: 'delete',
                dataType: 'html',
                data: {product_id: product_id},
                success: function(data){
                    $('#productList').DataTable().ajax.reload();
                }
            });
        }
    }
</script>

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        @if ($errors->any())
            <div class="alert alert-danger">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>
        @endif

        <form method="POST" action="{{ route('setProduct') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-label for="name" :value="__('Название товара')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" />
            </div>

            <!-- Price -->
            <div class="mt-4">
                <x-label for="price" :value="__('Стоимость')" />

                <x-input id="price" class="block mt-1 w-full" type="text" name="price" :value="old('price')" required />
            </div>

            <x-button class="ml-4">
                {{ __('Создать') }}
            </x-button>
        </form>
    </x-auth-card>
</x-guest-layout>

