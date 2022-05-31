<li class="nav-item dropdown pe-2 d-flex align-items-center">
    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-fw fas fa-bell fa-lg c-sidebar-nav-icon p-3">
            @if (count($notifications) > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{count($notifications)}}
                <span class="visually-hidden">unread notifications</span>
            </span>
            @endif
        </i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton" style="max-height: 400px;overflow-y: auto;">

        <div class="col-sm-12">
            <p class="dropdown-item">Notifications
                @if (count($notifications) > 0)
                <a href="#" id="mark-all" class="ps-4">Mark all as read</a>
                @endif
            </p>
            <hr class="m-0">
        </div>

        @forelse ($notifications as $notification)
        <li class="p-2">
            <span class="text-capitalize text-dark">{{ $notification->data['title'] }}</span><br>
            <span class="text-muted">{{$notification->data['name']}}, {{$notification->data['email']}}</span>
            <a href="#" class="mark-as-read" data-id="{{$notification->id}}"><i class="fa-fw fas fa-circle-check text-success fa-lg float-end"></i></a>
        </li>

        @empty
        <li class="p-3">
            <span class="text-capitalize text-dark dropdown-item"> There are no new notifications </span>
        </li>

        @endforelse
    </ul>
</li>
@section('scripts')
@parent
<script>
    function sendMarkRequest(id = null){
        return $.ajax("{{ route('notifications.markAsRead') }}", {
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id}
            });
    }

    $(function(){
        $('.mark-as-read').click(function(){
            let request = sendMarkRequest($(this).data('id'));

            request.done(() => {
                $(this).parents('li.p-2').remove();
                Livewire.emit('refreshNotifications');
            });
        });

        $('#mark-all').click(function(){
            let request = sendMarkRequest();

            request.done(() => {
                $('li.p-2').remove();
                Livewire.emit('refreshNotifications');
            });
        });
    })
</script>

@endsection
