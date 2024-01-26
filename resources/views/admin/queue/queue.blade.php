<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Admin Dashboard</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h5>Nomor Antrian Saat Ini: <br>
                                    <span id="current-number">
                                        {{ $currentAntrian->queue_number ?? '-' }}
                                    </span>
                                </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">

                                <h5>List Antrian:</h5>
                                <ul id="queue-list">
                                </ul>
                                <button class="btn btn-warning btn-sm" id="navigate-prev">Antrian Sebelumnya</button>
                                <button class="btn btn-success btn-sm" id="navigate-next">Antrian Berikutnya</button>
                            </div>
                            <div class="col-6">

                                <h5>Antrian Selesai:</h5>
                                <ul id="queue-finish">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            var currentQueueNumber = '{{ $currentAntrian->queue_number ?? '-' }}';
            var antrians = @json($antrians);

            var currentQueueIndex = null;


            function showQueueAtIndex(index) {
                if (index < 0) {
                    $('#current-number').text('-');
                    return;
                }
                $('#current-number').text(antrians[index].queue_number);
            }


            $('#navigate-next').click(function() {
                if (currentQueueIndex < antrians.length - 1) {
                    currentQueueIndex++;
                    showQueueAtIndex(currentQueueIndex - 1);
                    console.log('====================================');
                    console.log(antrians[currentQueueIndex - 1].queue_number, currentQueueNumber);
                    console.log('====================================');

                    updateQueueStatus(currentQueueNumber, antrians[currentQueueIndex - 1].queue_number,
                        antrians[currentQueueIndex - 1].id, 'next');
                }
            });


            $('#navigate-prev').click(function() {
                if (currentQueueIndex > 0) {
                    currentQueueIndex--;
                    showQueueAtIndex(currentQueueIndex - 1);
                    console.log('====================================');
                    console.log(antrians[currentQueueIndex - 1].queue_number), currentQueueNumber;
                    console.log('====================================');

                    updateQueueStatus(currentQueueNumber, antrians[currentQueueIndex - 1].queue_number),
                        antrians[currentQueueIndex - 1].id, 'prev';
                }
            });


            function updateQueueList() {
                $.ajax({
                    url: '/antrians/list/',
                    type: 'GET',
                    success: function(listAntrians) {
                        console.log('Daftar antrian.');

                        $('#queue-list').empty();
                        listAntrians.forEach(function(antrian) {
                            $('#queue-list').append('<li>' + antrian.queue_number +
                                '</li>');
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching finished queues: ' + error.responseText);
                    }
                });
            }


            function updateQueueStatus(currentQueueNumber, queueNumber, queueId, direction) {
                $.ajax({
                    url: '/antrians/navigate/',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        current_queue_number: currentQueueNumber,
                        id: queueId,
                        queue_number: queueNumber,
                        direction: direction
                    },
                    success: function(response) {

                        console.log('====================================');
                        console.log(response);
                        console.log('====================================');
                        if (response.success) {
                            console.log('Status antrian berhasil diperbarui.');
                            updateFinishedQueueList();
                            updateQueueList();
                        } else {
                            console.error('Gagal memperbarui status antrian.');
                        }
                    },
                    error: function(error) {
                        console.error('Error updating queue status: ' + error.responseText);
                    }
                });
            }


            function updateFinishedQueueList() {
                $.ajax({
                    url: '/antrians/finish/',
                    type: 'GET',
                    success: function(finishedAntrians) {
                        console.log('Daftar antrian selesai berhasil diperbarui.');

                        $('#queue-finish').empty();
                        finishedAntrians.forEach(function(antrianSelesai) {
                            $('#queue-finish').append('<li>' + antrianSelesai.queue_number +
                                '</li>');
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching finished queues: ' + error.responseText);
                    }
                });
            }

            updateQueueList();
            updateFinishedQueueList();
        });
    </script>

</body>

</html>
