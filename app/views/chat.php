<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>Realtime Chat</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f1f5f9;
}

.chat-container{
    width:700px;
    height:90vh;
    margin:20px auto;
    background:white;
    border-radius:20px;
    display:flex;
    flex-direction:column;
    overflow:hidden;
}

.header{
    background:#2563eb;
    color:white;
    padding:20px;
    font-size:22px;
    font-weight:bold;
}

.messages{
    flex:1;
    overflow-y:auto;
    padding:20px;
}

.message{
    background:#e2e8f0;
    padding:12px;
    border-radius:12px;
    margin-bottom:15px;
}

.message strong{
    color:#2563eb;
}

.form{
    display:flex;
    padding:20px;
    border-top:1px solid #ddd;
}

.form input{
    flex:1;
    padding:15px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:16px;
}

.form button{
    margin-left:10px;
    padding:15px 25px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:10px;
    cursor:pointer;
}

</style>

</head>
<body>

<div class="chat-container">

    <div class="header">

        🚨 SOS CHAT ROOM

    </div>

    <div
        class="messages"
        id="messages"
    >

    </div>

    <div class="form">

        <input
            type="text"
            id="messageInput"
            placeholder="Nhập tin nhắn..."
        >

        <button onclick="sendMessage()">

            Gửi

        </button>

    </div>

</div>

<script>

const sosId =
<?= $sos_id; ?>;

async function loadMessages()
{
    const response =
    await fetch(
        `/2026_SOS/public/chat/messages?sos_id=${sosId}`
    );

    const data =
    await response.json();

    let html = '';

    data.forEach(item => {

        html += `
            <div class="message">

                <strong>
                    ${item.name}
                </strong>

                <br><br>

                ${item.message}

            </div>
        `;
    });

    document.getElementById(
        'messages'
    ).innerHTML = html;

    document.getElementById(
        'messages'
    ).scrollTop =
    document.getElementById(
        'messages'
    ).scrollHeight;
}

async function sendMessage()
{
    const message =
    document.getElementById(
        'messageInput'
    ).value;

    if(message == '') return;

    await fetch(
        '/2026_SOS/public/chat/send',
        {
            method:'POST',

            headers:{
                'Content-Type':
                'application/x-www-form-urlencoded'
            },

            body:
            `sos_id=${sosId}&message=${message}`
        }
    );

    document.getElementById(
        'messageInput'
    ).value = '';

    loadMessages();
}

setInterval(loadMessages, 2000);

loadMessages();

</script>

</body>
</html>