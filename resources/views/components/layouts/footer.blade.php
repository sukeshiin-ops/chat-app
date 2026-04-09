{{-- <form id="chatForm">
    @csrf
    <div class="p-3 border-top bg-white d-flex">
        <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Type message...">
        <input type="hidden" id="receiver_id" value="2"> <!-- dynamic karna baad me -->
        <button type="submit" class="btn btn-danger">Send</button>
    </div>
</form> --}}

{{--

<form id="chatForm">
    @csrf
    <div class="p-3 border-top bg-white d-flex mx-4">
        <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Type message...">


        <input type="hidden" id="receiver_id">

        <button type="submit" class="btn btn-danger">Send</button>
    </div>
</form> --}}


<style>
    .chat-input-box {
        display: flex;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #ddd;
        background: #fff;
        border-radius: 30px;
        margin: 10px;
    }

    .chat-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px 15px;
        border-radius: 20px;
        background: #f1f1f1;
        font-size: 14px;
    }

    .send-btn {
        border: none;
        background: #ff4d4d;
        color: white;
        font-size: 18px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    .send-btn:hover {
        background: #e60000;
    }
</style>

<form id="chatForm">
    @csrf
    <div class="chat-input-box border border-dark border-3">

        <input type="text" name="message" id="messageInput" class="chat-input" placeholder="Type a message..."
            autocomplete="off">

        <input type="hidden" id="receiver_id">

        <button type="submit" class="send-btn">
            ➤
        </button>

    </div>
</form>



