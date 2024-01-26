<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Pengguna</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Antrian Pengguna</h1>
        <div id="current-number">
            Nomor Antrian Saat Ini:
            <h1 id="queue-number">0</h1>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;


        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });
        console.log('Pusher connection status:', pusher.connection.state);

        var channel = pusher.subscribe('queue-updates');
        channel.bind('App\\Events\\QueueUpdated', function(data) {
            console.log('QueueUpdated event received:', data);
            updateQueueList(data.updatedQueue);
        });

        function updateQueueList(updatedQueue) {
            $('#queue-number').text(updatedQueue.queue_number);

            var $queueListElement = $('#queue-list-user');
            var $listItem = $('<li>').text(updatedQueue.queue_number);
            $queueListElement.append($listItem);
        }
    </script>
</body>

</html>
