@foreach($messages as $message)
    @include('manufacturer.chat.partials.message', ['message' => $message, 'user' => $user])
@endforeach 