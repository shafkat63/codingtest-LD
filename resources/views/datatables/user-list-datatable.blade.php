<div class="overflow-x-auto">
    <table id="datatable" class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        </tbody>
    </table>
</div>

<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-96">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Edit User</h2>
        </div>
        <div class="p-6 space-y-4">
            <input type="hidden" id="editUserId">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="editUserName"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="editUserEmail"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div class="px-6 py-4 border-t flex justify-end space-x-2">
            <button id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
            <button id="updateUserBtn"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
    var table = $('#datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('users.list') }}",
        dataSrc: function (json) {
            console.log("Raw Data Received:", json); 
            return json.data; 
        }
    },
    columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'created_at'},
        {data: 'action', orderable: false, searchable: false},
    ],
    stripeClasses: ['bg-white', 'bg-gray-50'],
    });

    $(document).on('click', '.editUser', function () {
        $('#editUserId').val($(this).data('id'));
        $('#editUserName').val($(this).data('name'));
        $('#editUserEmail').val($(this).data('email'));
        $('#editUserModal').removeClass('hidden').addClass('flex');
    });

    $('#closeModal').click(function(){
        $('#editUserModal').addClass('hidden').removeClass('flex');
    });

    $('#updateUserBtn').click(function () {
        var id = $('#editUserId').val();
        $.ajax({
            url: '/users/update/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('#editUserName').val(),
                email: $('#editUserEmail').val()
            },
            success: function (response) {
                if(response.status === 'success') {
                    toastr.success(response.message);
                    $('#editUserModal').addClass('hidden').removeClass('flex');
                    table.ajax.reload(null, false);
                } else {
                    $.each(response.errors, function(key, value){
                        toastr.error(value[0]);
                    });
                }
            }
        });
    });
});

</script>