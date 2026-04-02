{{-- <form id="chatForm">
    @csrf
    <div class="p-3 border-top bg-white d-flex">
        <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Type message...">
        <input type="hidden" id="receiver_id" value="2"> <!-- dynamic karna baad me -->
        <button type="submit" class="btn btn-danger">Send</button>
    </div>
</form> --}}



<form id="chatForm">
    @csrf
    <div class="p-3 border-top bg-white d-flex">
        <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Type message...">

       
        <input type="hidden" id="receiver_id">

        <button type="submit" class="btn btn-danger">Send</button>
    </div>
</form>
