<style>
    .chat-input-box {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-top: 1px solid #ddd;
        background: #f0f2f5;
        border-radius: 25px;
        margin: 10px;
        position: relative;
    }

    .chat-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px 15px;
        border-radius: 20px;
        background: #fff;
        font-size: 14px;
        margin: 0 8px;
    }

    .attach-btn {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .send-btn {
        border: none;
        background: #00a884;
        /* WhatsApp green */
        color: white;
        font-size: 16px;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        cursor: pointer;
        transition: 0.2s;
    }

    .send-btn:hover {
        background: #008f72;
    }

    /* Preview box */
    .file-preview-box {
        position: absolute;
        bottom: 60px;
        left: 10px;
        background: #fff;
        border-radius: 10px;
        padding: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 200px;
    }

    /* Image preview */
    .file-preview-box img {
        width: 100%;
        border-radius: 8px;
    }

    /* File name preview */
    .file-preview-box p {
        margin: 0;
        font-size: 13px;
    }

    /* Remove button */
    .remove-file {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 12px;
        cursor: pointer;
    }
</style>

{{-- <form id="chatForm">
    @csrf
    <div class="chat-input-box border border-dark border-3">

        <input type="text" name="message" id="messageInput" class="chat-input" placeholder="Type a message..."
            autocomplete="off">

        <input type="hidden" id="receiver_id">

        <button type="submit" class="send-btn">
            ➤
        </button>

    </div>
</form> --}}


<form id="chatForm" enctype="multipart/form-data">

    @csrf

    <div class="chat-input-box border border-dark border-3">
        <button type="button" class="attach-btn" onclick="document.getElementById('fileInput').click()">
            📎
        </button>
        <input type="text" name="message" id="messageInput" class="chat-input" placeholder="Type a message..."
            autocomplete="off">

        <input type="hidden" id="receiver_id">

        <div id="filePreview" style="font-size:12px; margin-top:5px;"></div>

        <input type="file" id="fileInput" name="file" hidden>


        <button type="submit" class="send-btn">➤</button>

    </div>
</form>


<script>
    document.getElementById('messageInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();

            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }
    });
</script>
<script>
    document.getElementById('fileInput').addEventListener('change', function() {

        let file = this.files[0];

        if (file) {

            let preview = document.getElementById('filePreview');

            if (file.type.startsWith('image')) {

                let reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" width="100">`;
                }

                reader.readAsDataURL(file);

            } else {

                preview.innerHTML = `<p>${file.name}</p>`;
            }
        }
    });
</script>
