<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h3>Orders List</h3>
                <hr>
                <table id="ordersList" class="table table-bordered table-condensed table-striped" >
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Price</th>
                        <th>Actions</th>
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

            $('#ordersList').DataTable({
                ajax: '/api/DashboardList',
                serverSide: true,
                processing: true,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'phone', name: 'phone'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'price', name: 'price'},
                    {data: 'action', name: 'action'},
                ]
            });
        });

        function deleteOrder(order_id) {
            $.ajax({
                url: 'api/order/' + order_id,
                method: 'delete',
                dataType: 'html',
                data: {order_id: order_id},
                success: function(data){
                    alert(data);
                }
            });
        }

        function editOrder(order_id) {
            window.location.replace('/product/' + order_id);
        }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                    @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                        111
                        @foreach (\App\Models\User::all() as $user)
                            <p>This is user {{ $user->name }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
